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
        Schema::table('employees', function (Blueprint $table) {
            $table->string('highest_qualification')->nullable();
            $table->decimal('desired_salary', 10, 2)->nullable();
            $table->timestamp('confirmation_email_sent_at')->nullable();

            $table->text('note')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'highest_qualification',
                'desired_salary',
                'confirmation_email_sent_at',
                'note',
            ]);
        });
    }
};
