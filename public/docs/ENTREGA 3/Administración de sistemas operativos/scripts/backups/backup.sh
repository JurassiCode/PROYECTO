#!/bin/bash

# ======================
# Configuración
# ======================
FECHA=$(date +"%Y%m%d_%H%M%S")
BACKUP_DIR="/home/jurassiuser/backups"
WEB_DIR="/home/jurassiuser/jurassidraft/web"
DB_NAME="jurassidraft"
DB_USER="root"
DB_PASS="JurassiCode"

# Logs
MAIN_LOG="/var/log/jurassidraft/respaldos.log"
DETAIL_LOG="$BACKUP_DIR/backup_$FECHA.log"

mkdir -p /var/log/jurassidraft
mkdir -p "$BACKUP_DIR"
touch "$MAIN_LOG" "$DETAIL_LOG"
chmod 664 "$MAIN_LOG" "$DETAIL_LOG"

# ======================
# Función de log
# ======================
log() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1" | tee -a "$DETAIL_LOG" >> "$MAIN_LOG"
}

log "===== BACKUP iniciado $FECHA ====="

# ======================
# Dump de la base de datos (contenedor MySQL)
# ======================
log "[INFO] Iniciando dump de la base de datos ($DB_NAME)..."
if docker exec jurassidraft-mysql mysqldump -u"$DB_USER" -p"$DB_PASS" "$DB_NAME" > "$BACKUP_DIR/db_$FECHA.sql" 2>>"$DETAIL_LOG"; then
    log "[OK] Dump completado correctamente."
else
    log "[ERROR] Falló el dump de la base de datos."
    exit 1
fi

# ======================
# Backup de los archivos web (excluyendo pesos innecesarios)
# ======================
log "[INFO] Creando backup de archivos web..."

# Lista de exclusiones comunes
EXCLUDES=(
    --exclude='node_modules'
    --exclude='vendor'
    --exclude='.git'
    --exclude='.env'
    --exclude='storage/framework/cache'
    --exclude='storage/logs'
    --exclude='storage/debugbar'
    --exclude='*.log'
    --exclude='*.lock'
)

if tar -czf "$BACKUP_DIR/web_$FECHA.tar.gz" "${EXCLUDES[@]}" -C "$WEB_DIR" . 2>>"$DETAIL_LOG"; then
    log "[OK] Archivos web comprimidos correctamente."
else
    log "[ERROR] Falló el backup de los archivos web."
fi

# ======================
# Empaquetar todo
# ======================
log "[INFO] Empaquetando archivos en backup_$FECHA.tar.gz..."
if tar -czf "$BACKUP_DIR/backup_$FECHA.tar.gz" -C "$BACKUP_DIR" "db_$FECHA.sql" "web_$FECHA.tar.gz" 2>>"$DETAIL_LOG"; then
    log "[OK] Backup final creado correctamente: $BACKUP_DIR/backup_$FECHA.tar.gz"
    rm -f "$BACKUP_DIR/db_$FECHA.sql" "$BACKUP_DIR/web_$FECHA.tar.gz"
else
    log "[ERROR] Falló la creación del archivo final."
fi

log "===== BACKUP finalizado $FECHA ====="
