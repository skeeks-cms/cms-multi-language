<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
return [
    'bootstrap' => ['multiLanguage'],

    'components' => [
        'urlManager' => [
            'class' => \skeeks\yii2\multiLanguage\MultiLangUrlManager::class,
        ],

        'multiLanguage' => [
            'class' => \skeeks\cms\multiLanguage\MultiLangComponent::class,
        ],

        'request' => [
            'class' => \skeeks\yii2\multiLanguage\MultiLangRequest::class,
        ],
    ],
];