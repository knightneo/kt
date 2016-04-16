<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\PermissionRole;

class AdminController extends Controller
{
    public function getRoleList()
    {
        $roleDao = new Role;
        $list = $roleDao->getRoleList();
        return $list;
    }

    public function getPermissionList()
    {
        $permissionDao = new Permission;
        $list = $permissionDao->getPermissionList();
        return $list;
    }

    public function setRole($user_id)
    {
        $role_id = $this->request['role_id'];
        $userDao = new User;
        $result = $userDao->setUserRole($user_id, $role_id);
        return ['result' => $result];
    }

    public function setPermission($role_id)
    {
        $permission_roles = $this->request['permission_roles'];
        $params = [];
        foreach ($permission_roles as $permission_role) {
            $item = [];
            $item['role_id'] = $role_id;
            $item['permission'] = $permission_role;
        }
        $permissionRoleDao = new PermissionRole;
        if ($permissionRoleDao->setPermisionRole($role_id, $params)) {
            return ['result' => true];
        }
        return ['result' => false];
    }


}
