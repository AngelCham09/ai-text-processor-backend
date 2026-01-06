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
        Schema::create('api_logs', function (Blueprint $table) {
            $table->id();
            $table->string('method');             // GET, POST, etc.
            $table->string('url');                // API endpoint
            $table->unsignedBigInteger('user_id')->nullable(); // Auth user id, null if guest
            $table->integer('status_code');       // HTTP status
            $table->string('ip_address')->nullable(); // Client IP
            $table->float('execution_time', 8, 3)->nullable(); // seconds
            $table->text('error_message')->nullable(); // Error message if any
            $table->json('request_payload')->nullable(); // Request body
            $table->text('response_payload')->nullable(); // Response body (truncated if needed)
            $table->timestamps();

            $table->index('user_id');
            $table->index('url');
            $table->index('status_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_logs');
    }
};
