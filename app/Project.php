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
}
