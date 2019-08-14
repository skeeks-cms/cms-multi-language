<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 27.04.2016
 */
/* @var $this yii\web\View */
/* @var $widget \skeeks\cms\multiLanguage\widgets\LangsList */
/* @var $data array */
$widget = $this->context;

?>
<!-- body modal -->
<?
$this->registerCss(<<<CSS

.sx-select-language .sx-flag
{
    width: 20px;
}

.sx-select-language ul li
{
    list-style: none;
    padding-top: 2px;
    padding-bottom: 2px;
    height: 26px;
    white-space: nowrap;
    overflow: hidden;
}
/*
.sx-lang-ru, .sx-lang-en
{
    font-weight: bold;
}*/

CSS
)
?>
<? /* if ($this->beginCache('app-language', ['variations' => [Yii::$app->language]])) : */ ?>
<div class="sx-select-language" style="overflow: hidden;">
    <ul class="row">
        <? foreach (\Yii::$app->multiLanguage->getCmsLangs()->w as $cmsLang) : ?>

            <?
                $name = \skeeks\cms\helpers\StringHelper::ucfirst( $cmsLang->name ); //Испходное название языка
                $currentName = \skeeks\cms\helpers\StringHelper::ucfirst( \Yii::t('skeeks/multi-lang/langs', $cmsLang->name)); //Переведенное на текущий язык

                $myLangName = $currentName;

                if (\Yii::$app->language != \Yii::$app->multiLanguage->default_lang) {
                    $myLangName = \skeeks\cms\helpers\StringHelper::ucfirst( \Yii::t('skeeks/multi-lang/langs', $cmsLang->name, [], \Yii::$app->multiLanguage->default_lang)); //Переведенное на моя желаемый язык
                }

                $title = $currentName;

                if ($myLangName != $currentName) {
                    $title .= " [{$myLangName}]";
                }
            ?>

            <li class="col-sm-4">
                <?
                $urlData = [];
                $params = \Yii::$app->request->getQueryParams();
                if ($params) {
                    $params = \yii\helpers\ArrayHelper::merge($params, ['lang' => $cmsLang->code]);
                } else {
                    $params = \yii\helpers\ArrayHelper::merge([], ['lang' => $cmsLang->code]);
                }
                $route = \Yii::$app->requestedRoute;
                $urlData = ["/".$route];

                $urlData = \yii\helpers\ArrayHelper::merge($urlData, $params);
                ?>
                <a href="<?= \yii\helpers\Url::to($urlData) ?>" class="sx-lang-<?= $cmsLang->code; ?>" title="<?= $title; ?>">
                    <img src="<?= $cmsLang->image ? $cmsLang->image->src : \skeeks\cms\helpers\Image::getCapSrc(); ?>" class="sx-flag" alt="<?= $cmsLang->code; ?>"/>
                    <?= $title; ?>
                </a>
            </li>
        <? endforeach; ?>
    </ul>
</div>

