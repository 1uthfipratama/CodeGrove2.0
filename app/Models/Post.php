<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'programming_language_id',
        'post_id',
        'post_content',
        'status',
        'is_solution'
    ];

    protected $casts = [
        'is_solution' => 'boolean',
    ];

    public function replies()
    {
        return $this->hasMany(Post::class);
    }

    public function originalPost()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function programmingLanguage()
    {
        return $this->belongsTo(ProgrammingLanguage::class);
    }

    public function userLike()
    {
        return $this->hasMany(UserLike::class);
    }
}
