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
        Schema::create('cronjobs', function (Blueprint $table) {
            $table->id();
            $table->string('type')->comment('Can be job, command or exec, see laravel docu for more information');
            $table->string('command')->comment('The command class including namespace');
            $table->string('expression')->comment('The expression in cron style');
            $table->json('command_params')->nullable();
            $table->json('schedule_params')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cronjobs');
    }
};
