<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Repository models registration
    |--------------------------------------------------------------------------
    |
    | Here can you register your models to be used with the repository package
    |
    */

    'models' => [
        // 'MyModel'
    ],

    /*
    |--------------------------------------------------------------------------
    | Settings of target folders paths
    |--------------------------------------------------------------------------
    |
    | Paths below must be under Laravels `/app` folder.
    | Folders will be automatically created if not exists.
    |
    */

    'folders' => [
        'models' => 'Models',
        'contracts' => 'Contracts',
        'repositories' => 'Repositories',
        'events' => 'Events',
        'listeners' => 'Listeners',
    ],

];
