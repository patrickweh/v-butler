<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('component')->nullable();
            $table->string('icon')->default('');
            $table->json('config')->nullable();
            $table->json('details')->nullable();
            $table->float('value')->nullable();
            $table->boolean('is_on')->default(false);
            $table->boolean('is_group')->default(false);
            $table->string('foreign_id')->nullable();
            $table->unsignedBigInteger('service_id')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['service_id', 'foreign_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('devices');
    }
};
