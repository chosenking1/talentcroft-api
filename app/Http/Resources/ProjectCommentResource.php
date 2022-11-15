<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class ProjectCommentResource extends JsonResource
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
        $collection = $this->collection;
        $model = $this->resource;
        return [
            "id" => $model->id,
            "comment" => $model->comment,
            "created_at" => $model->created_at,
            "user" => [
                'name' => $model->user->name,
                'avatar' => $model->user->avatar,
                'id' => $model->user->id,
            ],
            "likes" => $model->likes,
            "dislikes" => $model->dislikes,
            "isReply" => $model->parent_id !== null,
            "sentiment" => $model->sentiment,
            "reply_count" => $model->replies->count(),
        ];
    }
}
