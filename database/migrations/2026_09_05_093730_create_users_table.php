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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->foreignId('company_id')->nullable()->constrained('companies')->cascadeOnDelete();
            $table->enum('role', ['SuperAdmin', 'Admin', 'Member'])->default('Member');
            $table->rememberToken();
            $table->timestamps();
        });
    }
};
