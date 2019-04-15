<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */

namespace skeeks\cms\multiLanguage\console\controllers;

use skeeks\cms\i18nDb\models\Message;
use skeeks\cms\i18nDb\models\SourceMessage;
use skeeks\cms\models\CmsContentElement;
use skeeks\cms\models\CmsContentProperty;
use yii\base\InvalidConfigException;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * @author Semenov Alexander <semenov@skeeks.com>
 */
class TranslateController extends Controller
{
    protected function _ensureGoolgeApi() {
        if (!isset(\Yii::$app->googleApi)) {
            throw new InvalidConfigException("Please install skeeks/cms-google-api-translate!");
        }
    }

    /**
     * @param \yii\base\Action $action
     * @return bool
     * @throws InvalidConfigException
     */
    public function beforeAction($action)
    {
        $this->_ensureGoolgeApi();
        return parent::beforeAction($action);
    }

    /**
     * @throws InvalidConfigException
     */
    public function actionMessages()
    {
        /**
         * @var $sourceMessage SourceMessage
         * @var $message Message
         */
        $query = SourceMessage::find();

        $this->stdout("Total messages: " . $query->count() . "\n");
        $this->stdout("Langs: " . print_r(\Yii::$app->multiLanguage->langs, true) . "\n");

        foreach ($query->each(100) as $sourceMessage)
        {
            $this->stdout("\t" .  $sourceMessage->category . " — " . $sourceMessage->message . "\n");
            $sourceMessage->initMessages();
            foreach ($sourceMessage->messages as $lang => $message)
            {
                if (!$message->translation) {
                    try {
                        $message->id = $sourceMessage->id;
                        $message->translation = \Yii::$app->googleApi->serviceTranslate->translate($sourceMessage->message, $lang);
                        if ($message->save()) {
                            $this->stdout("\t\t{$lang} — new translated\n", Console::FG_GREEN);
                        } else {
                            $this->stdout("\t\t{$lang} — not save!\n" . print_r($message->errors, true) . print_r($message->toArray(), true), Console::FG_RED);
                        }
                    } catch (\Exception $e) {
                        $this->stdout("\t\t{$lang} — {$e->getMessage()}\n", Console::FG_RED);
                    }

                } else {
                    $this->stdout("\t\t{$lang} — translated");
                }
            }

        }
    }

    /**
     * Перевод свойств у элементов контента
     */
    public function actionContentElements()
    {
        /**
         * @var CmsContentElement $cmsContentElement
         */
        $query = CmsContentElement::find();

        $this->stdout("Total CmsContentElements: " . $query->count() . "\n");
        $this->stdout("Langs: " . print_r(\Yii::$app->multiLanguage->langs, true) . "\n");

        foreach ($query->each(100) as $cmsContentElement)
        {
            $fields = $this->_getLangFielsForElement($model);
            if (!$fields) {
                $this->stdout("\t{$cmsContentElements->id} — no fields\n");
                continue;
            }

            foreach ($cmsContentElement->toArray($fields) as $key => $value) {
                if ($value = $model->relatedPropertiesModel->getAttribute($this->langPrefix.$key)) {
                    $model->{$key} = $value;
                }
            }
        }
    }


    protected $_possible_fields_for_elements = null;

    protected function _getLangFielsForElement(CmsContentElement $model)
    {
        if ($this->_possible_fields_for_elements === null) {
            $fields = [];
            /**
             * @var CmsContentProperty $property
             */
            $allowAttributes = array_keys($model->toArray());
            foreach (CmsContentProperty::find()->all() as $property) {
                if ($property->code) {
                    if (substr($property->code, 0, strlen($this->langPrefix)) == $this->langPrefix) {
                        $fieldName = substr($property->code, strlen($this->langPrefix), strlen($property->code));
                        $fields[] = $fieldName;
                    }
                }

            }

            $this->_possible_fields_for_elements = $fields;
        }
        return $this->_possible_fields_for_elements;
    }
}