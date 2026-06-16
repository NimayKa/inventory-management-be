<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('logs', function (Blueprint $table) {
            $table->dropForeign(['inventory_id']);
            $table->foreign('inventory_id')->references('id')->on('inventories')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('logs', function (Blueprint $table) {
            $table->dropForeign(['inventory_id']);
            $table->foreign('inventory_id')->references('id')->on('inventories')->onDelete('cascade');
        });
    }
};