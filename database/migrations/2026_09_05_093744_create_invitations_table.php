<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email');
            $table->enum('role', ['Admin', 'Member']);
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('token')->unique();
            $table->boolean('accepted')->default(false);
            $table->timestamps();
        });
    }
};
