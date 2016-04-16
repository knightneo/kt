<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';

    protected $dateFormat = 'U';

    public function getPermissionList()
    {
        $list = $this->where('is_deleted', 0)
            ->get();
        return $list ? $list->toArray() : [];
    }
}
