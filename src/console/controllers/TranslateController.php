<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */

namespace skeeks\cms\multiLanguage\console\controllers;

use skeeks\cms\i18nDb\models\SourceMessage;
use yii\base\InvalidConfigException;
use yii\console\Controller;

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
         */
        $query = SourceMessage::find();

        $this->stdout("Total messages: " . $query->count());

        foreach ($query->each(100) as $sourceMessage)
        {

        }
    }
}