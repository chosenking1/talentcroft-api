<?php

namespace App\Repositories;

use App\Http\Resources\MovieResource;
use App\Models\Movie;
use App\Models\User;
use App\Repositories\BaseRepository;
use JetBrains\PhpStorm\Pure;

class MovieRepository extends BaseRepository
{

    /**
     * ProjectRepository constructor.
     * @param Movie $model
     */
    #[Pure] public function __construct(Movie $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }


    public function featuredMovies()
    {
        $movies = $this->model->with('featured')->whereHas('featured', function ($query) {
            $query->active();
        })->searchable();

        $data = MovieResource::collection($movies->items());
        return array_merge($movies->toArray(), ['data' => $data]);
    }


    public function parse(Movie $movie)
    {
        return new MovieResource($movie);
    }
}
