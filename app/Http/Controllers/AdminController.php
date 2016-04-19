<?php

namespace App\Http\Controllers;

use Auth;
use Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\PermissionRole;

class AdminController extends Controller
{
    public function adminResetPassword()
    {
        $userDao = new User;
        $user = $userDao->getUserByEmail($this->request['email']);
        if ($user) {
            return ['result' => $userDao->resetPassword($user['id'], Hash::make($this->request['password']))];
        }
        return ['result' => false];
    }

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
        if (Auth::user()->id == $user_id) {
            return ['result' => false, 'error_message' => 'cannot set your own role'];
        }
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

    public function getUserList($page)
    {
        $userDao = new User;
        $result = $userDao->getUserByPageSize($page);
        return $result;
    }


}
