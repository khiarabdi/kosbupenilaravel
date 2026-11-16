
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('tenant')->index();
            $table->string('phone', 20)->nullable();
            $table->string('nik', 16)->nullable()->unique();
            $table->text('address')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['nik']);
            $table->dropIndex(['role']);
            $table->dropColumn([
                'role',
                'phone',
                'nik',
                'address'
            ]);
        });
    }
};
