<?php

namespace App\Http\Controllers;

use App\Models\Movie_List;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MovieListController extends Controller
{
    public function updatemovielist(Request $request, $id){
        $movielist = Movie_List::findorfail($id)->update([
            'title' =>$request->title,
            'description' =>$request->description,
            'type'=>$request->type,
            'genre'=>$request->genre,
            'content' =>$request->content,
            'created_at' =>Carbon::now(),
        ]);

        return $this->respondWithSuccess(['data' => ['message' => 'Movie list updated successfully', 'movielist' =>   $movielist]], 201);
    }

    public function getallmovielist(){
        $movielist = Movie_List::latest()->get();
        return $this->respondWithSuccess(['data' => ['message' => 'All Movielist ', 'movielist' =>   $movielist]], 201);

    }

    public function deletemovielist($id){
       $deletemovielist= Movie_List::findorfail($id)->delete();
        return $this->respondWithSuccess(['data' => ['message' => 'Movielist deleted successfully ', 'deletemovielist' =>   $deletemovielist]], 201);
    }
}
