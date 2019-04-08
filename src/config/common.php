<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
return [
    'components' => [

        'i18n' => [
            'translations' => [
                'skeeks/multi-lang/langs' => [
                    'class'    => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@skeeks/cms/multiLanguage/messages',
                    'fileMap'  => [
                        'skeeks/multi-lang/langs' => 'langs.php',
                    ],
                ],
            ],
        ],

        'urlManager' => [
            'class' => \skeeks\yii2\multiLanguage\MultiLangUrlManager::class,
        ]
    ]
];