<?php

namespace App\Services;

use App\Models\RecintoCatalogo;
use App\Models\DinosaurioCatalogo;
use App\Models\Colocacion;

class ServicioPuntaje
{
    /**
     * Valida si una jugada cumple con la restricción del dado.
     * Todas las comprobaciones se hacen solo dentro del parque del jugador actual.
     */
    public function validarRestriccion(
        string $restriccion,
        RecintoCatalogo $recinto,
        ?int $partidaId = null,
        ?int $usuarioId = null
    ): array {
        $restriccion = trim(strtolower($restriccion));
        $clave = strtolower($recinto->clave);

        //  Mapeo de recintos válidos por cara del dado
        $mapaRestricciones = [
            'el bosque'         => ['rio', 'bosque_semejanza', 'trio_frondoso', 'rey_selva'],
            'llanura'           => ['pradera_amor', 'prado_diferencia', 'isla_solitario', 'rio'],
            'baños'             => ['rio', 'prado_diferencia', 'isla_solitario', 'rey_selva'],
            'cafetería'         => ['bosque_semejanza', 'trio_frondoso', 'pradera_amor', 'rio'],
            'recinto vacío'     => ['*'],
            '¡cuidado con el t-rex!' => ['*'],
        ];

        //  Validación general (ubicación)
        if (isset($mapaRestricciones[$restriccion]) && $mapaRestricciones[$restriccion][0] !== '*') {
            $permitidos = $mapaRestricciones[$restriccion];
            if (!in_array($clave, $permitidos)) {
                return [
                    'valido' => false,
                    'motivo' => "❌ El recinto '{$recinto->descripcion}' no está habilitado para la restricción del dado '{$restriccion}'.",
                ];
            }
        }

        //  Validaciones especiales (filtradas por jugador)
        if ($partidaId && $usuarioId) {

            //  Recinto Vacío → ese recinto del jugador no debe tener dinos
            if ($restriccion === 'recinto vacío') {
                $ocupado = Colocacion::where('partida_id', $partidaId)
                    ->where('usuario_id', $usuarioId)
                    ->where('recinto_id', $recinto->id)
                    ->exists();

                if ($ocupado) {
                    return [
                        'valido' => false,
                        'motivo' => "🚫 El recinto '{$recinto->descripcion}' ya contiene dinosaurios. Debe estar vacío para esta restricción.",
                    ];
                }
            }

            //  ¡Cuidado con el T-Rex! → ese recinto del jugador no debe tener T-Rex
            if ($restriccion === '¡cuidado con el t-rex!') {
                $hayTrex = Colocacion::where('partida_id', $partidaId)
                    ->where('usuario_id', $usuarioId)
                    ->where('recinto_id', $recinto->id)
                    ->whereHas('dinoCatalogo', fn($q) => $q->where('nombre_corto', 'like', '%T-Rex%'))
                    ->exists();

                if ($hayTrex) {
                    return [
                        'valido' => false,
                        'motivo' => "🦖 Este recinto ya contiene un T-Rex. No se pueden colocar más aquí.",
                    ];
                }
            }
        }

        return ['valido' => true, 'motivo' => null];
    }

