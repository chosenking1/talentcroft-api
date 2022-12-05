<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function getAllPost(){
        // $post = Post::getpost();
        $post = Post::latest()->get();
        return $this->respondWithSuccess(['data' => ['message' => 'All post made by users', 'post' => $post]], 201);
    }

    public function show($id)
    {
        $post = Post::findOrFail($id);
        return $this->respondWithSuccess(['data' => ['post' => $post]], 201);
    }

    public function createPost(PostRequest $request): JsonResponse
    {
        

        $data = $request->validated();
        // create Post
        $post = getUser()->posts()->create($data);

        // url file
        $user_id= $request->user_id;
        $file = $request->url;
        $post_id = $post->id;
        $url = $file->storeAs($user_id,  "$post_id.{$file->extension()}" , 'posts'); 
        return $this->respondWithSuccess(['posts' => ["id" => $post_id, "user_id" => $user_id, "description" => $post->description, "url" => url($url), "message" => "Successfully created " . $post->id]], 201);
    }

    final public function destroy($id)
    {
        $post = Post::destroy($id);
        return $this->respondWithSuccess(['data' => ['message' => 'post'.'_'.$id.'_'.'deleted' ,'post' => $post]], 201);
    }
}
