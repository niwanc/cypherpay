<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->bigIncrements('transaction_id');
            $table->integer('reference_id');
            $table->enum('reference_type', ['INTERNAL', 'EXTERNAL'])->default('INTERNAL');
            $table->integer('user_id');
            $table->string('description');
            $table->float('amount');
            $table->text('successIndicator');
            $table->text('session_id');
            $table->text('session_version');
            $table->text('transaction_reference_id');
            $table->enum('status', ['PENDING', 'COMPLETED', 'REJECTED'])->default('PENDING');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_transactions');
    }
}
