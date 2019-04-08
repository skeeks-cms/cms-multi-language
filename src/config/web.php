<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
return [
    'components' => [
        'multiLanguage' => [
            'class' => \skeeks\cms\multiLanguage\MultiLangComponent::class
        ],

        'request' => [
            'class'               => \skeeks\yii2\multiLanguage\MultiLangRequest::class
        ]
    ]
];