    /**
     * Calcula los puntos obtenidos por colocar un dinosaurio (solo dentro del parque del jugador).
     */
    public function calcularPuntos(
        RecintoCatalogo $recinto,
        DinosaurioCatalogo $dino,
        ?int $partidaId = null,
        ?int $usuarioId = null
    ): int {
        if (!$partidaId || !$usuarioId) return (int) ($recinto->puntos_base ?? 0);

        //  Colocaciones del jugador (su propio parque)
        $colocaciones = Colocacion::where('partida_id', $partidaId)
            ->where('usuario_id', $usuarioId)
            ->with('dinoCatalogo')
            ->get();

        //  Bosque de las Semejanzas
        if ($recinto->tipo_regla === 'semejanza') {
            $enRecinto = $colocaciones->where('recinto_id', $recinto->id);
            if ($enRecinto->isNotEmpty()) {
                $tipoExistente = strtolower($enRecinto->first()->dinoCatalogo->nombre_corto);
                if (strtolower($dino->nombre_corto) !== $tipoExistente) {
                    throw new \Exception("Solo podés colocar dinosaurios del mismo tipo en este recinto.");
                }
            }

            $cantidad = $enRecinto->count() + 1;
            $tabla = [1 => 2, 2 => 4, 3 => 8, 4 => 12, 5 => 18, 6 => 24];
            return $tabla[$cantidad] ?? end($tabla);
        }

        //  Prado de la Diferencia
        if ($recinto->tipo_regla === 'variedad') {
            $enRecinto = $colocaciones->where('recinto_id', $recinto->id);
            $tiposExistentes = $enRecinto->pluck('dinoCatalogo.nombre_corto')->map('strtolower')->toArray();

            if (in_array(strtolower($dino->nombre_corto), $tiposExistentes)) {
                throw new \Exception("No se pueden repetir dinosaurios en este recinto.");
            }

            $cantidad = count($tiposExistentes) + 1;
            $tabla = [1 => 1, 2 => 3, 3 => 6, 4 => 10, 5 => 15, 6 => 21];
            return $tabla[$cantidad] ?? end($tabla);
        }

        //  Pradera del Amor (parejas del mismo tipo)
        if ($recinto->tipo_regla === 'parejas') {
            $enRecinto = $colocaciones->where('recinto_id', $recinto->id);

            // Contar dinosaurios por especie
            $porTipo = $enRecinto
                ->groupBy(fn($c) => strtolower($c->dinoCatalogo->nombre_corto))
                ->map(fn($grupo) => $grupo->count());

            // Incluir el nuevo dinosaurio
            $nombreNuevo = strtolower($dino->nombre_corto);
            $porTipo[$nombreNuevo] = ($porTipo[$nombreNuevo] ?? 0) + 1;

            // Calcular parejas totales
            $totalParejas = 0;
            foreach ($porTipo as $cantidad) {
                $totalParejas += intdiv($cantidad, 2);
            }

            return $totalParejas * 5;
        }

        //  Isla del Solitario
        if ($recinto->tipo_regla === 'solitario') {
            $repetidos = $colocaciones
                ->where('tipo_dino', $dino->id)
                ->count();

            return $repetidos > 0 ? 0 : 7;
        }

        //  Trío Frondoso (exactamente 3 dinosaurios)
        if ($recinto->tipo_regla === 'exactos') {
            $enRecinto = $colocaciones->where('recinto_id', $recinto->id);
            $cantidad = $enRecinto->count() + 1; // +1 por el nuevo dino que se está colocando

            return $cantidad === 3 ? 7 : 0;
        }

        //  Rey de la Selva (especial) — mayoría global por especie
        if ($recinto->tipo_regla === 'especial') {
            // Contar cuántos dinosaurios de esa especie tiene cada jugador
            $conteoPorJugador = Colocacion::where('partida_id', $partidaId)
                ->where('tipo_dino', $dino->id)
                ->selectRaw('usuario_id, COUNT(*) as total')
                ->groupBy('usuario_id')
                ->pluck('total', 'usuario_id');

            $cantidadJugador = $conteoPorJugador[$usuarioId] ?? 0;
            $maximo = $conteoPorJugador->max();

            // Si el jugador tiene la cantidad máxima (o empatado en ella), obtiene 7 puntos
            return $cantidadJugador === $maximo ? 7 : 0;
        }
        //  Río (cada dino = 1 punto fijo)
        if (strtolower($recinto->clave) === 'rio') {
            return 1;
        }

        //  Por defecto
        return (int) ($recinto->puntos_base ?? 0);
    }

    /**
     * Evalúa la jugada completa (solo afecta al jugador que coloca).
     */
    public function evaluarJugada(
        string $restriccion,
        RecintoCatalogo $recinto,
        DinosaurioCatalogo $dino,
        ?int $partidaId = null,
        ?int $usuarioId = null
    ): array {
        //  Si el jugador es quien lanzó el dado → libre
        $tiradorId = session('tirador_id');
        if ($usuarioId && $tiradorId && $usuarioId === $tiradorId) {
            $puntos = $this->calcularPuntos($recinto, $dino, $partidaId, $usuarioId);
            return [
                'valido' => true,
                'motivo' => '🎲 Jugador que tiró el dado: puede colocar libremente.',
                'puntos' => $puntos,
            ];
        }

        //  Validación normal para el resto
        $validacion = $this->validarRestriccion($restriccion, $recinto, $partidaId, $usuarioId);

        if (!$validacion['valido']) {
            return ['valido' => false, 'motivo' => $validacion['motivo'], 'puntos' => 0];
        }

        try {
            $puntos = $this->calcularPuntos($recinto, $dino, $partidaId, $usuarioId);
        } catch (\Exception $e) {
            return ['valido' => false, 'motivo' => $e->getMessage(), 'puntos' => 0];
        }

        return ['valido' => true, 'motivo' => null, 'puntos' => $puntos];
    }
}
