<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */

namespace skeeks\cms\multiLanguage\console\controllers;

use skeeks\cms\helpers\StringHelper;
use skeeks\cms\i18nDb\models\Message;
use skeeks\cms\i18nDb\models\SourceMessage;
use skeeks\cms\models\CmsContentElement;
use skeeks\cms\models\CmsContentProperty;
use skeeks\cms\models\CmsTree;
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
                    $this->stdout("\t\t{$lang} — translated\n");
                }
            }

        }
    }

    /**
     * Перевод свойств у элементов контента
     * @throws \Exception
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
            $this->stdout("\t{$cmsContentElement->id} — {$cmsContentElement->name}\n");
            $rp = $cmsContentElement->relatedPropertiesModel;
            $rpSave = false;
            foreach (\Yii::$app->multiLanguage->langs as $lang)
            {
                $this->stdout("\t\t{$lang}\n");
                foreach ($cmsContentElement->toArray() as $key => $value) {
                    $attrName = $lang . \Yii::$app->multiLanguage->lang_delimetr . $key;

                    if ($rp->hasAttribute($attrName)) {
                        if ($v = $rp->getAttribute($attrName)) {
                            $this->stdout("\t\t\t{$attrName} — {$v}\n");
                            continue;
                        } elseif ($value) {

                            if (in_array($key, ['name', 'meta_title', 'meta_keywords', 'meta_description'])) {
                                $translated = \Yii::$app->googleApi->serviceTranslate->translate($value, $lang);
                                $rp->setAttribute($attrName, $translated);
                                $rpSave = true;
                                $this->stdout("\t\t\t{$attrName} — {$translated} (new)\n", Console::FG_GREEN);
                            } elseif (in_array($key, ['description_short', 'description_full'])) {
                                //$translated = \Yii::$app->googleApi->serviceTranslate->translate($value, $lang, null, 'html');
                                if (StringHelper::strlen($value) < 1500) {
                                    $this->stdout("\t\t\t\t" . StringHelper::strlen($value) . "\n");
                                    $translated = \Yii::$app->googleApi->serviceTranslate->translate($value, $lang, null, 'html');
                                    $rp->setAttribute($attrName, $translated);
                                    $rpSave = true;
                                    $this->stdout("\t\t\t{$attrName} — {$translated} (new)\n", Console::FG_GREEN);
                                }

                                /*$rp->setAttribute($attrName, $translated);
                                $rpSave = true;
                                $this->stdout("\t\t\t{$attrName} — {$translated} (new)\n", Console::FG_GREEN);*/
                            } else {
                                $this->stdout("\t\t\t{$attrName} — !!!\n", Console::FG_RED);
                            }
                            continue;
                        } else {
                            $this->stdout("\t\t\t{$attrName} — no source value!!!\n", Console::FG_RED);
                        }
                    }
                }
            }

            if ($rpSave) {
                if ($rp->save()) {
                    $this->stdout("\tSaved\n", Console::FG_GREEN);
                } else {
                    $this->stdout("\tNot saved: " . print_r($rp->errors, true) . "\n", Console::FG_RED);
                }
            }
        }
    }

    /**
     * Перевод свойств у элементов контента
     * @throws \Exception
     */
    public function actionTree()
    {
        /**
         * @var CmsTree $tree
         */
        $query = CmsTree::find();

        $this->stdout("Total trees: " . $query->count() . "\n");
        $this->stdout("Langs: " . print_r(\Yii::$app->multiLanguage->langs, true) . "\n");

        foreach ($query->each(100) as $tree)
        {
            $this->stdout("\t{$tree->id} — {$tree->name}\n");
            $rp = $tree->relatedPropertiesModel;
            $rpSave = false;
            foreach (\Yii::$app->multiLanguage->langs as $lang)
            {
                $this->stdout("\t\t{$lang}\n");
                foreach ($tree->toArray() as $key => $value) {
                    $attrName = $lang . \Yii::$app->multiLanguage->lang_delimetr . $key;

                    if ($rp->hasAttribute($attrName)) {
                        $v = $rp->getAttribute($attrName);
                        /*var_dump($attrName);
                        var_dump($v);*/
                        if ($v) {
                            $this->stdout("\t\t\t{$attrName} — {$v}\n");
                            continue;
                        } elseif ($value) {

                            if (in_array($key, ['name', 'meta_title', 'meta_keywords', 'meta_description'])) {
                                $translated = \Yii::$app->googleApi->serviceTranslate->translate($value, $lang);
                                $rp->setAttribute($attrName, $translated);
                                $rpSave = true;
                                $this->stdout("\t\t\t{$attrName} — {$translated} (new)\n", Console::FG_GREEN);
                            } elseif (in_array($key, ['description_short', 'description_full'])) {

                                if (StringHelper::strlen($value) < 1500) {
                                    $this->stdout("\t\t\t\t" . StringHelper::strlen($value) . "\n");
                                    $translated = \Yii::$app->googleApi->serviceTranslate->translate($value, $lang, null, 'html');
                                    $rp->setAttribute($attrName, $translated);
                                    $rpSave = true;
                                    $this->stdout("\t\t\t{$attrName} — {$translated} (new)\n", Console::FG_GREEN);
                                }

                            } else {
                                $this->stdout("\t\t\t{$attrName} — !!!\n", Console::FG_RED);
                            }
                            continue;
                        } else {
                            $this->stdout("\t\t\t{$attrName} — no source value!!!\n", Console::FG_RED);
                        }
                    }
                }
            }

            if ($rpSave) {
                if ($rp->save()) {
                    $this->stdout("\tSaved\n", Console::FG_GREEN);
                } else {
                    $this->stdout("\tNot saved: " . print_r($rp->errors, true) . "\n", Console::FG_RED);
                }
            }
        }
    }

}