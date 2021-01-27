<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Post extends Model
{

    protected $fillable = ['title', 'slug', 'body', 'category_id', 'thumbnail'];
    protected $with = ['author', 'category', 'tags'];

    // pake $guarded ketika form kita sendiri yg input bukan user
    // protected $guarded = [];

    public function category()
    {
        // belongsTo itu untuk relasi one to many database berdasarkan foreign key pada table category
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        // relasi many to many dengan table tag
        return $this->BelongsToMany(Tag::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getTakeImgAttribute()   
    {
        return "/storage/" . $this->thumbnail;
    }
}
