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
        Schema::create('issuers', function (Blueprint $table) {
            $table->id();
            $table->string('issuer')->index();
            $table->string('client_id')->index();
            $table->string('key_set_url');
            $table->string('auth_token_url');
            $table->string('auth_login_url');
            $table->string('auth_server')->nullable();
            $table->text('tool_private_key');
            $table->string('kid')->nullable();
            $table->timestamps();

            $table->unique(['issuer', 'client_id']);
        });

        Schema::create('deployments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('issuer_id')->constrained()->onDelete('cascade');
            $table->string('deployment_id');
            $table->timestamps();

            $table->unique(['issuer_id', 'deployment_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issuers');
        Schema::dropIfExists('deployments');
    }
};
