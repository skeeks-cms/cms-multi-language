<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 27.04.2016
 */

namespace skeeks\cms\multiLanguage\widgets\selectLanguage;

use skeeks\cms\models\CmsLang;
use yii\base\Widget;
use yii\helpers\Json;

/**
 * @property string $modalId
 *
 * Class SelectLanguage
 * @package common\widgets\selectLanguage
 */
class SelectLanguage extends Widget
{
    public function run()
    {
        parent::run();

        $options = [];
        $options['modalId'] = $this->modalId;

        $jsOptions = Json::encode($options);

        SelectLanguageAsset::register($this->view);

        $this->view->registerJs(<<<JS
        sx.SelectLanguage = new sx.classes.SelectLanguage({$jsOptions});
JS
        );
        $key = 'app-select-lang-'.\Yii::$app->language;
        $langsData = \Yii::$app->cache->get($key);
        if (!$langsData) {
            $langs = \skeeks\cms\models\CmsLang::find()->with('image')->active()->orderBy(['name' => SORT_ASC])->all();

            /**
             * @var CmsLang $lang
             */
            foreach ($langs as $lang) {
                $langsData[] =
                    [
                        'code'  => $lang->code,
                        'name'  => $lang->name,
                        'image' => $lang->image ? $lang->image->src : \skeeks\cms\helpers\Image::getSrc(),
                    ];
            }

            \Yii::$app->cache->set($key, $langsData, 86400);
        }


        /*if (!isset(\Yii::$app->multiLanguage->modals['selec-lang-modal']))
        {
            \Yii::$app->project->modals['selec-lang-modal'] = $this->render('_modal', [
                'data' => $langsData
            ]);
        }*/

        $result = $this->render('_modal', [
            'data' => $langsData,
        ]);

        $result .= $this->render('default');
        return $result;
    }

    /**
     * @return string
     */
    public function getModalId()
    {
        return $this->id."-container";
    }

}