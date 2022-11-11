<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */

namespace skeeks\cms\multiLanguage;

use skeeks\cms\backend\BackendComponent;
use skeeks\cms\models\CmsContentElement;
use skeeks\cms\models\CmsContentProperty;
use skeeks\cms\models\CmsLang;
use skeeks\cms\models\CmsTreeTypeProperty;
use skeeks\cms\models\Tree;
use skeeks\cms\multiLanguage\widgets\detectLanguage\DetectLanguage;
use skeeks\cms\mysqlSession\DbSession;
use skeeks\modules\cms\form2\models\Form2FormProperty;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\web\Application;
use yii\web\View;

/**
 * @property CmsLang[] $cmsLangs
 * @property string    $langPrefix
 *
 * @author Semenov Alexander <semenov@skeeks.com>
 */
class MultiLangComponent extends \skeeks\yii2\multiLanguage\MultiLangComponent implements BootstrapInterface
{
    /**
     * @var Подключить скрипт автоопределения языка пользователя?
     */
    public $isRenderDetectWidget = true;

    /**
     * @var bool Автоматически перенаправлять на определенный язык и сохранять эти данные в сессию
     */
    public $isRedirectToDetect = true;

    /**
     *
     */
    public function init()
    {
        parent::init();

        if ($this->cmsLangs) {
            $this->langs = [];
            foreach ($this->cmsLangs as $cmsLang) {
                $this->langs[] = $cmsLang->code;
            }
        }

        $this->default_lang = \Yii::$app->cms->languageCode;
    }


    public function bootstrap($application)
    {
        if ($application instanceof Application) {

            if (class_exists(Form2FormProperty::class)) {
                Event::on(Form2FormProperty::class, Form2FormProperty::EVENT_AFTER_FIND, function (Event $e) {
                    $e->handled = false;
                    if (BackendComponent::getCurrent()) {
                        return true;
                    }

                    /**
                     * @var $model Form2FormProperty
                     */
                    $model = $e->sender;
                    $model->name = \Yii::t('app', $model->name);
                });
            }

            Event::on(Tree::class, Tree::EVENT_AFTER_FIND, function (Event $e) {
                /**
                 * @var $model Tree
                 */
                $model = $e->sender;

                if (BackendComponent::getCurrent()) {
                    return true;
                }

                if (\Yii::$app->language == $this->default_lang) {
                    return true;
                }

                $fields = $this->_getLangFielsForTree($model);
                if (!$fields) {
                    return true;
                }

                foreach ($model->toArray($fields) as $key => $value) {
                    if ($value = $model->relatedPropertiesModel->getAttribute($this->langPrefix.$key)) {
                        $model->{$key} = $value;
                    }
                }
            });

            Event::on(CmsContentElement::class, CmsContentElement::EVENT_AFTER_FIND, function (Event $e) {
                /**
                 * @var $model Tree
                 */
                $model = $e->sender;

                if (BackendComponent::getCurrent()) {
                    return true;
                }

                if (\Yii::$app->language == $this->default_lang) {
                    return true;
                }

                $fields = $this->_getLangFielsForElement($model);
                if (!$fields) {
                    return true;
                }

                foreach ($model->toArray($fields) as $key => $value) {
                    if ($value = $model->relatedPropertiesModel->getAttribute($this->langPrefix.$key)) {
                        $model->{$key} = $value;
                    }
                }
            });

            $application->view->on(View::EVENT_END_BODY, function (Event $e) {
                //Перенаправить пользователя на нужный язык
                if ($this->isRedirectToDetect) {

                    if (\Yii::$app->session instanceof DbSession) {
                        if (!\Yii::$app->session->isBot()) {
                            //Если это первый переход на сайт определяем лучший язык и сохраняем
                            if (!\Yii::$app->session->get("LANG_DETECT")) {
                                $preferedLanguage = \Yii::$app->request->getPreferredLanguage(array_keys(\Yii::$app->cms->languages));
                                \Yii::$app->session->set("LANG_DETECT", $preferedLanguage);
                                if (\Yii::$app->language != $preferedLanguage) {

                                    $urlData = [];
                                    $params = \Yii::$app->request->getQueryParams();
                                    if ($params) {
                                        $params = \yii\helpers\ArrayHelper::merge($params, ['lang' => $preferedLanguage]);
                                    } else {
                                        $params = \yii\helpers\ArrayHelper::merge([], ['lang' => $preferedLanguage]);
                                    }
                                    $route = \Yii::$app->requestedRoute;
                                    $urlData = ["/".$route];

                                    $urlData = \yii\helpers\ArrayHelper::merge($urlData, $params);
                                    \Yii::$app->response->redirect(\yii\helpers\Url::to($urlData));
                                    \Yii::$app->end();

                                }
                            }
                        }
                    }

                }

                if ($this->isRenderDetectWidget) {
                    echo DetectLanguage::widget();
                }
            });
        }


        return parent::bootstrap($application);
    }

    /**
     * Объекты языков проекта.
     *
     * @var CmsLang[]
     */
    protected $_cms_langs = null;

    /**
     * @var string Разделитель кода языка от названия свойтва
     */
    public $lang_delimetr = "__";

    /**
     * @return string
     */
    public function getLangPrefix()
    {
        return \Yii::$app->language.$this->lang_delimetr;
    }

    /**
     * Объекты языков проекта.
     *
     * @return array|CmsLang[]
     */
    public function getCmsLangs()
    {
        if ($this->_cms_langs === null) {
            $this->_cms_langs = CmsLang::find()->with('image')->active()->all();
        }

        return $this->_cms_langs;
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

    protected $_possible_fields_for_tree = null;

    public function _getLangFielsForTree(Tree $model)
    {
        if ($this->_possible_fields_for_tree === null) {
            $fields = [];
            /**
             * @var CmsTreeTypeProperty $property
             */
            $allowAttributes = array_keys($model->toArray());
            foreach (CmsTreeTypeProperty::find()->all() as $property) {
                if ($property->code) {
                    if (substr($property->code, 0, strlen($this->langPrefix)) == $this->langPrefix) {

                        $fieldName = substr($property->code, strlen($this->langPrefix), strlen($property->code));
                        $fields[] = $fieldName;
                    }
                }

            }

            $this->_possible_fields_for_tree = $fields;
        }
        return $this->_possible_fields_for_tree;
    }

}