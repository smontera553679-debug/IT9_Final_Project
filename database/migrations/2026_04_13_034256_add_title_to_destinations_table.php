<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('destinations', function (Blueprint $table) {
        $table->string('title')->nullable()->after('name'); 
    });
}

public function down()
{
    Schema::table('destinations', function (Blueprint $table) {
        $table->dropColumn('title');
    });
}
};
