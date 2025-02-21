<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('priority');
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->enum('priority', ['baja', 'media', 'alta', 'urgente'])->default('baja');
        });
    }

    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->integer('priority')->default(1)->change();
        });
    }
};
