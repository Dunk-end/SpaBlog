<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;


class PostController extends Controller
{
    public function uploadFile(Request $req)
    {
        $img = null;

        if ($file = $req->file('file')) {
            /** @var $file UploadedFile */

            $imgName = $file->getClientOriginalName();
            if (Storage::disk('public')->missing($imgName)) {
                $file->storeAs('images', $imgName, 'public');
            }
        }

        return response()->json([
            'status' => 'Запись была добавлена!'
        ]);
    }

    public function showCategory()
    {
        return Category::get();
    }

//    public function getPostCat()
//    {
//        $post = Post::find(1);
//        foreach ($post->categories as $cat) {
//            $response = $cat;
//            dd($response);
//        }
//    }
}
