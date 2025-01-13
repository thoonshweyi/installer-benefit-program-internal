<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'url' => env('DATABASE_URL'),
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ],

        'mysql' => [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'pgsql' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'pgsql2' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST_SECOND', '127.0.0.1'),
            'port' => env('DB_PORT_SECOND', '5432'),
            'database' => env('DB_DATABASE_SECOND', 'forge'),
            'username' => env('DB_USERNAME_SECOND', 'forge'),
            'password' => env('DB_PASSWORD_SECOND', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'pgsql_ho' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST_HO', '127.0.0.1'),
            'port' => env('DB_PORT_HO', '5432'),
            'database' => env('DB_DATABASE_HO', 'forge'),
            'username' => env('DB_USERNAME_HO', 'forge'),
            'password' => env('DB_PASSWORD_HO', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'pgsql_lanthit' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST_LANTHIT', '127.0.0.1'),
            'port' => env('DB_PORT_LANTHIT', '5432'),
            'database' => env('DB_DATABASE_LANTHIT', 'forge'),
            'username' => env('DB_USERNAME_LANTHIT', 'forge'),
            'password' => env('DB_PASSWORD_LANTHIT', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'pgsql_ayetharyar' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST_AYETHARYAR', '127.0.0.1'),
            'port' => env('DB_PORT_AYETHARYAR', '5432'),
            'database' => env('DB_DATABASE_AYETHARYAR', 'forge'),
            'username' => env('DB_USERNAME_AYETHARYAR', 'forge'),
            'password' => env('DB_PASSWORD_AYETHARYAR', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'pgsql_eastdagon' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST_EASTDAGON', '127.0.0.1'),
            'port' => env('DB_PORT_EASTDAGON', '5432'),
            'database' => env('DB_DATABASE_EASTDAGON', 'forge'),
            'username' => env('DB_USERNAME_EASTDAGON', 'forge'),
            'password' => env('DB_PASSWORD_EASTDAGON', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'pgsql_hlaingtharyar' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST_HLAINGTHARYAR', '127.0.0.1'),
            'port' => env('DB_PORT_HLAINGTHARYAR', '5432'),
            'database' => env('DB_DATABASE_HLAINGTHARYAR', 'forge'),
            'username' => env('DB_USERNAME_HLAINGTHARYAR', 'forge'),
            'password' => env('DB_PASSWORD_HLAINGTHARYAR', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'pgsql_mawlamyine' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST_MAWLAMYINE', '127.0.0.1'),
            'port' => env('DB_PORT_MAWLAMYINE', '5432'),
            'database' => env('DB_DATABASE_MAWLAMYINE', 'forge'),
            'username' => env('DB_USERNAME_MAWLAMYINE', 'forge'),
            'password' => env('DB_PASSWORD_MAWLAMYINE', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'pgsql_satsan' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST_SATSAN', '127.0.0.1'),
            'port' => env('DB_PORT_SATSAN', '5432'),
            'database' => env('DB_DATABASE_SATSAN', 'forge'),
            'username' => env('DB_USERNAME_SATSAN', 'forge'),
            'password' => env('DB_PASSWORD_SATSAN', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'pgsql_tampawady' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST_TAMPAWADY', '127.0.0.1'),
            'port' => env('DB_PORT_TAMPAWADY', '5432'),
            'database' => env('DB_DATABASE_TAMPAWADY', 'forge'),
            'username' => env('DB_USERNAME_TAMPAWADY', 'forge'),
            'password' => env('DB_PASSWORD_TAMPAWADY', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'pgsql_terminalm' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST_TERMINALM', '127.0.0.1'),
            'port' => env('DB_PORT_TERMINALM', '5432'),
            'database' => env('DB_DATABASE_TERMINALM', 'forge'),
            'username' => env('DB_USERNAME_TERMINALM', 'forge'),
            'password' => env('DB_PASSWORD_TERMINALM', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'pgsql_theikpan' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST_THEIKPAN', '127.0.0.1'),
            'port' => env('DB_PORT_THEIKPAN', '5432'),
            'database' => env('DB_DATABASE_THEIKPAN', 'forge'),
            'username' => env('DB_USERNAME_THEIKPAN', 'forge'),
            'password' => env('DB_PASSWORD_THEIKPAN', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'pgsql_southdagon' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST_SOUTHDAGON', '127.0.0.1'),
            'port' => env('DB_PORT_SOUTHDAGON', '5432'),
            'database' => env('DB_DATABASE_SOUTHDAGON', 'forge'),
            'username' => env('DB_USERNAME_SOUTHDAGON', 'forge'),
            'password' => env('DB_PASSWORD_SOUTHDAGON', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'pgsql_bago' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST_BAGO', '127.0.0.1'),
            'port' => env('DB_PORT_SOUTHDAGON', '5432'),
            'database' => env('DB_DATABASE_BAGO', 'forge'),
            'username' => env('DB_USERNAME_BAGO', 'forge'),
            'password' => env('DB_PASSWORD_BAGO', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],
        'pgsql_shwepyithar' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST_SHWEPYITHAR', '127.0.0.1'),
            'port' => env('DB_PORT_SHWEPYITHAR', '5432'),
            'database' => env('DB_DATABASE_SHWEPYITHAR', 'forge'),
            'username' => env('DB_USERNAME_SHWEPYITHAR', 'forge'),
            'password' => env('DB_PASSWORD_SHWEPYITHAR', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'pos101_pgsql' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('POS101_DB_HOST', '127.0.0.1'),
            'port' => env('POS101_DB_PORT', '5432'),
            'database' => env('POS101_DB_DATABASE', 'forge'),
            'username' => env('POS101_DB_USERNAME', 'forge'),
            'password' => env('POS101_DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'pos102_pgsql' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('POS102_DB_HOST', '127.0.0.1'),
            'port' => env('POS102_DB_PORT', '5432'),
            'database' => env('POS102_DB_DATABASE', 'forge'),
            'username' => env('POS102_DB_USERNAME', 'forge'),
            'password' => env('POS102_DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'pos103_pgsql' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('POS103_DB_HOST', '127.0.0.1'),
            'port' => env('POS103_DB_PORT', '5432'),
            'database' => env('POS103_DB_DATABASE', 'forge'),
            'username' => env('POS103_DB_USERNAME', 'forge'),
            'password' => env('POS103_DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'pos104_pgsql' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('POS104_DB_HOST', '127.0.0.1'),
            'port' => env('POS104_DB_PORT', '5432'),
            'database' => env('POS104_DB_DATABASE', 'forge'),
            'username' => env('POS104_DB_USERNAME', 'forge'),
            'password' => env('POS104_DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'pos105_pgsql' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('POS105_DB_HOST', '127.0.0.1'),
            'port' => env('POS105_DB_PORT', '5432'),
            'database' => env('POS105_DB_DATABASE', 'forge'),
            'username' => env('POS105_DB_USERNAME', 'forge'),
            'password' => env('POS105_DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'pos106_pgsql' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('POS106_DB_HOST', '127.0.0.1'),
            'port' => env('POS106_DB_PORT', '5432'),
            'database' => env('POS106_DB_DATABASE', 'forge'),
            'username' => env('POS106_DB_USERNAME', 'forge'),
            'password' => env('POS106_DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'pos107_pgsql' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('POS107_DB_HOST', '127.0.0.1'),
            'port' => env('POS107_DB_PORT', '5432'),
            'database' => env('POS107_DB_DATABASE', 'forge'),
            'username' => env('POS107_DB_USERNAME', 'forge'),
            'password' => env('POS107_DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'pos108_pgsql' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('POS108_DB_HOST', '127.0.0.1'),
            'port' => env('POS108_DB_PORT', '5432'),
            'database' => env('POS108_DB_DATABASE', 'forge'),
            'username' => env('POS108_DB_USERNAME', 'forge'),
            'password' => env('POS108_DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'pos_pgsql' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('POS_DB_HOST', '127.0.0.1'),
            'port' => env('POS_DB_PORT', '5432'),
            'database' => env('POS_DB_DATABASE', 'forge'),
            'username' => env('POS_DB_USERNAME', 'forge'),
            'password' => env('POS_DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'pos112_pgsql' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('POS112_DB_HOST', '127.0.0.1'),
            'port' => env('POS112_DB_PORT', '5432'),
            'database' => env('POS112_DB_DATABASE', 'forge'),
            'username' => env('POS112_DB_USERNAME', 'forge'),
            'password' => env('POS112_DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'pos113_pgsql' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('POS113_DB_HOST', '127.0.0.1'),
            'port' => env('POS113_DB_PORT', '5432'),
            'database' => env('POS113_DB_DATABASE', 'forge'),
            'username' => env('POS113_DB_USERNAME', 'forge'),
            'password' => env('POS113_DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],
        'pos110_pgsql' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('POS110_DB_HOST', '127.0.0.1'),
            'port' => env('POS110_DB_PORT', '5432'),
            'database' => env('POS110_DB_DATABASE', 'forge'),
            'username' => env('POS110_DB_USERNAME', 'forge'),
            'password' => env('POS110_DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],
        'pos114_pgsql' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('POS114_DB_HOST', '127.0.0.1'),
            'port' => env('POS114_DB_PORT', '5432'),
            'database' => env('POS114_DB_DATABASE', 'forge'),
            'username' => env('POS114_DB_USERNAME', 'forge'),
            'password' => env('POS114_DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'pro1208_pgsql' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('COM_POS_DB_HOST', '127.0.0.1'),
            'port' => env('COM_POS_DB_PORT', '5432'),
            'database' => env('COM_POS_DB_DATABASE', 'forge'),
            'username' => env('COM_POS_DB_USERNAME', 'forge'),
            'password' => env('COM_POS_DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'sqlsrv' => [
            'driver' => 'sqlsrv',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '1433'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
        ],


        'centralpgsql' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('CEN_DB_HOST', '127.0.0.1'),
            'port' => env('CEN_PORT', '5432'),
            'database' => env('CEN_DATABASE', 'forge'),
            'username' => env('CEN_USERNAME', 'forge'),
            'password' => env('CEN_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'cloudpgsql' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('CLOUD_DB_HOST', '127.0.0.1'),
            'port' => env('CLOUD_PORT', '5432'),
            'database' => env('CLOUD_DATABASE', 'forge'),
            'username' => env('CLOUD_USERNAME', 'forge'),
            'password' => env('CLOUD_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer body of commands than a typical key-value system
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'client' => env('REDIS_CLIENT', 'phpredis'),

        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_database_'),
        ],

        'default' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
        ],

        'cache' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_CACHE_DB', '1'),
        ],

    ],

];
