<?php

namespace Tests\Feature;

use Tests\TestCase;

class PostTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndexTest()
    {
        $response = $this->get('/api/admin/posts');
        $response->assertStatus(200);
    }

    /**
     * 暂时不能用
     */
    public function testCreateTest()
    {
        $response = $this->post('/api/admin/posts', [
            'appid' => 1,
            'version' => 1,
            'params' => json_encode([
                'title' => '新标题',
                'content' => '新内容'
            ])
        ]);

        dd($response->content());

        $response->assertStatus(200);
    }
}
