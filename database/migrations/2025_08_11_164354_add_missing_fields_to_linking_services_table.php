<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('linking_services', function (Blueprint $table) {
            $table->decimal('commission_rate', 5, 2)->nullable()->after('commission');
            $table->text('description')->nullable()->after('commission_rate');
            $table->text('admin_notes')->nullable()->after('description');
            $table->timestamp('completed_at')->nullable()->after('linked_at');
        });
    }

    public function down()
    {
        Schema::table('linking_services', function (Blueprint $table) {
            $table->dropColumn(['commission_rate', 'description', 'admin_notes', 'completed_at']);
        });
    }
};
