#!/bin/bash

# ======================
# Verificación de superusuario
# ======================
if [ "$EUID" -ne 0 ]; then
    echo "Este script debe ejecutarse como root o con sudo."
    exit 1
fi

# ======================
# Variables
# ======================
BACKUP_SCRIPT="/home/jurassiuser/scripts/backups/backup.sh"
SYNC_SCRIPT="/home/jurassiuser/scripts/backups/sync_backups.sh"
BACKUP_DIR="/home/jurassiuser/backups"
LOGFILE="/var/log/jurassidraft/respaldos.log"

mkdir -p /var/log/jurassidraft
touch "$LOGFILE"
chmod 664 "$LOGFILE"

# ======================
# Logging
# ======================
log_action() {
    local level="$1"
    local message="$2"
    local user
    user=$(whoami)
    echo "$(date '+%Y-%m-%d %H:%M:%S') [$level] ($user) $message" >> "$LOGFILE"
}

# ======================
# Funciones auxiliares
# ======================

configurar_cron_backups() {
    OPCION=$(dialog --clear --stdout --title "Configurar CRON - Backups" \
        --menu "Selecciona la frecuencia de los backups:" 15 60 5 \
        1 "Diario a las 03:00 (default)" \
        2 "Cada 12 horas" \
        3 "Semanal (domingo 03:00)" \
        4 "Desactivar backups automáticos" \
        5 "Cancelar")

    case $OPCION in
        1) CRON="0 3 * * * $BACKUP_SCRIPT" ;;
        2) CRON="0 */12 * * * $BACKUP_SCRIPT" ;;
        3) CRON="0 3 * * 0 $BACKUP_SCRIPT" ;;
        4) CRON="" ;;
        5) return ;;
    esac

    (crontab -l 2>/dev/null | grep -v "$BACKUP_SCRIPT"; [ -n "$CRON" ] && echo "$CRON") | crontab -
    dialog --msgbox "Configuración de backups actualizada." 7 50
    log_action "INFO" "CRON de backups actualizado (opción $OPCION)"
}

configurar_cron_sync() {
    OPCION=$(dialog --clear --stdout --title "Configurar CRON - Sync remoto" \
        --menu "Selecciona la frecuencia de sincronización:" 15 60 5 \
        1 "Semanal (domingo 04:00, default)" \
        2 "Diario a las 04:00" \
        3 "Cada 12 horas" \
        4 "Desactivar sincronización automática" \
        5 "Cancelar")

    case $OPCION in
        1) CRON="0 4 * * 0 $SYNC_SCRIPT" ;;
        2) CRON="0 4 * * * $SYNC_SCRIPT" ;;
        3) CRON="0 */12 * * * $SYNC_SCRIPT" ;;
        4) CRON="" ;;
        5) return ;;
    esac

    (crontab -l 2>/dev/null | grep -v "$SYNC_SCRIPT"; [ -n "$CRON" ] && echo "$CRON") | crontab -
    dialog --msgbox "Configuración de sincronización actualizada." 7 50
    log_action "INFO" "CRON de sincronización actualizado (opción $OPCION)"
}

ver_backups() {
    if [ ! -d "$BACKUP_DIR" ]; then
        dialog --msgbox "El directorio de backups no existe: $BACKUP_DIR" 7 60
        log_action "ERROR" "Intento de ver backups fallido: directorio inexistente."
        return
    fi

    if ! ls "$BACKUP_DIR"/*.tar.gz &>/dev/null; then
        dialog --msgbox "No se encontraron archivos de respaldo." 7 50
        log_action "INFO" "No se encontraron backups en $BACKUP_DIR"
        return
    fi

    dialog --title "Últimos respaldos" --textbox <(ls -lh "$BACKUP_DIR"/*.tar.gz | tail -n 15) 25 80
    log_action "INFO" "Mostrados últimos backups."
}

ver_logs() {
    if [ ! -f "$LOGFILE" ]; then
        dialog --msgbox "No se encontró el archivo de logs: $LOGFILE" 7 50
        return
    fi
    dialog --tailbox "$LOGFILE" 30 80
}

# ======================
# Menú principal
# ======================
CHOICE=0
while [ "$CHOICE" != "7" ]; do
    CHOICE=$(dialog --clear --stdout --title "Gestión de Respaldos - JurassiDraft" \
        --menu "Selecciona una opción:" 18 60 7 \
        1 "Hacer backup manual" \
        2 "Ver últimos backups" \
        3 "Sincronizar manualmente" \
        4 "Configurar cron (backups)" \
        5 "Configurar cron (sync remoto)" \
        6 "Ver logs" \
        7 "Salir")

    case $CHOICE in
        1)
            bash "$BACKUP_SCRIPT"
            dialog --msgbox "Backup ejecutado. Revisar /home/jurassiuser/backups" 7 60
            log_action "INFO" "Backup manual ejecutado desde el panel."
            ;;
        2)
            ver_backups
            ;;
        3)
            bash "$SYNC_SCRIPT"
            dialog --msgbox "Sincronización ejecutada. Verificar log para más detalles." 7 60
            log_action "INFO" "Sincronización manual ejecutada desde el panel."
            ;;
        4)
            configurar_cron_backups
            ;;
        5)
            configurar_cron_sync
            ;;
        6)
            ver_logs
            ;;
        7)
            log_action "INFO" "Panel de gestión de respaldos cerrado por el usuario."
            ;;
        *)
            break
            ;;
    esac
done

clear
