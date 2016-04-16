<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminTest extends TestCase
{
    public function testAdmin()
    {
        $roles = [
            ['id' => 1, 'name' => 'reader', 'is_deleted' => 0 ],
            ['id' => 2, 'name' => 'writer', 'is_deleted' => 0 ],
            ['id' => 3, 'name' => 'admin', 'is_deleted' => 0 ],
        ];

        foreach ($roles as $r) {
            factory(App\Models\Role::class)->create($r);
        }

        $permissions = [
            ['id' => 1, 'name' => 'read', 'is_deleted' => 0],
            ['id' => 2, 'name' => 'write', 'is_deleted' => 0],
            ['id' => 3, 'name' => 'role', 'is_deleted' => 0],
            ['id' => 4, 'name' => 'permission', 'is_deleted' => 0],
        ];

        foreach ($permissions as $p) {
            factory(App\Models\Permission::class)->create($p);
        }

        $permission_roles = [
            ['id' => 1, 'role_id' => 1, 'permission_id' =>1, 'is_deleted' =>0],
            ['id' => 2, 'role_id' => 2, 'permission_id' =>1, 'is_deleted' =>0],
            ['id' => 3, 'role_id' => 2, 'permission_id' =>2, 'is_deleted' =>0],
            ['id' => 4, 'role_id' => 3, 'permission_id' =>1, 'is_deleted' =>0],
            ['id' => 5, 'role_id' => 3, 'permission_id' =>2, 'is_deleted' =>0],
            ['id' => 6, 'role_id' => 3, 'permission_id' =>3, 'is_deleted' =>0],
            ['id' => 7, 'role_id' => 3, 'permission_id' =>4, 'is_deleted' =>0],
        ];

        foreach ($permission_roles as $p) {
            factory(App\Models\PermissionRole::class)->create($p);
        }

        $admin = factory(App\Models\User::class)->create([
            'name' => 'admin',
            'email' => 'admin@email.com',
            'password' => Hash::make('123456'),
            'role_id' => 3,
        ]);

        $user = factory(App\Models\User::class)->create([
            'name' => 'user',
            'email' => 'user@email.com',
            'password' => Hash::make('123456'),
            'role_id' => 1,
        ]);

        $data = ['email'=>'admin@email.com', 'password' => '123456'];
        $response = $this->call('POST', 'signin', $data);
        $this->assertEquals(200, $response->status());
        $response = json_decode($response->content(), true);
        $token = $response['token'];
        $header = [
            'HTTP_Authorization' => 'Bearer{' . $token . '}',
        ];

        $response = $this->call('GET', 'admin/role_list', [], [], [], $header);
        $this->assertEquals(200, $response->status());
        $response = json_decode($response->content(), true);
        $this->assertEquals(3, count($response));

        $response = $this->call('GET', 'admin/permission_list', [], [], [], $header);
        $this->assertEquals(200, $response->status());
        $response = json_decode($response->content(), true);
        $this->assertEquals(4, count($response));

        $data = ['permission_roles' => [1,2]];
        $response = $this->call('PUT', 'admin/set_permission/' . '1', $data, [], [], $header);
        $this->assertEquals(200, $response->status());
        $response = json_decode($response->content(), true);
        $this->assertEquals(true, $response['result']);

        $data = ['role_id' => 2];
        $response = $this->call('PUT', 'admin/set_role/' . $user->id, $data, [], [], $header);
        $this->assertEquals(200, $response->status());
        $response = json_decode($response->content(), true);
        $this->assertEquals(true, $response['result']);
    }
}
