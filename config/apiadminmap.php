<?php

return [
    'V1' => [
        'auth_admin_login' => 'AuthController@login',
        'auth_admin_logout' => 'AuthController@logout',
        'auth_admin_refreh' => 'AuthController@refresh',
        'auth_admin_me' => 'AuthController@me',

        'post_list' => 'PostController@index',
        'post_show' => 'PostController@show',
        'post_create' => 'PostController@create',
        'post_edit' => 'PostController@edit',
        'post_delete' => 'PostController@delete',
        'post_restore' => 'PostController@restore',


        'novel_list' => 'NovelController@index',
    ]
];
