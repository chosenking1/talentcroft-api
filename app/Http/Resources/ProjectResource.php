<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request)
    {
        $parent = parent::toArray($request);
        return array_merge(
            $parent,
            [
                "collaborators" => $this->collaborators,
                "tickets" => $this->tickets,
                "file" => $this->file,
                "likes" => $this->likes,
                "dislikes" => $this->dislikes,
                "comments" => 0,
                "comment_count" => $this->comments->count(),
                "views" => 0,
            ]
        );
    }
}
