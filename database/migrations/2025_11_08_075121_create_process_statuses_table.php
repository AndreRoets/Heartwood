<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('process_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('status')->default('idle')->after('id');
            $table->timestamp('nomination_starts_at')->nullable();
            $table->timestamp('nomination_ends_at')->nullable();
            $table->timestamp('voting_starts_at')->nullable();
            $table->timestamp('voting_ends_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('process_statuses');
    }
};
