<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1) PARTIDAS
        Schema::create('partidas', function (Blueprint $table) {
            $table->bigIncrements('id_partida');
            $table->string('nombre', 120);
            $table->enum('estado', ['config','en_curso','cerrada'])->default('config');
            $table->unsignedTinyInteger('ronda')->default(1); // 1..2
            $table->unsignedTinyInteger('turno')->default(1); // 1..6
            $table->string('dado_restriccion', 50)->nullable();
            $table->integer('creador_id'); // FK a usuarios.id_usuario (int(11))
            $table->timestamp('creado_en')->useCurrent();

            $table->index('estado', 'idx_partidas_estado');
            $table->index(['ronda','turno'], 'idx_partidas_ronda_turno');

            $table->foreign('creador_id')
                ->references('id_usuario')->on('usuarios')
                ->onUpdate('cascade')->onDelete('restrict');
        });

        // 2) PARTICIPANTES
        Schema::create('partida_jugadores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('partida_id');
            $table->integer('usuario_id'); // FK usuarios.id_usuario
            $table->unsignedTinyInteger('orden_mesa');
            $table->integer('puntos_totales')->default(0);
            $table->timestamp('creado_en')->useCurrent();

            $table->unique(['partida_id','usuario_id'], 'uq_pj_partida_usuario');
            $table->unique(['partida_id','orden_mesa'], 'uq_pj_partida_orden');
            $table->index('partida_id', 'idx_pj_partida');

            $table->foreign('partida_id')
                ->references('id_partida')->on('partidas')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('usuario_id')
                ->references('id_usuario')->on('usuarios')
                ->onUpdate('cascade')->onDelete('restrict');
        });

        // 3) RECINTOS (catálogo)
        Schema::create('recintos', function (Blueprint $table) {
            $table->tinyIncrements('id_recinto');
            $table->string('clave', 50)->unique();
            $table->string('descripcion', 200)->nullable();
        });

        // 4) DINO CATÁLOGO
        Schema::create('dinosaurios_catalogo', function (Blueprint $table) {
            $table->smallIncrements('id_dino');
            $table->string('nombre_corto', 50)->unique();
            $table->string('categoria', 50)->nullable();
        });

        // 5) COLOCACIONES (jugadas)
        Schema::create('colocaciones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('partida_id');
            $table->integer('usuario_id'); // FK usuarios.id_usuario
            $table->unsignedTinyInteger('ronda'); // 1..2
            $table->unsignedTinyInteger('turno'); // 1..6
            $table->unsignedTinyInteger('recinto_id'); // FK recintos.id_recinto
            $table->unsignedSmallInteger('tipo_dino');  // FK dinosaurios_catalogo.id_dino
            $table->boolean('valido')->default(true);
            $table->string('motivo_invalidez', 200)->nullable();
            $table->integer('pts_obtenidos')->default(0);
            $table->timestamp('creado_en')->useCurrent();

            $table->index(['partida_id','usuario_id'], 'idx_col_part_usr');
            $table->index(['partida_id','ronda','turno'], 'idx_col_turno');
            $table->index('recinto_id', 'idx_col_recinto');

            $table->foreign('partida_id')
                ->references('id_partida')->on('partidas')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('usuario_id')
                ->references('id_usuario')->on('usuarios')
                ->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('recinto_id')
                ->references('id_recinto')->on('recintos')
                ->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('tipo_dino')
                ->references('id_dino')->on('dinosaurios_catalogo')
                ->onUpdate('cascade')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('colocaciones');
        Schema::dropIfExists('dinosaurios_catalogo');
        Schema::dropIfExists('recintos');
        Schema::dropIfExists('partida_jugadores');
        Schema::dropIfExists('partidas');
    }
};
