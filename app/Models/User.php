<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'users';

    protected $dateFormat = 'U';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function setUserRole($user_id, $role_id)
    {
        try {
            $this->where('id', $user_id)
                ->update(['role_id' => $role_id]);
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    public function getUserByPageSize($page, $size = 4)
    {
        $offset = ($page -1) * $size;

        $query= $this->select('id', 'name', 'email', 'role_id')
            ->with('role')
            ->orderBy('id', 'desc');

        $count = $query->count();
        $list = $query->skip($offset)->take($size)->get();

        $list = $list ? $list->toArray() : [];
        foreach ($list as &$item) {
            $item['role_name'] = $item['role']['name'];
            unset($item['role']);
        }

        $result = [];
        $result['list'] = $list;
        $result['number'] = ceil($count/$size);

        return $result;
    }

    public function role()
    {
        return $this->belongsTo('App\Models\Role');
    }
}
