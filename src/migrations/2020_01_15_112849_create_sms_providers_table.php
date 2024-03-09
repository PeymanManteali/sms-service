<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sms_providers', function (Blueprint $table) {
            $table->id();
            $table->string('method');
            $table->string('type');
            $table->json('params');
            $table->boolean('usable')->default(true);
            $table->boolean('short_deactivation')->default(false);
            $table->bigInteger('count')->default(0);
            $table->integer('fail_count')->default(0);
            $table->integer('total_fail_count')->default(0);
            $table->tinyInteger('inactive_level')->default(0);
            $table->timestamp('inactive_until')->nullable();
            $table->timestamp('last_use_at')->nullable();
            $table->timestamp('last_fail_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_providers');
    }
};
