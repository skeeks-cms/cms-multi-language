<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */

namespace skeeks\cms\multiLanguage\widgets\detectLanguage\assets;

use skeeks\cms\base\AssetBundle;

/**
 * @author Semenov Alexander <semenov@skeeks.com>
 */
class DetectLanguageAsset extends AssetBundle
{
    public $sourcePath = '@skeeks/cms/multiLanguage/widgets/detectLanguage/assets/src';

    public $css = [];

    public $js = [
        'detect-language.js',
    ];

    public $depends = [
        'skeeks\sx\assets\Core',
    ];
}