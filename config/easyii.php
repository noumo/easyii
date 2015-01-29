<?php

return [
    'modules' => [
        'admin' => [
            'class' => 'yii\easyii\AdminModule',
        ],
    ],
    'components' => [
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<module:\w+>/<controller:\w+>/<action:[\w-]+>/<id:\d+>' => '<module>/<controller>/<action>',
                '<module:\w+>/<submodule:\w+>/<controller:\w+>/<action:[\w-]+>/<id:\d+>' => '<module>/<submodule>/<controller>/<action>'
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