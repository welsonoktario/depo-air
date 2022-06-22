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
        Schema::create('depo_barangs', function (Blueprint $table) {
            $table->foreignId('depo_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('barang_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->integer('stok')
                ->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('depo_barangs');
    }
};
