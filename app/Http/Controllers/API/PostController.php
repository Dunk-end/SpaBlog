<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\PostRequest;
use App\Http\Controllers\Controller;
use App\Services\PostService;
use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $posts = Post::latest()->paginate(1000);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(PostRequest $request)
    {
        if (PostService::create($request->all())) {

            return response()->json([
                'status' => 'Запись была добавлена!'
            ]);
        }

        return response()->json([
            'status' => '422'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $post = Post::find($id);

        $post -> name = $request->get('name');
        $post -> desc = $request->get('desc');

        if ($post -> save()) {

            return response()->json([
                'status' => 'Запись была изменена!'
            ]);
        }

        return response()->json([
            'status' => '422'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        if (Post::find($id)->delete()) {

            return response()->json([
                'status' => 'Запись была удалена!'
            ]);
        }

        return response()->json([
            'status' => '422'
        ]);
    }
}
