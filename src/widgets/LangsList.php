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
 * @author Semenov Alexander <semenov@skeeks.com>
 */
class LangsList extends Widget
{
    /**
     * @var string
     */
    public $viewFile = 'langs-list';

    /**
     * @var array
     */
    public $options = [

    ];

    public function init()
    {
        Html::addCssClass($this->options, "sx-langs-list-wrapper");
        $this->options['id'] = $this->id;
    }

    public function run()
    {
        return $this->render($this->viewFile);
    }
}