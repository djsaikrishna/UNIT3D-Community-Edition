<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTorrentsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('torrents', function (Blueprint $table) {
            $table->integer('distributor_id')->nullable()->index()->after('resolution_id');
            $table->integer('region_id')->nullable()->index()->after('distributor_id');
        });
    }
}