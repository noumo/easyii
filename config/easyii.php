<?php

Yii::setAlias('@uploads', dirname($basePath) . '/uploads');

return [
    'modules' => [
        'admin' => [
            'class' => 'yii\easyii\AdminModule',
        ],
        'markdown' => [
            'class' => 'kartik\markdown\Module',
        ]
    ],
    'components' => [
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'admin/<controller:\w+>/<action:[\w-]+>/<id:\d+>' => 'admin/<controller>/<action>',
                'admin/<module:\w+>/<controller:\w+>/<action:[\w-]+>/<id:\d+>' => 'admin/<module>/<controller>/<action>'
            ],
         ],
        'user' => [
            'identityClass' => 'yii\easyii\models\Admin',
            'enableAutoLogin' => true,
            'authTimeout' => 86400,
        ],
        'i18n' => [
            'translations' => [
                'easyii' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'sourceLanguage' => 'en-US',
                    'basePath' => '@easyii/messages',
                    'fileMap' => [
                        'easyii' => 'admin.php',
                    ]
                ]
            ],
        ],
        'formatter' => [
            'sizeFormatBase' => 1000
        ],
    ],
    'bootstrap' => ['admin']
];