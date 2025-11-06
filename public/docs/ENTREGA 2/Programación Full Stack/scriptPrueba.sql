-- ===========================================
-- SEED: datos iniciales de prueba
-- Requiere el esquema ya creado y la DB seleccionada (USE draftosaurus;)
-- ===========================================

-- Roles (por si no existen)
INSERT IGNORE INTO roles (rol, descripcion) VALUES
  ('jugador','Usuario participante'),
  ('admin','Usuario administrador');

-- Usuarios (hash bcrypt de "password" compatible Laravel)
-- $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi  => "password"
INSERT INTO usuarios (nombre, usuario, contrasena, rol)
VALUES
  ('Admin Demo',  'admin_demo',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
  ('Seba',        'seba_demo',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'jugador'),
  ('Tomi',        'tomi_demo',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'jugador'),
  ('Nacho',       'nacho_demo',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'jugador'),
  ('Joaco',       'joaco_demo',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'jugador')
ON DUPLICATE KEY UPDATE nombre=VALUES(nombre);

-- Recintos base (ejemplos)
INSERT IGNORE INTO recintos (clave, descripcion) VALUES
  ('bosque',    'Recinto Bosque'),
  ('rio',       'Recinto Río'),
  ('pradera',   'Recinto Pradera'),
  ('montana',   'Recinto Montaña'),
  ('pantano',   'Recinto Pantano');

-- Catálogo de dinosaurios (ejemplos)
INSERT IGNORE INTO dinosaurios_catalogo (nombre_corto, categoria) VALUES
  ('TRex',        'carnivoro'),
  ('Raptor',      'carnivoro'),
  ('Stego',       'herbivoro'),
  ('Tricera',     'herbivoro'),
  ('Ptero',       'volador');

-- Variables de IDs útiles
SET @admin_id  = (SELECT id_usuario FROM usuarios WHERE usuario='admin_demo');
SET @seba_id   = (SELECT id_usuario FROM usuarios WHERE usuario='seba_demo');
SET @tomi_id   = (SELECT id_usuario FROM usuarios WHERE usuario='tomi_demo');
SET @nacho_id  = (SELECT id_usuario FROM usuarios WHERE usuario='nacho_demo');
SET @joaco_id  = (SELECT id_usuario FROM usuarios WHERE usuario='joaco_demo');

-- Crear partida demo
INSERT INTO partidas (nombre, estado, creador_id)
VALUES ('Partida Demo', 'config', @admin_id);

SET @partida_id = LAST_INSERT_ID();

-- Inscribir jugadores (orden de mesa)
INSERT INTO partida_jugadores (partida_id, usuario_id, orden_mesa)
VALUES
  (@partida_id, @seba_id,  1),
  (@partida_id, @tomi_id,  2),
  (@partida_id, @nacho_id, 3),
  (@partida_id, @joaco_id, 4);

-- Instanciar recintos para cada jugador (un tablero con todos los recintos)
INSERT IGNORE INTO recintos_tablero (partida_id, usuario_id, recinto_id)
SELECT @partida_id, u.id_usuario, r.id_recinto
FROM usuarios u
JOIN recintos r
WHERE u.usuario IN ('seba_demo','tomi_demo','nacho_demo','joaco_demo');

-- Jugadas de ejemplo (colocaciones) en lote único
-- Seba coloca 2 dinos, Tomi coloca 2, Nacho 1, Joaco 1
SET @TRex     = (SELECT id_dino FROM dinosaurios_catalogo WHERE nombre_corto='TRex');
SET @Raptor   = (SELECT id_dino FROM dinosaurios_catalogo WHERE nombre_corto='Raptor');
SET @Stego    = (SELECT id_dino FROM dinosaurios_catalogo WHERE nombre_corto='Stego');
SET @Tricera  = (SELECT id_dino FROM dinosaurios_catalogo WHERE nombre_corto='Tricera');
SET @Ptero    = (SELECT id_dino FROM dinosaurios_catalogo WHERE nombre_corto='Ptero');

SET @bosque   = (SELECT id_recinto FROM recintos WHERE clave='bosque');
SET @rio      = (SELECT id_recinto FROM recintos WHERE clave='rio');
SET @pradera  = (SELECT id_recinto FROM recintos WHERE clave='pradera');
SET @montana  = (SELECT id_recinto FROM recintos WHERE clave='montana');
SET @pantano  = (SELECT id_recinto FROM recintos WHERE clave='pantano');

START TRANSACTION;

INSERT INTO colocaciones (partida_id, usuario_id, recinto_id, tipo_dino, pts_obtenidos, creado_en) VALUES
  (@partida_id, @seba_id,  @bosque,  @Stego,   2, NOW()),
  (@partida_id, @seba_id,  @rio,     @Tricera, 1, NOW()),
  (@partida_id, @tomi_id,  @pradera, @TRex,    3, NOW()),
  (@partida_id, @tomi_id,  @montana, @Raptor,  2, NOW()),
  (@partida_id, @nacho_id, @bosque,  @Ptero,   1, NOW()),
  (@partida_id, @joaco_id, @pantano, @Stego,   2, NOW());

-- Derivar puntos_recinto desde las colocaciones (ejemplo simple sumando pts_obtenidos por recinto)
INSERT INTO puntos_recinto (partida_id, usuario_id, recinto_id, puntos)
SELECT c.partida_id, c.usuario_id, c.recinto_id, SUM(c.pts_obtenidos)
FROM colocaciones c
WHERE c.partida_id = @partida_id
GROUP BY c.partida_id, c.usuario_id, c.recinto_id
ON DUPLICATE KEY UPDATE puntos = VALUES(puntos);

COMMIT;

-- Registrar el cierre del lote y pasar a 'en_curso'
CALL sp_cerrar_turno_o_partida(
  @partida_id,
  @admin_id,
  1,              -- ronda_final (informativo)
  1,              -- turno_final (informativo)
  'bosque',       -- dado_final (informativo)
  'en_curso',     -- estado_final
  6               -- cantidad de jugadas insertadas arriba
);

-- Partida de ejemplo 2 ya cerrada, con 2 jugadores
INSERT INTO partidas (nombre, estado, creador_id)
VALUES ('Partida Cerrada', 'config', @admin_id);
SET @partida2 = LAST_INSERT_ID();

INSERT INTO partida_jugadores (partida_id, usuario_id, orden_mesa)
VALUES
  (@partida2, @seba_id, 1),
  (@partida2, @tomi_id, 2);

INSERT IGNORE INTO recintos_tablero (partida_id, usuario_id, recinto_id)
SELECT @partida2, u.id_usuario, r.id_recinto
FROM usuarios u
JOIN recintos r
WHERE u.usuario IN ('seba_demo','tomi_demo');

INSERT INTO colocaciones (partida_id, usuario_id, recinto_id, tipo_dino, pts_obtenidos, creado_en) VALUES
  (@partida2, @seba_id, @bosque,  @Stego,  2, NOW()),
  (@partida2, @tomi_id, @pradera, @TRex,   4, NOW());

INSERT INTO puntos_recinto (partida_id, usuario_id, recinto_id, puntos)
SELECT c.partida_id, c.usuario_id, c.recinto_id, SUM(c.pts_obtenidos)
FROM colocaciones c
WHERE c.partida_id = @partida2
GROUP BY c.partida_id, c.usuario_id, c.recinto_id
ON DUPLICATE KEY UPDATE puntos = VALUES(puntos);

CALL sp_cerrar_turno_o_partida(
  @partida2,
  @admin_id,
  1,
  8,
  'rio',
  'cerrada',
  2
);

-- Fin seed
