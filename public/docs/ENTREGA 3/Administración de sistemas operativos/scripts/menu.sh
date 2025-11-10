#!/bin/bash

# ======================
# Verificación de superusuario
# ======================
if [ "$EUID" -ne 0 ]; then
    dialog --title "Permisos insuficientes" \
           --msgbox "Este script debe ejecutarse como root o con sudo." 8 60
    clear
    exit 1
fi

# ======================
# Variables
# ======================
BASE_DIR="/home/jurassiuser/scripts"
LOG_DIR="/var/log/jurassidraft"
LOGFILE="$LOG_DIR/main.log"

# Rutas a los módulos
DOCKER_SCRIPT="$BASE_DIR/docker/docker.sh"
FIREWALL_SCRIPT="$BASE_DIR/firewall/firewall.sh"
BACKUPS_SCRIPT="$BASE_DIR/backups/backups_menu.sh"
USERS_SCRIPT="$BASE_DIR/usuarios_grupos/principal.sh"

mkdir -p "$LOG_DIR"
touch "$LOGFILE"
chmod 664 "$LOGFILE"

# ======================
# Función de logging
# ======================
log_action() {
    local level="$1"
    local message="$2"
    local user
    user=$(whoami)
    echo "$(date '+%Y-%m-%d %H:%M:%S') [$level] ($user) $message" >> "$LOGFILE"
}

# ======================
# Verificación de módulos
# ======================
check_modules() {
    local missing=0
    for file in "$DOCKER_SCRIPT" "$FIREWALL_SCRIPT" "$BACKUPS_SCRIPT" "$USERS_SCRIPT"; do
        if [ ! -f "$file" ]; then
            echo "Falta el módulo: $file" >> "$LOGFILE"
            missing=1
        fi
    done

    if [ $missing -eq 1 ]; then
        dialog --title "Error" \
               --msgbox "Faltan uno o más módulos necesarios. Revisá los logs." 8 60
        exit 1
    fi
}

check_modules

# ======================
# Ver todos los logs
# ======================
ver_logs() {
    opcion=$(dialog --stdout --menu "Selecciona qué logs consultar:" 15 60 7 \
        1 "Logs de Docker" \
        2 "Logs del Firewall" \
        3 "Logs de Respaldos" \
        4 "Logs de Usuarios y Grupos" \
        5 "Logs del Panel Principal" \
        6 "Ver todos juntos" \
        7 "Volver")

    case $opcion in
        1) dialog --tailbox "$LOG_DIR/docker.log" 30 90 ;;
        2) dialog --tailbox "$LOG_DIR/firewall.log" 30 90 ;;
        3) dialog --tailbox "$LOG_DIR/respaldos.log" 30 90 ;;
        4) dialog --tailbox "$LOG_DIR/usuarios.log" 30 90 ;;
        5) dialog --tailbox "$LOGFILE" 30 90 ;;
        6)
            cat "$LOG_DIR"/*.log > /tmp/all_jurassilogs.txt
            dialog --textbox /tmp/all_jurassilogs.txt 35 100
            rm -f /tmp/all_jurassilogs.txt
            ;;
        7) return ;;
    esac
}

# ======================
# Menú principal
# ======================
loop=1
while [ $loop -eq 1 ]; do
    opcion=$(dialog --clear --stdout \
        --title "Panel Principal - JurassiServer" \
        --menu "Selecciona un módulo:" 18 60 7 \
        1 "Gestionar Docker" \
        2 "Gestionar Firewall" \
        3 "Gestionar Respaldos" \
        4 "Gestionar Usuarios y Grupos" \
        5 "Ver Logs del Sistema" \
        6 "Salir")

    case $opcion in
        1)
            log_action "INFO" "Ingreso al módulo Docker"
            bash "$DOCKER_SCRIPT"
            ;;
        2)
            log_action "INFO" "Ingreso al módulo Firewall"
            bash "$FIREWALL_SCRIPT"
            ;;
        3)
            log_action "INFO" "Ingreso al módulo Respaldos"
            bash "$BACKUPS_SCRIPT"
            ;;
        4)
            log_action "INFO" "Ingreso al módulo Usuarios y Grupos"
            bash "$USERS_SCRIPT"
            ;;
        5)
            ver_logs
            ;;
        6)
            log_action "INFO" "Panel principal cerrado por el usuario"
            loop=0
            ;;
    esac
done

clear
log_action "INFO" "Ejecución del panel principal finalizada."
