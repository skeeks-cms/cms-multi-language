<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
return [
    'modules' => [
        'multiLang' => [
            'class' => \skeeks\cms\multiLanguage\MultiLangModule::class,
            'controllerNamespace' => 'skeeks\cms\multiLanguage\console\controllers'
        ]
    ],
];