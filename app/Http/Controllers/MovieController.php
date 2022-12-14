<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovieRequest;
use App\Http\Resources\MovieResource;
use App\Models\Movie;
use App\Models\MovieFile;
use App\Repositories\MovieRepository;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
    private $movieRepository;

    public function __construct(MovieRepository $projects)
    {
        $this->movieRepository = $projects;
//        $this->middleware('cache.no');
    }

    final public function create(MovieRequest $request): JsonResponse
    {
        $data = $request->validated();
        // create Project
        $movie = getUser()->movies()->create($data);
        return $this->respondWithSuccess(['data' => [
            'movie' => $this->movieRepository->parse($movie),
            "message" => "Successfully created " . $movie->name
        ]], 201);
    }


    final public function index(Request $request)
    {
        $movies = Movie::latest()->searchable();
        $data = MovieResource::collection($movies->items());
        return $this->respondWithSuccess(array_merge($movies->toArray(), ['data' => $data]));
    }

    final public function update(Request $request, Movie $movie)
    {
        $movie->update($request->all());
        return $this->respondWithSuccess(['data' => [
            'movie' => $this->movieRepository->parse($movie),
            "message" => "Successfully updated " . $movie->name]], 201);
    }

    final public function destroy($id)
    {
        $movie = Movie::destroy($id);
        return $this->respondWithSuccess('Deleted Successfully', 201);
    }
    
}
