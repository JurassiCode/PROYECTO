# 🦖 JurassiCode – JurassiDraft

Este proyecto es un sistema web de gestión de partidas del juego Draftosaurus, desarrollado en **Laravel** con **Vite + TailwindCSS** en el frontend y **MySQL** como base de datos.  
Permite registrar usuarios, crear partidas, gestionar rondas y turnos, y visualizar resultados.

---

## ⚠️ Pre-requisitos

Antes de iniciar o instalar el proyecto, asegurate de tener instalado:

- PHP 8.2 o superior (Podría venir de XAMPP, entre otros)
- Composer
- Node.js 20 o superior
- NPM (Se debería instalar junto con Node.js)
- MySQL o MariaDB (XAMPP, WAMP, MAMP, etc.)
- Git

---

## ⚙️ Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/JurassiCode/PROYECTO.git
cd PROYECTO
```

### 2. Instalar dependencias

```bash
composer install
npm install
```

### 3. Crear archivo `.env`

#### 🪟 Windows (PowerShell o CMD)

```powershell
copy .env.example .env
```

### 🐧 Linux/Mac

```bash
cp .env.example .env
```

Editar el archivo `.env` para configurar la base de datos. La que viene por defecto es MySQL, pero también se puede usar MariaDB.

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_de_tu_base_de_datos
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

### 4. Generar clave de aplicación

```bash
php artisan key:generate
```

### 5. Dumpear la base de datos

Desde phpMyAdmin o Workbench, importar el archivo `db/draftosaurus.sql`

---

## ✅ Uso

```bash
composer run dev #inicia tanto el servidor de Laravel como el de Vite (necesario para TailwindCSS, entre otros)
```

Acceder a `http://127.0.0.1:8000`

---

## 📝 Notas

Luego de haber iniciado el proyecto, en `/documentacion` se encuentra la documentación de las tres entregas por separado, indexadas y prolijas.
