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
            'absoluteAuthTimeout' => 86400,
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
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'js' => [YII_DEBUG ? 'jquery.js' : 'jquery.min.js'],
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [YII_DEBUG ? 'css/bootstrap.css' : 'css/bootstrap.min.css'],
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'js' => [YII_DEBUG ? 'js/bootstrap.js' : 'js/bootstrap.min.js'],
                ],
            ],
        ],
    ],
    'bootstrap' => ['admin']
];