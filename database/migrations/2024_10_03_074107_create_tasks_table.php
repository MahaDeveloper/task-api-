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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->unsignedBigInteger('assign_to')->nullable();
            $table->foreign('assign_to')->references('id')->on('users')->onDelete('set null');
            $table->string('title');
            $table->text('description');
            $table->enum('status', ['pending', 'in_progress', 'completed']);
            $table->date('due_date')->nullable();
            $table->string('attachment')->nullable();
            $table->tinyInteger('priority')->default(0)->comment('0: Low, 1: Medium, 2: High');
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
        Schema::dropIfExists('tasks');
    }
};
