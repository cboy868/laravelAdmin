<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class PostResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request):array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'posted_at' => $this->posted_at->toDateTimeString(),
            'author_id' => $this->author_id,
            'likes_count' => $this->likes_count ?? $this->likes()->count(),
            'author' => $this->author
        ];
    }
}
