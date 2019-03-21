<?php

//图片上传  参考https://laravelacademy.org/post/2333.html
//需要建立软连接 php artisan storage:link

/**
 *
 * @author: wansq
 * @since: 1.0
 * Date: 2019/2/21
 * Time: 15:29
 */
return [
    'title' => 'My Blog',
    'posts_per_page' => 5,
    'uploads' => [
        'storage' => 'picture',
        'webpath' => '/uploads',
    ],
    'freePicturesId' => 50
];