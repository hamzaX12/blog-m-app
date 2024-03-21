<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    // the $this refrence on the model passed in the param
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'comment_date' => $this->created_at,
            'user' => new CommentUserResource($this->whenLoaded('user')),
            // 'user' => new CommentUserResource($this->user) in this exemple i have only one object if i have more or a collection 
            //  i will use this notation
            // 'user' => CommentUserResource::collection($this->user),
        ];
    }
}
