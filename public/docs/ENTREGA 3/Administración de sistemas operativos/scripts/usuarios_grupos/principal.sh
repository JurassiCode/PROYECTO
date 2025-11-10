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
LOGFILE="/var/log/jurassidraft/usuarios.log"
BASE_DIR="/home/jurassiuser/scripts/usuarios_grupos"
USUARIOS_SCRIPT="$BASE_DIR/usuarios.sh"
GRUPOS_SCRIPT="$BASE_DIR/grupos.sh"

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
# Validación de módulos
# ======================
if [ ! -f "$USUARIOS_SCRIPT" ] || [ ! -f "$GRUPOS_SCRIPT" ]; then
    dialog --title "Error" \
           --msgbox "No se encontraron los módulos requeridos (usuarios.sh / grupos.sh)." 8 60
    clear
    log_action "ERROR" "Faltan módulos requeridos para gestión de cuentas."
    exit 1
fi

# Cargar módulos
source "$USUARIOS_SCRIPT"
source "$GRUPOS_SCRIPT"

# ======================
# Menú principal
# ======================
loop=1
while [ $loop -eq 1 ]; do
    opcion=$(dialog --clear --stdout \
        --title "Gestión de Cuentas del Sistema - JurassiDraft" \
        --menu "Seleccione una opción:" 15 60 6 \
        1 "Gestionar usuarios" \
        2 "Gestionar grupos" \
        3 "Ver logs" \
        4 "Salir")

    case $opcion in
        1)
            log_action "INFO" "Acceso al módulo de gestión de usuarios."
            menu_usuarios
            ;;
        2)
            log_action "INFO" "Acceso al módulo de gestión de grupos."
            menu_grupos
            ;;
        3)
            dialog --tailbox "$LOGFILE" 30 80
            ;;
        4)
            log_action "INFO" "Panel de gestión de cuentas cerrado por el usuario."
            loop=0
            ;;
    esac
done

clear
