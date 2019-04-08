<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 27.04.2016
 */
/* @var $this yii\web\View */
/* @var $widget \skeeks\cms\multiLanguage\widgets\selectLanguage\SelectLanguage */
/* @var $data array */
$widget = $this->context;

?>
<? $modal = \yii\bootstrap\Modal::begin([
    'id'     => $widget->modalId,
    'header' => \Yii::t('app', 'Select your language'),
    'footer' => '<button type="button" class="btn btn-default" data-dismiss="modal">
                        '.\Yii::t('app', 'Close').'
                    </button>',
]); ?>


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
.sx-lang-ru, .sx-lang-en
{
    font-weight: bold;
}
CSS
)
?>
<? /* if ($this->beginCache('app-language', ['variations' => [Yii::$app->language]])) : */ ?>
<div class="sx-select-language" style="overflow: hidden;">
    <ul class="row">
        <? foreach ($data as $array) : ?>
            <? if (\Yii::$app->language == \Yii::$app->sourceLanguage) : ?>
                <? $title = $array['name']; ?>
            <? else : ?>
                <? $title = \skeeks\cms\helpers\StringHelper::ucfirst(\Yii::t('app', $array['name']))." [".$array['name']."]" ?>
            <? endif; ?>
            <li class="col-sm-6">
                <?
                $urlData = [];
                $params = \Yii::$app->request->getQueryParams();
                if ($params) {
                    $params = \yii\helpers\ArrayHelper::merge($params, ['lang' => $array['code']]);
                } else {
                    $params = \yii\helpers\ArrayHelper::merge([], ['lang' => $array['code']]);
                }
                $route = \Yii::$app->requestedRoute;
                $urlData = ["/".$route];

                $urlData = \yii\helpers\ArrayHelper::merge($urlData, $params);
                ?>
                <a href="<?= \yii\helpers\Url::to($urlData) ?>" class="sx-lang-<?= $array['code']; ?>" title="<?= $title; ?>">
                    <img src="<?= $array['image']; ?>" class="sx-flag" alt="<?= $array['code']; ?>"/>
                    <?= $title; ?>
                </a>
            </li>
        <? endforeach; ?>
    </ul>
</div>
<? /* $this->endCache(); */ ?><!--
                --><? /* endif; */ ?>


<? $modal::end(); ?>

