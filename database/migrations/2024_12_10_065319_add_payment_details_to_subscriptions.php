<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('payment_reference')->nullable()->after('subscription_code');
            $table->decimal('amount_paid', 10, 2)->nullable()->after('payment_reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('payment_reference');
            $table->dropColumn('amount_paid');
        });
    }
};