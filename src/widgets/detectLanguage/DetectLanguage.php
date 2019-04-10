<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 25.04.2016
 */

namespace skeeks\cms\multiLanguage\widgets\detectLanguage;

use skeeks\cms\multiLanguage\widgets\detectLanguage\assets\DetectLanguageAsset;
use yii\base\Widget;
use yii\helpers\Json;
use yii\web\Cookie;

/**
 * @property string $preferredLanguage
 * @property bool   $isSavedLanguage
 * @property bool   $isDifferent
 *
 * @author Semenov Alexander <semenov@skeeks.com>
 */
class DetectLanguage extends Widget
{
    /**
     * @var string
     */
    public $cookieName = 'language';

    /**
     * @var int
     */
    public $showDelay = 5000;

    /**
     * @var float|int
     */
    public $expire = 60 * 60 * 24; // 1 day

    /**
     * @var string
     */
    public $viewFile = 'detect-language';


    public function getPreferredLanguage()
    {
        return \Yii::$app->request->getPreferredLanguage(array_keys(\Yii::$app->cms->languages));
    }

    /**
     * @return bool
     */
    public function getIsSavedLanguage()
    {
        return isset(\Yii::$app->request->cookies[$this->cookieName]);
    }

    /**
     * @return bool
     */
    public function getIsDifferent()
    {
        return (bool)($this->preferredLanguage != \Yii::$app->language);
    }

    public function run()
    {
        parent::run();

        $options = [];

        $options['preferredLanguage'] = $this->preferredLanguage;
        $options['isSavedLanguage'] = $this->isSavedLanguage;
        $options['isDifferent'] = $this->isDifferent;
        $options['showDelay'] = $this->showDelay;
        $options['appLanguage'] = \Yii::$app->language;
        $options['id'] = $this->id;

        $jsOptions = Json::encode($options);

        DetectLanguageAsset::register($this->view);
        $this->view->registerJs(<<<JS
        sx.LanguageDetector = new sx.classes.LanguageDetector({$jsOptions});
JS
        );

        //Язык еще не сохраняли?
        if (!$this->isSavedLanguage) {
            $languageCookie = new Cookie([
                'name'   => $this->cookieName,
                'value'  => \Yii::$app->language,
                'domain' => '.'.\Yii::$app->request->hostName,
                //'expire' => time() + 60 * 60 * 24 * 30, // 30 days
                'expire' => (int)\Yii::$app->formatter->asTimestamp(time()) + $this->expire, // 30 days
            ]);
            \Yii::$app->response->cookies->add($languageCookie);
        }

        return $this->render($this->viewFile);
    }


}