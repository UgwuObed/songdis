<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PromoCodesTable extends Migration
{
    public function up()
    {
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->boolean('is_active')->default(true);
            $table->integer('duration_days')->default(30);
            $table->integer('times_used')->default(0);
            $table->integer('max_uses')->default(1);
            $table->timestamps();
        });

        Schema::create('promo_code_uses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('promo_code_id')->constrained();
            $table->timestamp('expires_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('promo_code_uses');
        Schema::dropIfExists('promo_codes');
    }
}