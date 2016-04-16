<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';

    protected $dateFormat = 'U';

    public function getRoleList()
    {
        $list = $this->where('is_deleted', 0)
            ->get();
        return $list ? $list->toArray() : [];
    }
}
