<?php

use webvimark\behaviors\multilanguage\MultiLanguageUrlManager;

return [
    'modules' => [
        'admin' => [
            'class' => 'yii\easyii\AdminModule',
        ],
    ],
    'components' => [
        'urlManager' => [
            'class' => MultiLanguageUrlManager::className(),
            'enablePrettyUrl' => true,
            'showScriptName'=>false,
            'rules'=>[
                'admin/<controller:\w+>/<action:[\w-]+>/<id:\d+>' => 'admin/<controller>/<action>',
                'admin/<module:\w+>/<controller:\w+>/<action:[\w-]+>/<id:\d+>' => 'admin/<module>/<controller>/<action>',

                '<_c:[\w \-]+>/<id:\d+>'=>'<_c>/view',
                '<_c:[\w \-]+>/<_a:[\w \-]+>/<id:\d+>'=>'<_c>/<_a>',
                '<_c:[\w \-]+>/<_a:[\w \-]+>'=>'<_c>/<_a>',

                '<_m:[\w \-]+>/<_c:[\w \-]+>/<_a:[\w \-]+>'=>'<_m>/<_c>/<_a>',
                '<_m:[\w \-]+>/<_c:[\w \-]+>/<_a:[\w \-]+>/<id:\d+>'=>'<_m>/<_c>/<_a>',

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
    'bootstrap' => ['admin'],
    'params' => [
        'mlConfig'=>[
            'default_language'=>'en',
            'languages'=>[
                'en'=>'Eng',
                'ru'=>'Rus',
            ],
        ],
    ]
];