<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 27.04.2016
 */
/* @var $this yii\web\View */
/* @var $widget \skeeks\cms\multiLanguage\widgets\selectLanguage\SelectLanguage */
$this->registerCss(<<<CSS
.sx-change-language
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
<div class="sx-change-language">
    <img src="<?= \skeeks\cms\helpers\Image::getSrc(\Yii::$app->cms->cmsLanguage->image ? \Yii::$app->cms->cmsLanguage->image->src : null); ?>" height="20"/>
    <a href="#" class="sx-btn-select-language">
        <?= \skeeks\cms\helpers\StringHelper::ucfirst( \Yii::t('app', \Yii::$app->cms->cmsLanguage->name) ); ?>
        <? if (\Yii::$app->language != \Yii::$app->sourceLanguage) : ?>
            (<?= \Yii::$app->cms->cmsLanguage->name; ?>)
        <? endif; ?>
    </a>
</div>


