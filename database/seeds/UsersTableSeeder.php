<?php

use App\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('roles')
            ->insert(
                [
                    [
                        'slug' => 'customer',
                        'name' => 'Customer',
                    ],
                    [
                        'slug' => 'manager-bank',
                        'name' => 'Manager',
                    ],
                    [
                        'slug' => 'teller-bank',
                        'name' => 'Teller',
                    ],
                ]
            );
        $customer = User::create([
            'name' => 'Customer',
            'email' => 'customer@mail.com',
            'password' => bcrypt('123456'),
        ]);
        $manager = User::create([
            'name' => 'Manager Bank',
            'email' => 'manager@mail.com',
            'password' => bcrypt('123456'),
        ]);
        $teller = User::create([
            'name' => 'Teller Bank',
            'email' => 'teller@mail.com',
            'password' => bcrypt('123456'),
        ]);

        DB::table(
            'account_types'
        )->insert(
            [
                'name' => 'Platinum',
            ]
        );

        DB::table(
            'role_users'
        )->insert(
            [
                [
                    'user_id' => $customer->id,
                    'role_id' => DB::table('roles')->where('slug', 'customer')->first()->id,
                ],
                [
                    'user_id' => $manager->id,
                    'role_id' => DB::table('roles')->where('slug', 'manager-bank')->first()->id,
                ],
                [
                    'user_id' => $teller->id,
                    'role_id' => DB::table('roles')->where('slug', 'teller-bank')->first()->id,
                ],
            ]
        );

        DB::table(
            'customers'
        )->insert(
            [
                'user_id' => $customer->id,
                'title' => 'Mr',
                'name' => 'Customer',
                'phone' => '08212233445',
                'address' => 'Jl Jenderal Sudirman no 10',
                'date_of_birth' => '1990-01-01',
                'mother_name' => 'Nisa',
            ]
        );

        $custProfile = DB::table('customers')
            ->where('user_id', $customer->id)
            ->first();
        $accountType = DB::table('account_types')->first();

        DB::table(
            'customer_accounts'
        )->insert(
            [
                'customer_id' => $custProfile->id,
                'account_type_id' => $accountType->id,
                'rekening_number' => $this->generateNumber(12),
                'card_number' => $this->generateNumber(16),
                'expired_date' => Carbon::now()->addYear()->format('Y-m-d'),
                'pin' => $this->generateNumber(6),
                'status' => 'active',
            ]
        );
    }

    protected function generateNumber($digits)
    {
        $returnString = rand(1, 9);
        while (strlen($returnString) < $digits) {
            $returnString .= rand(0, 9);
        }

        return $returnString;
    }
}
