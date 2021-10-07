<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostService
{
    public static function create(array $data)
    {
        $imgName = data_get($data,'file');
        $img = 'public/storage/images/' . $imgName;

        $post = Post::create([
            'name'      => data_get($data, 'name'),
            'desc'      => data_get($data, 'desc'),
            'img'       => $img,
            'user_id'   => data_get($data, 'user_id')
        ]);

        $post->categories()->attach(data_get($data, 'categories'));

        return true;
    }
}
