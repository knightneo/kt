<?php

class UserTest extends TestCase
{
    public function testUser()
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
            ['id' => 5, 'name' => 'user', 'is_deleted' => 0],
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
            ['id' => 8, 'role_id' => 3, 'permission_id' =>5, 'is_deleted' =>0],
        ];

        foreach ($permission_roles as $p) {
            factory(App\Models\PermissionRole::class)->create($p);
        }

        //test sign in
        $user = factory(App\Models\User::class)->create([
            'name' => 'test',
            'email' => 'test@email.com',
            'password' => Hash::make('123456'),
            'role_id' => 2,
        ]);
        $data = ['email'=>'test@email.com', 'password' => '123456'];
        $response = $this->call('POST', 'signin', $data);
        $this->assertEquals(200, $response->status());
        $response = json_decode($response->content(), true);
        $this->assertArrayHasKey('token', $response);
        $token = $response['token'];

        //test profile
        $header = [
            'HTTP_Authorization' => 'Bearer{' . $token . '}',
        ];
        $response = $this->call('GET', 'profile', [], [], [], $header, []);
        $this->assertEquals(200, $response->status());
        $response = json_decode($response->content(), true);
        $this->assertArrayHasKey('user', $response);
        $this->assertArrayHasKey('role', $response);
        $this->assertArrayHasKey('permission', $response);

        $params = [
            'name' => 'test_name',
            'email' => 'testname@test.com',
            'password' => 'qwerasdf',
        ];
        $response = $this->call('POST', 'signup', $params);
        $this->assertEquals(200, $response->status());
        $response = json_decode($response->content(), true);
        $this->assertEquals(true, $response['result']);

        $response = $this->call('POST', 'check/email/available', ['email'=>'testname@test.com']);
        $this->assertEquals(200, $response->status());
        $response = json_decode($response->content(), true);
        $this->assertEquals(false, $response['result']);
    }
}
