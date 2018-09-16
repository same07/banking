<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CustomerAccounts extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('customer_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('customer_id');
            $table->unsignedInteger('account_type_id');
            $table->bigInteger('rekening_number')->unique();
            $table->bigInteger('card_number')->unique();
            $table->date('expired_date');
            $table->integer('pin');
            $table->enum('status', ['active', 'blocked', 'pending']);
            $table->timestamps();

            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('account_type_id')
                ->references('id')
                ->on('account_types')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('customer_accounts');
    }
}
