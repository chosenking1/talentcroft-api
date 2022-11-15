<?php

namespace App\Repositories;

use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\User;
use App\Repositories\BaseRepository;
use JetBrains\PhpStorm\Pure;

class ProjectRepository extends BaseRepository
{

    /**
     * ProjectRepository constructor.
     * @param Project $model
     */
    #[Pure] public function __construct(Project $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }


    public function featuredProjects()
    {
        return $this->model->with('featured')->whereHas('featured', function ($query) {
            $query->active();
        })->searchable();
    }


    public function parse(Project $project)
    {
        return new ProjectResource($project);
    }
}
