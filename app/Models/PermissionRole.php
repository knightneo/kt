<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionRole extends Model
{
    protected $table = 'permission_roles';

    protected $dateFormat = 'U';

    public function getPermissionByRoleID($role_id)
    {
        $list = $this->where('role_id', $role_id)
            ->where('is_deleted', 0)
            ->with('permission')
            ->get();

        $list = $list ? $list->toArray() : [];

        foreach ($list as &$item) {
            if (!isset($item['permission'])) {
                unset($item);
                break;
            }

            $item['permission_name'] = $item['permission']['name'];
            $item['permission_id'] = $item['permission']['id'];
            unset($item['id']);
            unset($item['role_id']);
            unset($item['is_deleted']);
            unset($item['created_at']);
            unset($item['updated_at']);
            unset($item['permission']);
        }

        return $list;
    }

    public function setPermisionRole($role_id, $permission_roles)
    {
        $db = app('db');
        try {
            $db->beginTransaction();
            $this->where('role_id', $role_id)
                ->where('is_deleted', 0)
                ->update(['is_deleted' => 1]);
            $this->create($permission_roles);
            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
            return false;
        }
        return true;
    }

    public function role()
    {
        return $this->belongsTo('App\Models\Role');
    }

    public function permission()
    {
        return $this->belongsTo('App\Models\Permission');
    }
}
