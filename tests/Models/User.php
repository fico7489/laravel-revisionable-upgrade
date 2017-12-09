<?php

namespace Fico7489\Laravel\RevisionableUpgrade\Tests\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends BaseModel implements AuthenticatableContract
{
    use Authenticatable, SoftDeletes;
    
    protected $table = 'users';

    protected $fillable = ['name', 'email'];

    public function roles()
    {
        return $this->belongsToMany(Role::class)
            ->withPivot(['value']);
    }
}
