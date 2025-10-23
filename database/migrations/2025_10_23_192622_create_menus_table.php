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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('router_link')->nullable();
            $table->string('href')->nullable();
            $table->string('icon')->nullable();
            $table->string('target')->nullable();
            $table->boolean('has_sub_menu')->default(false);
            $table->unsignedBigInteger('parent_id')->default(0);
            $table->string('type')->comment('vertical or horizontal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
