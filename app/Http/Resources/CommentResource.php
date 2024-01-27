<?php

namespace App\Http\Resources;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $parent = Comment::where('id', $this->parent_id)->get();
        return [
            'id' => $this->id,
            'post' => $this->posts,
            'user' => new GuestResource($this->users),
            'parent_id' => $parent,
            'content' => $this->content
        ];
    }
}
