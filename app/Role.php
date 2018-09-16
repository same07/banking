<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * @var string
     */
    protected $table = 'roles';
    /**
     * @var array
     */
    protected $fillable = [
        'slug',
        'name'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'updated_at',
        'created_at',
        'deleted_at',
        'pivot'
    ];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this
            ->belongsToMany(User::class, 'role_users', 'role_id', 'user_id')
            ->withTimestamps();
    }
}
