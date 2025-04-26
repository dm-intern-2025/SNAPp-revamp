<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->string('account_name')->nullable();
            $table->string('short_name')->nullable();
            $table->text('business_address')->nullable();
            $table->text('facility_address')->nullable();
            $table->string('customer_category')->nullable();
            $table->string('cooperation_period_start_date')->nullable();
            $table->string('cooperation_period_end_date')->nullable();
            $table->string('contract_price')->nullable();
            $table->string('contracted_demand')->nullable();
            $table->text('other_information')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('designation')->nullable();
            $table->string('user_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('profiles');
    }
}
