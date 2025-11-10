#!/bin/bash
set -euo pipefail

# ======================
# Configuración
# ======================
FECHA=$(date +"%Y%m%d_%H%M%S")

LOCAL_BACKUPS="/home/jurassiuser/backups"
REMOTE_USER="jurassiuser"
REMOTE_HOST="10.10.10.2"
REMOTE_DIR="/home/jurassiuser/backups"

LOG_DIR="/var/log/jurassidraft"
MAIN_LOG="$LOG_DIR/respaldos.log"
DETAIL_LOG="$LOCAL_BACKUPS/sync_$FECHA.log"

mkdir -p "$LOG_DIR" "$LOCAL_BACKUPS"
touch "$MAIN_LOG" "$DETAIL_LOG"
chmod 664 "$MAIN_LOG" "$DETAIL_LOG"

# ======================
# Función de log
# ======================
log() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1" | tee -a "$DETAIL_LOG" >> "$MAIN_LOG"
}

log "===== SYNC iniciado $FECHA ====="
log "[INFO] Host remoto: $REMOTE_HOST"
log "[INFO] Directorio destino: $REMOTE_DIR"

# ======================
# Verificar conectividad
# ======================
if ! ping -c1 -W2 "$REMOTE_HOST" >/dev/null 2>&1; then
    log "[ERROR] No hay conexión con $REMOTE_HOST. Sincronización cancelada."
    log "===== SYNC finalizado con error ====="
    exit 1
fi

# ======================
# Sincronización con rsync
# ======================
log "[INFO] Iniciando rsync..."
if rsync -avz --delete "$LOCAL_BACKUPS/" "${REMOTE_USER}@${REMOTE_HOST}:${REMOTE_DIR}/" >> "$DETAIL_LOG" 2>&1; then
    log "[OK] Sincronización completada correctamente hacia $REMOTE_HOST:$REMOTE_DIR"
else
    log "[ERROR] Falló la sincronización con $REMOTE_HOST"
    log "===== SYNC finalizado con error ====="
    exit 1
fi

log "===== SYNC finalizado correctamente $FECHA ====="
