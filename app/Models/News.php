<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class News extends Model
{
    use HasFactory;

    protected $primaryKey = 'news_id';
    protected $fillable = [
        'title',
        'slug',
        'images',
        'content',
        'author_id',
        'news_category_id',
        'published_date',
        'updated_date',
        'status'
    ];

    public function category()
    {
        return $this->belongsTo(NewsCategory::class, 'news_category_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function getPublishedDateAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y');
    }
}
