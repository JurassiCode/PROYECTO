#!/bin/bash

# Verificar que dialog esté instalado
if ! command -v dialog &> /dev/null; then
    echo "dialog no está instalado. Instalalo con: sudo dnf install -y dialog"
    exit 1
fi

# ======================
# Funciones utilitarias
# ======================

select_mode() {
    modo=$(dialog --menu "Seleccioná el modo de las reglas" 12 60 2 \
        permanent "Persistente (se guarda al reiniciar)" \
        runtime   "Temporal (se borra al reiniciar)" \
        --stdout)

    if [[ -z "$modo" ]]; then
        return 1
    fi
}

apply_cmd() {
    local cmd="$1"
    case "$modo" in
        permanent) eval "$cmd --permanent" ;;
        runtime)   eval "$cmd" ;;
    esac
}

# ======================
# Función 1 - Cambiar zona de una interfaz
# ======================

cambiar_zona_interfaz() {
    zonas=$(firewall-cmd --get-zones)
    zona=$(dialog --menu "Zonas disponibles" 15 50 10 $(for z in $zonas; do echo "$z" "$z"; done) --stdout) || return
    interfaz=$(dialog --inputbox "Nombre de la interfaz (ej: enp0s3):" 8 40 --stdout) || return
    select_mode || return
    apply_cmd "firewall-cmd --zone=$zona --change-interface=$interfaz"
    firewall-cmd --reload
    dialog --msgbox "Zona $zona aplicada a interfaz $interfaz en modo $modo" 7 50
}

# ======================
# Función 2 - Bloquear todo desde IP/red
# ======================

bloquear_ip_total() {
    ip=$(dialog --inputbox "IP o red a BLOQUEAR (ej: 192.168.1.100 o 192.168.1.0/24):" 8 45 --stdout) || return
    select_mode || return
    apply_cmd "firewall-cmd --remove-rich-rule='rule family=ipv4 source address=$ip drop'" 2>/dev/null
    apply_cmd "firewall-cmd --add-rich-rule='rule family=ipv4 source address=$ip drop'"
    firewall-cmd --reload
    dialog --msgbox "Tráfico desde $ip BLOQUEADO en modo $modo" 7 60
}

# ======================
# Función 3 - Desbloquear todo desde IP/red
# ======================

desbloquear_ip_total() {
    ip=$(dialog --inputbox "IP o red a DESBLOQUEAR (ej: 192.168.1.100 o 192.168.1.0/24):" 8 45 --stdout) || return
    select_mode || return
    apply_cmd "firewall-cmd --remove-rich-rule='rule family=ipv4 source address=$ip drop'"
    firewall-cmd --reload
    dialog --msgbox "Tráfico desde $ip DESBLOQUEADO en modo $modo" 7 60
}

# ======================
# Función 4 - Permitir o bloquear puerto para IP/red
# ======================

puerto_por_ip() {
    ip=$(dialog --inputbox "IP o red (ej: 192.168.1.100 o 192.168.1.0/24):" 8 45 --stdout) || return
    puerto=$(dialog --inputbox "Puerto (ej: 22, 80, 3306):" 8 40 --stdout) || return
    protocolo=$(dialog --menu "Protocolo" 10 40 2 tcp "TCP" udp "UDP" --stdout) || return
    accion=$(dialog --menu "Acción sobre $puerto/$protocolo para $ip" 10 50 2 allow "Permitir" deny "Bloquear" --stdout) || return
    select_mode || return

    # 🔥 Eliminar reglas conflictivas previas
    apply_cmd "firewall-cmd --remove-rich-rule='rule family=ipv4 source address=$ip port port=$puerto protocol=$protocolo accept'" 2>/dev/null
    apply_cmd "firewall-cmd --remove-rich-rule='rule family=ipv4 source address=$ip port port=$puerto protocol=$protocolo drop'" 2>/dev/null

    # ✅ Aplicar la nueva regla
    if [ "$accion" = "allow" ]; then
        cmd="firewall-cmd --add-rich-rule='rule family=ipv4 source address=$ip port port=$puerto protocol=$protocolo accept'"
    else
        cmd="firewall-cmd --add-rich-rule='rule family=ipv4 source address=$ip port port=$puerto protocol=$protocolo drop'"
    fi

    apply_cmd "$cmd"
    firewall-cmd --reload
    dialog --msgbox "Regla $accion aplicada para $ip:$puerto/$protocolo en modo $modo (conflictos eliminados)" 8 60
}

# ======================
# MENÚ PRINCIPAL
# ======================

loop=1
while [ $loop -eq 1 ]; do
    opcion=$(dialog --clear --stdout \
        --title "🔥 FirewallD - Panel Interactivo (JurassiCode)" \
        --menu "Seleccioná una acción:" 20 60 10 \
        1 "Cambiar zona de interfaz" \
        2 "Bloquear TODO desde IP/red" \
        3 "Desbloquear TODO desde IP/red" \
        4 "Permitir o bloquear puerto por IP/red" \
        5 "Salir")

    case $opcion in
        1) cambiar_zona_interfaz ;;
        2) bloquear_ip_total ;;
        3) desbloquear_ip_total ;;
        4) puerto_por_ip ;;
        5) loop=0 ;;
        *) break ;;
    esac
done
