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
        Schema::table('job_applications', function (Blueprint $table) {
              //
          $table->decimal("current_salary");
             $table->string("current_company");
                $table->decimal("notice_period");
                  $table->text("bio")->nullable();
                    $table->text("reason_to_join")->nullable();
                    $table->boolean("night_available")->default(0);
                      $table->string("linkedin")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            //
        });
    }
};