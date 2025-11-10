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

agregar_usuario() {
    usuario=$(dialog --stdout --inputbox "Nombre del nuevo usuario:" 8 40)
    if [ -z "$usuario" ]; then
        dialog --msgbox "Operación cancelada." 6 40
        return
    fi

    if grep -q "^$usuario:" /etc/passwd; then
        dialog --msgbox "El usuario '$usuario' ya existe." 6 40
        log_action "WARN" "Intento de crear usuario existente: $usuario"
        return
    fi

    home_dir=$(dialog --stdout --inputbox "Carpeta home del usuario (default: /home/$usuario):" 8 50)
    [[ -z "$home_dir" ]] && home_dir="/home/$usuario"

    passwd=$(dialog --stdout --insecure --passwordbox "Contraseña del usuario:" 8 40)
    if [ -z "$passwd" ]; then
        dialog --msgbox "No se ingresó contraseña. Operación cancelada." 6 50
        return
    fi

    # Obtener lista de grupos para selección
    grupos=()
    grupos+=("0" "Usar grupo por defecto (nombre del usuario)")
    while IFS=: read -r grupo _; do
        grupos+=("$grupo" "$grupo")
    done < /etc/group

    grupo_primario=$(dialog --stdout --menu "Seleccione grupo primario para el usuario:" 20 50 15 "${grupos[@]}")

    # Crear usuario
    if [[ "$grupo_primario" == "0" || -z "$grupo_primario" ]]; then
        useradd -m -d "$home_dir" "$usuario" 2>/dev/null
    else
        useradd -m -d "$home_dir" -g "$grupo_primario" "$usuario" 2>/dev/null
    fi

    if [ $? -eq 0 ]; then
        echo "$usuario:$passwd" | chpasswd
        dialog --msgbox "Usuario '$usuario' creado con éxito." 6 50
        log_action "INFO" "Usuario creado: $usuario (home: $home_dir, grupo: ${grupo_primario:-default})"
    else
        dialog --msgbox "Error al crear el usuario '$usuario'." 6 50
        log_action "ERROR" "Fallo al crear usuario: $usuario"
    fi
}

eliminar_usuario() {
    usuario=$(dialog --stdout --inputbox "Nombre del usuario a eliminar:" 8 40)
    if [ -z "$usuario" ]; then
        dialog --msgbox "Operación cancelada." 6 40
        return
    fi

    if grep -q "^$usuario:" /etc/passwd; then
        if userdel -r "$usuario" 2>/dev/null; then
            dialog --msgbox "Usuario '$usuario' eliminado correctamente." 6 50
            log_action "INFO" "Usuario eliminado: $usuario"
        else
            dialog --msgbox "Error al eliminar el usuario '$usuario'." 6 50
            log_action "ERROR" "Fallo al eliminar usuario: $usuario"
        fi
    else
        dialog --msgbox "El usuario '$usuario' no existe." 6 40
        log_action "WARN" "Intento de eliminar usuario inexistente: $usuario"
    fi
}

listar_usuarios() {
    dialog --title "Usuarios del sistema" --textbox <(cut -d: -f1 /etc/passwd | sort) 20 50
    log_action "INFO" "Listado de usuarios mostrado al usuario."
}

# ======================
# Menú principal
# ======================
menu_usuarios() {
    loop=1
    while [ $loop -eq 1 ]; do
        opcion=$(dialog --clear --stdout \
            --title "Gestión de Usuarios - JurassiDraft" \
            --menu "Seleccione una acción:" 15 50 6 \
            1 "Agregar usuario" \
            2 "Eliminar usuario" \
            3 "Listar usuarios" \
            4 "Ver logs" \
            5 "Volver al menú principal")

        case $opcion in
            1) agregar_usuario ;;
            2) eliminar_usuario ;;
            3) listar_usuarios ;;
            4) dialog --tailbox "$LOGFILE" 30 80 ;;
            5)
                log_action "INFO" "Menú de usuarios cerrado por el usuario."
                loop=0
                ;;
        esac
    done
}

# ======================
# Ejecución
# ======================
log_action "INFO" "Menú de gestión de usuarios iniciado."
menu_usuarios
log_action "INFO" "Script de gestión de usuarios finalizado."
clear
