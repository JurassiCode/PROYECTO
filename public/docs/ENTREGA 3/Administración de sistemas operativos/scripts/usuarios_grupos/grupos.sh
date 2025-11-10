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
LOGFILE="/var/log/jurassidraft/usuarios.log"

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

agregar_grupo() {
    grupo=$(dialog --stdout --inputbox "Nombre del nuevo grupo:" 8 40)
    if [ -z "$grupo" ]; then
        dialog --msgbox "Operación cancelada." 6 40
        return
    fi

    if grep -q "^$grupo:" /etc/group; then
        dialog --msgbox "El grupo '$grupo' ya existe." 6 40
        log_action "WARN" "Intento de crear grupo existente: $grupo"
    else
        if groupadd "$grupo" 2>/dev/null; then
            dialog --msgbox "Grupo '$grupo' creado correctamente." 6 50
            log_action "INFO" "Grupo creado: $grupo"
        else
            dialog --msgbox "Error al crear el grupo '$grupo'." 6 50
            log_action "ERROR" "Fallo al crear grupo: $grupo"
        fi
    fi
}

eliminar_grupo() {
    grupo=$(dialog --stdout --inputbox "Nombre del grupo a eliminar:" 8 40)
    if [ -z "$grupo" ]; then
        dialog --msgbox "Operación cancelada." 6 40
        return
    fi

    if grep -q "^$grupo:" /etc/group; then
        if groupdel "$grupo" 2>/dev/null; then
            dialog --msgbox "Grupo '$grupo' eliminado correctamente." 6 50
            log_action "INFO" "Grupo eliminado: $grupo"
        else
            dialog --msgbox "Error al eliminar el grupo '$grupo'." 6 50
            log_action "ERROR" "Fallo al eliminar grupo: $grupo"
        fi
    else
        dialog --msgbox "El grupo '$grupo' no existe." 6 40
        log_action "WARN" "Intento de eliminar grupo inexistente: $grupo"
    fi
}

listar_grupos() {
    dialog --title "Grupos del sistema" --textbox <(cut -d: -f1 /etc/group | sort) 20 50
    log_action "INFO" "Listado de grupos mostrado al usuario."
}

# ======================
# Menú principal
# ======================
menu_grupos() {
    loop=1
    while [ $loop -eq 1 ]; do
        opcion=$(dialog --clear --stdout \
            --title "Gestión de Grupos - JurassiDraft" \
            --menu "Seleccione una acción:" 15 50 6 \
            1 "Agregar grupo" \
            2 "Eliminar grupo" \
            3 "Listar grupos" \
            4 "Ver logs" \
            5 "Volver al menú principal")

        case $opcion in
            1) agregar_grupo ;;
            2) eliminar_grupo ;;
            3) listar_grupos ;;
            4) dialog --tailbox "$LOGFILE" 30 80 ;;
            5) 
                log_action "INFO" "Menú de grupos cerrado por el usuario."
                loop=0
                ;;
        esac
    done
}

# ======================
# Ejecución
# ======================
log_action "INFO" "Menú de gestión de grupos iniciado."
menu_grupos
log_action "INFO" "Script de gestión de grupos finalizado."
clear
