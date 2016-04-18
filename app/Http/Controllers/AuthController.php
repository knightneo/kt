<?php

namespace App\Http\Controllers;

use JWTAuth;
use Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\User;
use App\Models\Role;
use App\Models\PermissionRole;

class AuthController extends Controller
{
    public function isEmailAvailable()
    {
        $userDao = new User;
        if ($userDao->isEmailExist($this->request['email'])) {
            return ['result' => false];
        }
        return ['result' => true];
    }

    public function createUser()
    {
        $params = [];
        $params['name'] = $this->request['name'];
        $params['email'] = $this->request['email'];
        $params['password'] = Hash::make($this->request['password']);
        $userDao = new User;
        if ($userDao->isEmailExist($params['email'])) {
            return [
                'result' => false,
                'error_message' => 'this email has already signed up',
            ];
        }
        if ($userDao->createUser($params)) {
            return ['result' => true];
        }
        return ['result' => false];
    }

    public function authenticate()
    {
        // grab credentials from the request
        $credentials = $this->request->only('email', 'password');

        try {
            // attempt to verify the credentials and create a token for user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        return response()->json(compact('token'));
    }

    public function getAuthenticatedUser()
    {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_note_found'], 404);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }

        // the token is valid and we have found the user via the sub claim
        //return response()->json(compact('user'));

        $user = compact('user')['user']->toArray();

        $roleDao = new Role;
        $role = $roleDao->where('id', $user['role_id'])->first();
        $role = $role ? $role->toArray() : [];
        unset($role['is_deleted']);
        unset($role['created_at']);
        unset($role['updated_at']);

        $permissionRoleDao = new PermissionRole;
        $permission_list = $permissionRoleDao->getPermissionByRoleID($user['role_id']);

        $result = [
            'user' => $user,
            'role' => $role,
            'permission' => $permission_list,
        ];

        return $result;
    }
}
