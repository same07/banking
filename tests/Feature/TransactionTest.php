<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\User;
use App\AccountType;

class TransactionTest extends TestCase
{
    protected $user;
    protected $account_type;
    public function setUp()
    {
        parent::setUp();
        $this->user = User::find(1);
        $this->account_type = AccountType::find(1);
    }

    public function testListAccounts()
    {
        $this->actingAs($this->user);
        $headers = [
            'Accept' => 'application/json',
        ];
        $response = $this
            ->get('/user/account', $headers);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testRequestAccountSuccess()
    {
        $this->actingAs($this->user);
        $headers = [
            'Accept' => 'application/json',
        ];
        $params = [
            'account_type_id' => $this->account_type->id,
            'pin' => 123456
        ];
        $response = $this
            ->post('/customer/request', $params, $headers);

        $this->assertEquals(200, $response->getStatusCode());
    }
}
