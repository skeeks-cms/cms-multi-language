<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 27.04.2016
 */

namespace skeeks\cms\multiLanguage\widgets;

use skeeks\cms\models\CmsLang;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * Виджет показывающий кнопку текущего языка
 *
 * @author Semenov Alexander <semenov@skeeks.com>
 */
class CurrentLangButton extends Widget
{
    /**
     * @var string
     */
    public $viewFile = 'current-lang-button';

    /**
     * @var array
     */
    public $options = [

    ];

    public function init()
    {
        Html::addCssClass($this->options, "sx-current-lang-button-wrapper");
        $this->options['id'] = $this->id;
    }

    public function run()
    {
        return $this->render($this->viewFile);
    }
}