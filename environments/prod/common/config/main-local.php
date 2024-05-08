<?php

return [
    'components' => [
        'db' => [
            'class' => \yii\db\Connection::class,
            'dsn' => 'mysql:host=localhost;dbname=yii2advanced',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
        'session' => [
            'class' => 'yii\web\DbSession',
            'writeCallback' => function ($session) {
                $user_browser = null;
                if (!Yii::$app->user->isGuest) {
                    $browser = new \Wolfcast\BrowserDetection();
                    $user_browser = "{$browser->getName()}-{$browser->getPlatform()}" . ($browser->is64bitPlatform() ? "(x64)" : "(x86)") . ($browser->isMobile() ? "-Mobile" : "-Desktop");
                }
                return [
                    'user_id' => Yii::$app->user->id,
                    'last_write' => new \yii\db\Expression('NOW()'),
                    'browser_platform' => $user_browser,
                    'ipaddress' => Yii::$app->request->userIP
                ];
            }
            // 'db' => 'mydb',  // the application component ID of the DB connection. Defaults to 'db'.
            // 'sessionTable' => 'my_session', // session table name. Defaults to 'session'.
        ],    
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@common/mail',
        ],
    ],
];
