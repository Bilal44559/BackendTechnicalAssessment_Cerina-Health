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
        Schema::create('appointments', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('doctor_id');
            $table->unsignedBigInteger('patient_id');

            $table->dateTime('appointment_date');
            $table->enum('status', ['pending','confirmed','completed','cancelled'])
                  ->default('pending');
            $table->text('notes')->nullable();
            $table->engine = 'InnoDB';
            $table->timestamps();

            $table->foreign('doctor_id')
                  ->references('id')
                  ->on('doctors')
                  ->onDelete('cascade');

            $table->foreign('patient_id')
                  ->references('id')
                  ->on('patients')
                  ->onDelete('cascade');
        });



    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
