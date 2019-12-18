<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $fillable = [
        'user_id_emitter',
        'action',
        'project_id',
        'user_id_receiver',
        'client_id',
    ];

    public function userEmitter()
    {
        return $this->belongsTo(User::class, 'user_id_emitter', 'id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function userReceiver()
    {
        return $this->belongsTo(User::class, 'user_id_receiver', 'id');
    }

    public function clients()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }
}
