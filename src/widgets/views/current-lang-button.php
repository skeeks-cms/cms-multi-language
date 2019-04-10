<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 27.04.2016
 */
/* @var $this yii\web\View */
/* @var $widget \skeeks\cms\multiLanguage\widgets\CurrentLangButton */

$name = \skeeks\cms\helpers\StringHelper::ucfirst( \Yii::$app->cms->cmsLanguage->name ); //Испходное название языка
$currentName = \skeeks\cms\helpers\StringHelper::ucfirst( \Yii::t('skeeks/multi-lang/langs', \Yii::$app->cms->cmsLanguage->name)); //Переведенное на текущий язык
$myLangName = $currentName;
if (\Yii::$app->language != \Yii::$app->multiLanguage->default_lang) {
    $myLangName = \skeeks\cms\helpers\StringHelper::ucfirst( \Yii::t('skeeks/multi-lang/langs', \Yii::$app->cms->cmsLanguage->name, [], \Yii::$app->multiLanguage->default_lang)); //Переведенное на моя желаемый язык
}

$this->registerCss(<<<CSS
.sx-current-lang-button-wrapper
{
    cursor: pointer;
}

.sx-btn-select-language
{
    border-bottom: 1px dashed;
}
.sx-btn-select-language:hover
{
    text-decoration: none;
}
CSS
);
$widget = $this->context;
?>
<?= \yii\helpers\Html::beginTag('div', $widget->options); ?>
    <img src="<?= \skeeks\cms\helpers\Image::getSrc(\Yii::$app->cms->cmsLanguage->image ? \Yii::$app->cms->cmsLanguage->image->src : null); ?>" height="20"/>
    <a href="#" onclick="return false;" class="sx-btn-select-language">
        <?= $currentName; ?>
        <? if ($myLangName != $currentName ) : ?>
            (<?= $myLangName; ?>)
        <? endif; ?>
    </a>
<?= \yii\helpers\Html::endTag('div'); ?>

