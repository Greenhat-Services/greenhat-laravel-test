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
            $table->string('highest_qualification')->nullable()->after('role');
            $table->decimal('desired_salary', 10, 2)->nullable()->after('highest_qualification');
            $table->text('note')->nullable()->after('desired_salary');
            $table->timestamp('confirmation_email_sent_at')->nullable()->after('note');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'highest_qualification',
                'desired_salary',
                'note',
                'confirmation_email_sent_at',
            ]);
        });
    }
};
