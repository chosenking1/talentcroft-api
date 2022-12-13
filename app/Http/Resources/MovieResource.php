<?php

namespace App\Http\Resources;

use App\Enums\TransactionStatus;
use App\Models\Movie;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use JsonSerializable;

class MovieResource extends JsonResource
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
        //converted to boolean
        $thumbnail = textToImage('No Thumbnail');
        $previewLink = "";
        if (isset($this->file)) {
            $thumbnail = !$this->file->thumbnails ? textToImage('No Thumbnail') : $this->file->thumbnails[0];
            $previewLink = $this->file->url ?? "";
        }

        $isAuth = auth('api')->check();

        $extraData = [
            "thumbnail" => $thumbnail,
            // "purchased_by_me" => false,
            "views" => $this->views ? $this->views->count() : 0,
            "video" => $previewLink,
            "episodes_count" => $this->episodes->count(),
            "episodes" => $this->episodes
        ];

        if ($isAuth) {
            // Other stuffs
//            $extraData['key'] = some data
            // $auth_id = auth('api')->id();

            // $isOwner = (boolean)$this->user_id === $auth_id;
            $isAdmin = (boolean)auth('api')->user()->isAdmin;
            // $hasPurchased = (boolean)auth('api')->user()->payments()->where('id', $this->id)
            //     ->where('transactable_type', Project::class)->where('status', TransactionStatus::SUCCESS())->count();

            // if ($hasPurchased) {
            //     $extraData['video'] = isset($this->file) ? $this->file->url : "";
            //     $extraData['purchased_by_me'] = true;
            //     $extraData['episodes'] = $this->episodes;
            // }
            // if ($isOwner) {
            //     $extraData['video'] = isset($this->file) ? $this->file->url : "";
            //     $extraData['collaborators'] = $this->collaborators;
            //     $extraData['tickets'] = $this->tickets;
            //     $extraData['file'] = $this->file;
            //     $extraData['episodes'] = $this->episodes;
            // }
            if ($isAdmin) {

            }
        }
        return array_merge($parent, $extraData);
    }
}
