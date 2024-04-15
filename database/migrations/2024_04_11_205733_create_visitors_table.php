<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    use \Izpixel\LaravelTracker\Traits\WithHelpers;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create($this->getTableName(), function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->ipAddress();
            $table->text('referer')->nullable();
            $table->text('url');
            $table->json('queries')->nullable();
            $table->string('continent')->nullable();
            $table->string('continent_code')->nullable();
            $table->string('country')->nullable();
            $table->string('country_code')->nullable();
            $table->string('city')->nullable();
            $table->string('region')->nullable();
            $table->string('region_name')->nullable();
            $table->string('district')->nullable();
            $table->string('zip')->nullable();
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->string('offset')->nullable();
            $table->string('timezone')->nullable();
            $table->string('currency')->nullable();
            $table->string('language')->nullable();
            $table->json('languages')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('platform')->nullable();
            $table->string('platform_version')->nullable();
            $table->string('browser')->nullable();
            $table->string('browser_version')->nullable();
            $table->string('version')->nullable();
            $table->string('device')->nullable();
            $table->string('device_type')->nullable();
            $table->string('isp')->nullable();
            $table->string('org')->nullable();
            $table->string('as')->nullable();
            $table->string('as_name')->nullable();
            $table->string('reverse')->nullable();
            $table->boolean('is_mobile')->default(false);
            $table->boolean('is_proxy')->default(false);
            $table->boolean('is_hosting')->nullable(false);
            $table->json('steps')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists($this->getTableName());
    }
};
