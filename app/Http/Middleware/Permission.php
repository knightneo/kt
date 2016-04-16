<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Models\PermissionRole;

class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission_str)
    {
        $user = Auth::user();
        $required_permissions = explode('|', $permission_str);

        if ($user) {//user
            $role_id = $user->role_id;
        } else {//guest(reader is allowed)
            $role_id = 1;
        }

        $permissionRoleDao = new PermissionRole;
        $p_list = $permissionRoleDao->getPermissionByRoleID($role_id);
        $user_permissions= [];
        foreach($p_list as $p) {
            $user_permissions[] = $p['permission_name'];
        }

        if (!$this->checkPermission($required_permissions, $user_permissions)) {
            return response()->json(['no_permission'], 403);
        }

        return $next($request);
    }

    public function checkPermission($required_permissions, $user_permissions)
    {
        $counter = 0;
        foreach($required_permissions as $r) {
            if (in_array($r, $user_permissions)) {
                $counter ++;
                continue;
            }
        }
        return ($counter == count($required_permissions));
    }
}
