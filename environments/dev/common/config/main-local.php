<?php

return [
    'components' => [
        'db' => [
            //'class' => \yii\db\Connection::class,
            'class' => \common\config\db\NewConnection::class,
            //'dsn' => 'mysql:host=localhost;dbname=yii2advanced',
            'username' => 'root',
            'password' => 'xxxxxxx',
            'charset' => 'utf8',
            'dsn' => 'mysql:host=localhost;dbname=yourdatabase',    
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
            // send all mails to a file by default.
            'useFileTransport' => false,
            'transport' => [
                    'scheme' => 'smtps',
                    'host' => 'smtps.aruba.it',
                    'username' => 'name@yoursite.org',
                    'password' => 'xxxxxxxxx',
                    'port' => 465,
                    'dsn' => 'native://default',
            ],
            'transport' => [
                'dsn' => 'smtp://name@yoursite.org:xxxxxxxx@smtps.aruba.it:465',
            ],

            // You have to set
            //
            // 'useFileTransport' => false,
            //
            // and configure a transport for the mailer to send real emails.
            //
            // SMTP server example:
            //    'transport' => [
            //        'scheme' => 'smtps',
            //        'host' => '',
            //        'username' => '',
            //        'password' => '',
            //        'port' => 465,
            //        'dsn' => 'native://default',
            //    ],
            //
            // DSN example:
            //    'transport' => [
            //        'dsn' => 'smtp://user:pass@smtp.example.com:25',
            //    ],
            //
            // See: https://symfony.com/doc/current/mailer.html#using-built-in-transports
            // Or if you use a 3rd party service, see:
            // https://symfony.com/doc/current/mailer.html#using-a-3rd-party-transport
        ],
    ],
];
