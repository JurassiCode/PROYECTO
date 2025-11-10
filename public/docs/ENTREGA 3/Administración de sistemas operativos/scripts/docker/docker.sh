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
PROJECT_DIR="/home/jurassiuser/jurassidraft"
COMPOSE_FILE="$PROJECT_DIR/docker-compose.yml"
LOGFILE="/var/log/jurassidraft/docker.log"

mkdir -p /var/log/jurassidraft
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
# Funciones principales
# ======================

levantar_todo() {
    log_action "INFO" "Intento de levantar todos los contenedores del proyecto."
    if docker compose -f "$COMPOSE_FILE" up -d 2>>"$LOGFILE"; then
        dialog --msgbox "Contenedores del proyecto levantados correctamente." 7 60
        log_action "OK" "Contenedores del proyecto levantados correctamente."
    else
        dialog --msgbox "Error al levantar los contenedores." 7 50
        log_action "ERROR" "Falló levantar todos los contenedores."
    fi
}

apagar_todo() {
    log_action "INFO" "Intento de apagar todos los contenedores del proyecto."
    if docker compose -f "$COMPOSE_FILE" down 2>>"$LOGFILE"; then
        dialog --msgbox "Contenedores del proyecto apagados correctamente." 7 60
        log_action "OK" "Contenedores del proyecto apagados correctamente."
    else
        dialog --msgbox "Error al apagar los contenedores." 7 50
        log_action "ERROR" "Falló apagar todos los contenedores."
    fi
}

levantar_mysql() {
    log_action "INFO" "Intento de levantar contenedor de base de datos (jurassidraft-mysql)."
    if docker start jurassidraft-mysql >>"$LOGFILE" 2>&1; then
        dialog --msgbox "Contenedor MySQL levantado correctamente." 7 50
        log_action "OK" "Contenedor jurassidraft-mysql levantado correctamente."
    else
        dialog --msgbox "Error al levantar el contenedor MySQL." 7 50
        log_action "ERROR" "Falló levantar el contenedor jurassidraft-mysql."
    fi
}

apagar_mysql() {
    log_action "INFO" "Intento de apagar contenedor de base de datos (jurassidraft-mysql)."
    if docker stop jurassidraft-mysql >>"$LOGFILE" 2>&1; then
        dialog --msgbox "Contenedor MySQL detenido correctamente." 7 50
        log_action "OK" "Contenedor jurassidraft-mysql detenido correctamente."
    else
        dialog --msgbox "Error al detener el contenedor MySQL." 7 50
        log_action "ERROR" "Falló detener el contenedor jurassidraft-mysql."
    fi
}

ver_logs_contenedores() {
    opcion=$(dialog --stdout --menu "Selecciona los logs a consultar:" 12 60 3 \
        1 "Logs del proyecto completo (docker compose)" \
        2 "Logs del contenedor MySQL" \
        3 "Logs del contenedor Laravel")

    TMPFILE=$(mktemp)

    case $opcion in
        1)
            log_action "INFO" "Consulta de logs de todo el proyecto (docker compose)."
            docker compose -f "$COMPOSE_FILE" logs --tail 100 > "$TMPFILE" 2>&1
            dialog --textbox "$TMPFILE" 30 90
            ;;
        2)
            log_action "INFO" "Consulta de logs del contenedor MySQL."
            docker logs jurassidraft-mysql --tail 100 > "$TMPFILE" 2>&1
            dialog --textbox "$TMPFILE" 30 90
            ;;
        3)
            log_action "INFO" "Consulta de logs del contenedor Laravel."
            docker logs jurassidraft-laravel --tail 100 > "$TMPFILE" 2>&1
            dialog --textbox "$TMPFILE" 30 90
            ;;
    esac

    rm -f "$TMPFILE"
}

# ======================
# Menú principal
# ======================
loop=1
while [ $loop -eq 1 ]; do
    opcion=$(dialog --clear --stdout \
        --title "Gestión de Docker - JurassiDraft" \
        --menu "Seleccione una acción:" 18 60 7 \
        1 "Levantar todos los contenedores" \
        2 "Apagar todos los contenedores" \
        3 "Levantar solo MySQL" \
        4 "Apagar solo MySQL" \
        5 "Ver logs de contenedores" \
        6 "Ver log del script" \
        7 "Salir")

    case $opcion in
        1) levantar_todo ;;
        2) apagar_todo ;;
        3) levantar_mysql ;;
        4) apagar_mysql ;;
        5) ver_logs_contenedores ;;
        6) dialog --tailbox "$LOGFILE" 30 80 ;;
        7)
            log_action "INFO" "Menú de gestión Docker cerrado por el usuario."
            loop=0
            ;;
    esac
done

clear
log_action "INFO" "Script de gestión Docker finalizado."
