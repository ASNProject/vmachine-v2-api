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
        Schema::create('limit_periods', function (Blueprint $table) {
            $table->id();
            $table->string('uid');
            $table->foreignId('group_id')->constrained('item_groups');
            $table->string('period_month');
            $table->integer('remaining_qty');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('limit_periods');
    }
};
