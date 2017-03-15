return array(
    'db' => array(
        'adapters' => array(
            'master' => array(
                'driver'   => 'pdo_mysql',
                'dsn'      => 'mysql:dbname=suisuichat;host=127.0.0.1;charset=utf8;port=3306',
                'username' => 'enter your db user name',
                'password' => 'enter your db password',
            ),
            'slave01' => array(
                'driver'   => 'pdo_mysql',
                'dsn'      => 'mysql:dbname=suisuichat;host=127.0.0.1;charset=utf8;port=3307',
                'username' => 'enter your db user name',
                'password' => 'enter your db password',
            ),
            'slave02' => array(
                'driver'   => 'pdo_mysql',
                'dsn'      => 'mysql:dbname=suisuichat;host=127.0.0.1;charset=utf8;port=3307',
                'username' => 'enter your db user name',
                'password' => 'enter your db password',
            ),
            'slave03' => array(
                'driver'   => 'pdo_mysql',
                'dsn'      => 'mysql:dbname=suisuichat;host=127.0.0.1;charset=utf8;port=3308',
                'username' => 'enter your db user name',
                'password' => 'enter your db password',
            ),
            'slave04' => array(
                'driver'   => 'pdo_mysql',
                'dsn'      => 'mysql:dbname=suisuichat;host=127.0.0.1;charset=utf8;port=3308',
                'username' => 'enter your db user name',
                'password' => 'enter your db password',
            ),
        )
    ),
);