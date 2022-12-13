<?php

namespace App\Http\Controllers;

use App\Models\MovieList;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MovieListController extends Controller
{
    public function updateMovieList(Request $request, $id){
        $movielist = MovieList::findorfail($id)->update([
            'title' =>$request->title,
            'description' =>$request->description,
            'type'=>$request->type,
            'genre'=>$request->genre,
            'content' =>$request->content,
            'created_at' =>Carbon::now(),
        ]);

        return $this->respondWithSuccess(['data' => ['message' => 'Movie list updated successfully', 'movielist' =>   $movielist]], 201);
    }

    public function getAllMovieList(){
        $movielist = MovieList::latest()->get();
        return $this->respondWithSuccess(['data' => ['message' => 'All Movielist ', 'movielist' =>   $movielist]], 201);

    }

    public function deleteMovieList($id){
       $deletemovielist= MovieList::findorfail($id)->delete();
        return $this->respondWithSuccess(['data' => ['message' => 'Movielist deleted successfully ', 'deletemovielist' =>   $deletemovielist]], 201);
    }
}
