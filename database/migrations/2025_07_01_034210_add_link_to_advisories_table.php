<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('advisories', function (Blueprint $table) {
            $table->string('link')->nullable()->after('attachment');
        });
    }

    /**
     * Reverse the migrations.
     */
public function down()
    {
        Schema::table('advisories', function (Blueprint $table) {
            $table->dropColumn('link');
        });
    }
};
