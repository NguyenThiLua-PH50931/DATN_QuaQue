<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\admin\BlogComment;

class Blog extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'blogs';

    protected $dates = ['deleted_at'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    protected $fillable = [
        'title',
        'slug',
        'content',
        'thumbnail',
        'start_date',
        'end_date',
    ];

    public function comments()
    {
        return $this->hasMany(BlogComment::class, 'blog_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($blog) {
            if ($blog->isForceDeleting()) {
                $blog->comments()->forceDelete();
            } else {
                $blog->comments()->delete();
            }
        });

        static::restoring(function ($blog) {
            BlogComment::onlyTrashed()
                ->where('blog_id', $blog->id)
                ->restore();
        });        
    }
}
