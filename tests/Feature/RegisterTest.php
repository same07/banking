<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testRegisterSuccess()
    {
        $headers = [
            'Accept' => 'application/json',
        ];
        $param = [
                'name' => 'Testing Name',
                'email' => rand().'@mail.com',
                'password' => '123456',
                'phone' => '123456',
                'address' => 'lorem ipsum',
            ];
        $response = $this
            ->post('/api/register', $param, $headers);

        $this->assertEquals(200, $response->getStatusCode());

    }

    public function testRegisterFailed()
    {
        $headers = [
            'Accept' => 'application/json',
            'X-Requested-With' => 'application/x-www-form-urlencoded'
        ];
        $response = $this->post('/api/register', [
            'name' => '',
            'email' => '',
            '_token' => csrf_token()
        ], $headers);
        $this->assertEquals(422, $response->getStatusCode());
    }

}
