<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Jenssegers\Mongodb\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    protected $collection = 'user_collection';
    protected $connection = 'mongodb';

    use Notifiable;

    public function roles()
    {
        return $this->embedsMany(Role::class);
    }

    public function authorizeRoles($roles){
        if($this->hasAnyRoles($roles)){
            return true;
        }
        abort(401, 'This action is unauthorized.');
    }

    public function hasAnyRoles($roles){
        if(is_array($roles)){
            foreach($roles as $role) {
                if($this->hasRole($role)){
                    return true;
                }
            }
        }else{
            if($this->hasRole($roles)){
                return true;
            }
        }
        return false;
    }

    public function hasRole($role_required){
        foreach($this->roles() as $role) {
            if($role->name == $role_required){
                return true;
            }
        }
        return false;
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'roles',
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
}
