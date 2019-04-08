<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 27.04.2016
 */
namespace skeeks\cms\multiLanguage\widgets\selectLanguage;

use skeeks\cms\base\AssetBundle;
use skeeks\sx\assets\Custom;
use yii\bootstrap\BootstrapPluginAsset;
use yii\web\YiiAsset;

/**
 * Class SelectLanguage
 * @package common\widgets\selectLanguage
 */
class SelectLanguageAsset extends AssetBundle
{
    public $sourcePath = '@skeeks/cms/multiLanguage/widgets/selectLanguage/assets';
    public $css = [];
    public $js = [
        'js/select-language.js',
    ];
    public $depends = [
        YiiAsset::class,
        Custom::class,
        BootstrapPluginAsset::class,
    ];
}