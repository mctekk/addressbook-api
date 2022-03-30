<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('google_id')->nullable()->after('remember_token');
                $table->string('facebook_id')->nullable()->after('google_id');
                $table->string('apple_id')->nullable()->after('facebook_id');
            });
        }
    }
};
