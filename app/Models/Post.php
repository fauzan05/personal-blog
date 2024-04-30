<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    protected $table = "posts";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;

    protected $dates = ['dob'];
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'content',
        'location',
        'menu_id',
        'slug',
        'show_image'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function tags():BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tag');
    } 

    public function comments(): HasMany
    { 
        return $this->hasMany(Comment::class, 'post_id', 'id');
    }

    public function media(): HasMany
    {
        return $this->hasMany(Media::class, 'post_id', 'id');
    }

    public function menu(): BelongsTo
    {
       return $this->belongsTo(Menu::class, 'menu_id', 'id');
    }

    public static function search($searchTitle)
    {
        return Post::where('title', 'like', '%' . $searchTitle . '%')->get();
    }
}
