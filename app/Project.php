<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Picture;

class Project extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'url',
        'date_start',
        'date_end',
    ];

    public function picture() {
        return $this->hasMany(Picture::class, 'project_id', 'id');
    }

    public function user()
    {
        return $this->belongsToMany(User::class, 'project_users')->withPivot('project_id', 'user_id', 'position');
    }

    public function tag()
    {
        return $this->belongsToMany(Tag::class, 'project_tags')->withPivot('project_id', 'tag_id');
    }
}
