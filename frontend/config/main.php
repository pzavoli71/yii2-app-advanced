<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'name'=>'Sport (frontend)',   
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => false,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
            'authTimeout' => 3600, // auth expire 4 hours
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            //'class' => 'yii\web\CacheSession',
            'name' => 'advanced-frontend',
            //'cookieParams' => [
            //    'lifetime' => '60',
            //],
            
            /*'cookieParams' => [
                'path' => '/',
                'domain' => 'localhost', // <<<--- check this 
                'secure' => true,
            ],*/
            //'timeout' => 3600, //session expire
            'useCookies' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        /*
        'formatter' => [
           'dateFormat' => 'd/m/Y',
           'datetimeFormat' => 'd/m/Y H:i', 
           'timeFormat' => 'H:i:s',

           'locale' => 'it-IT', //your language locale
           'defaultTimeZone' => 'Europe/Rome', // time zone
        ],  */ 
        'assetManager' => [
            'bundles' => [
                'kartik\form\ActiveFormAsset' => [
                    'bsDependencyEnabled' => false // do not load bootstrap assets for a specific asset bundle
                ],
            ],
        ]
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
    ],
    /*
    'modules'    => [
         'datecontrol' => [
             'class'          => 'kartik\datecontrol\Module',
              'displaySettings' => [
                     kartik\datecontrol\Module::FORMAT_DATE     => 'php:d/m/Y', 
                     kartik\datecontrol\Module::FORMAT_TIME     => 'hh:mm:ss a',
                     kartik\datecontrol\Module::FORMAT_DATETIME => 'php:d/m/Y H:i', 
              ],
              // format settings for saving each date attribute (PHP format example)
              'saveSettings'    => [
                     kartik\datecontrol\Module::FORMAT_DATE     => 'php:U', // saves as unix timestamp
                     kartik\datecontrol\Module::FORMAT_TIME     => 'php:H:i:s',
                     kartik\datecontrol\Module::FORMAT_DATETIME => 'php:Y-m-d H:i:s',
              ],
            // set your display timezone
            'displayTimezone' => 'Europe/Rome',
            // set your timezone for date saved to db
            'saveTimezone' => 'UTC',
            // automatically use kartik\widgets for each of the above formats
            'autoWidget' => true,             
            // default settings for each widget from kartik\widgets used when autoWidget is true
            'autoWidgetSettings' => [
                kartik\datecontrol\Module::FORMAT_DATE => ['type'=>2, 'pluginOptions'=>['autoclose'=>true]], // example
                kartik\datecontrol\Module::FORMAT_DATETIME => [], // setup if needed
                kartik\datecontrol\Module::FORMAT_TIME => [], // setup if needed
            ],
             'widgetSettings' => [
                 kartik\datecontrol\Module::FORMAT_DATE => [
                         'class' => 'yii\jui\DatePicker', // example
                        'options' => [
                            'dateFormat' => 'php:d-M-Y',
                            'options' => ['class'=>'form-control'],
                        ]
                 ],
                 kartik\datecontrol\Module::FORMAT_DATETIME => [
                         'class' => '\kartik\datetime\DateTimePicker', // example
                        'options' => [
                            'dateFormat' => 'php:d-M-Y',
                            'options' => ['class'=>'form-control'],
                        ]                 
                 ]
              ],
         ],    
     ],   */         
    'params' => $params,
];
