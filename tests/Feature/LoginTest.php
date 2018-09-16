<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    protected $clientSecret = 'uSCVtUnO49yEfUFlKrY6vOnqGLbidterN6tXm9Ba';
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testLoginSuccess()
    {
        $response = $this->call('POST', '/oauth/token', [
            'username' => 'customer@mail.com',
            'password' => '123456',
            'client_secret' => $this->clientSecret,
            'client_id' => '2',
            'grant_type' => 'password',
            '_token' => csrf_token()
        ]);
        $this->assertEquals(200, $response->getStatusCode());
    }
    

    public function testLoginFailed()
    {
        $response = $this->call('POST', '/oauth/token', [
            'username' => 'wrongemail@mail.com',
            'password' => 'wrongpassword',
            'client_secret' => $this->clientSecret,
            'client_id' => '2',
            'grant_type' => 'password',
            '_token' => csrf_token()
        ]);
        $this->assertEquals(401, $response->getStatusCode());
    }
}
