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
        Schema::create('customer_item_usages', function (Blueprint $table) {
            $table->id();
            $table->string('uid');
            $table->foreignId('item_id')->constrained('items');
            $table->foreignId('group_id')->constrained('item_groups');
            $table->string('keypad_code');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_item_usages');
    }
};
