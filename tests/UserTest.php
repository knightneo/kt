<?php

class UserTest extends TestCase
{
    public function testUser()
    {
        //test sign in
        $user = factory(App\Models\User::class)->create([
            'name' => 'test',
            'email' => 'test@email.com',
            'password' => Hash::make('123456'),
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
    }
}
