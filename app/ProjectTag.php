<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectTag extends Model
{
    protected $fillable = [
        'project_id',
        'tag_id',
    ];
}
