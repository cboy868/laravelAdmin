<?php

return [
    'V1' => [
        'auth_user_login' => 'AuthController@login',
        'auth_user_logout' => 'AuthController@logout',
        'auth_user_refreh' => 'AuthController@refresh',
        'auth_user_me' => 'AuthController@me',

        'post_list' => 'PostController@index',
        'post_show' => 'PostController@show',
    ]
];
