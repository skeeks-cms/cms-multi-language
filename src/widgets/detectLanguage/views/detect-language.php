<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 06.03.2015
 *
 * @var \skeeks\cms\multiLanguage\widgets\detectLanguage\DetectLanguage $widget
 */
$widget = $this->context;

$urlData = [];
$params = \Yii::$app->request->getQueryParams();
if ($params)
{
	$params = \yii\helpers\ArrayHelper::merge($params, ['lang' => $widget->preferredLanguage]);
} else
{
	$params = \yii\helpers\ArrayHelper::merge([], ['lang' => $widget->preferredLanguage]);
}
$route = \Yii::$app->requestedRoute;
$urlData = ["/" . $route];

$urlData = \yii\helpers\ArrayHelper::merge($urlData, $params);

?>
<? $modal = \yii\bootstrap\Modal::begin([
    'id'        => $widget->id,
    'header'    => \Yii::t('skeeks/multi-lang/main', 'Change the language on the site?', [], $widget->preferredLanguage),
    'footer'    => '
					<a class="btn btn-primary" href="' . \yii\helpers\Url::to($urlData) . '">
						' . \Yii::t('yii', 'Yes', [], $widget->preferredLanguage) . '
					</a>
					<button type="button" class="btn btn-default" data-dismiss="modal">
                        ' . \Yii::t('yii', 'No', [], $widget->preferredLanguage) . '
                    </button>',
]); ?>
	<?= Yii::t('skeeks/multi-lang/main', 'We automatically determined your language and assume that your language is', [], $widget->preferredLanguage) . ": «" . $widget->preferredLanguage . "»"; ?><br />
	<?= Yii::t('skeeks/multi-lang/main', 'Turn your language version of the site?', [], $widget->preferredLanguage); ?>
<? $modal::end(); ?>