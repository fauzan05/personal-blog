<?php

namespace App\Http\Resources;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $comments = Comment::where('post_id', $this->id)->get();
        return [
            'id' => $this->id,
            'author' => $this->user,
            'menu_id' => $this->menu_id,
            'category' => $this->category,
            'title' => $this->title,
            'content' => $this->content,
            'location' => $this->location,
            'tag' => $this->tags,
            'media' => $this->media,
            'menu' => $this->menu,
            'created_at' => $this->created_at->format('l, j F Y H:i:s'),
            'updated_at' => $this->updated_at->format('l, j F Y H:i:s'),
            'dibuat' => $this->created_at->format('F j, Y'),
            'comments' => $comments,
            'slug' => $this->slug,
            'month_year' => $this->created_at->format('F Y')
        ];
    }
}
