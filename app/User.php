<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Project;

class User extends Authenticatable
{
    use Notifiable, HasRoles, HasApiTokens, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'identification',
        'name',
        'lastname',
        'email',
        'email_verified_at',
        'password',
        'phone',
        'is_employed',
        'position',
        'address',
        'birthdate',
        'date_start',
        'photo',
        'description',
        'slack_url',
        'linkedin_url',
        'facebook_url',
        'twitter_url',
        'github_url',
        'instagram_url',
        'status',
        'online',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsToMany(Project::class, 'project_users')->withPivot('project_id', 'user_id', 'position');
    }
}
