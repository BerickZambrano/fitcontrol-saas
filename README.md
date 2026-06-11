# ⚽ FitControl — Plataforma SaaS Multi-Tenant para Gestión de Clubes Deportivos

<p align="center">
  <img src="public/images/logo.png" alt="FitControl Logo" width="180px" style="border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.3); margin-bottom: 20px;">
</p>

<p align="center">
  <strong>La solución definitiva para la administración integral de clubes deportivos, academias y equipos de alto rendimiento.</strong>
</p>

<p align="center">
  <a href="https://laravel.com"><img src="https://img.shields.io/badge/Laravel-12.0--FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12"></a>
  <a href="https://filamentphp.com"><img src="https://img.shields.io/badge/Filament-v4.0--FFA500?style=for-the-badge&logo=filament&logoColor=white" alt="Filament v4"></a>
  <a href="https://react.dev"><img src="https://img.shields.io/badge/React-2.0--61DAFB?style=for-the-badge&logo=react&logoColor=black" alt="React"></a>
  <a href="https://inertiajs.com"><img src="https://img.shields.io/badge/Inertia.js-v2.0--9553E6?style=for-the-badge&logo=inertia&logoColor=white" alt="Inertia.js"></a>
  <a href="https://tailwindcss.com"><img src="https://img.shields.io/badge/Tailwind_CSS-v4.2--38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS"></a>
  <a href="https://capacitorjs.com"><img src="https://img.shields.io/badge/Capacitor-v6.0--119EFF?style=for-the-badge&logo=capacitor&logoColor=white" alt="Capacitor v6"></a>
  <a href="https://www.docker.com"><img src="https://img.shields.io/badge/Docker--2496ED?style=for-the-badge&logo=docker&logoColor=white" alt="Docker"></a>
</p>

---

## 📖 Visión General

**FitControl** es una plataforma SaaS (Software as a Service) multi-tenant diseñada para unificar todas las operaciones de un club o academia deportiva. Ofrece aislamiento lógico de datos por club (Tenant) y dispone de paneles especializados para cada tipo de rol del ecosistema deportivo (Administradores, Entrenadores, Jugadores y Árbitros). 

El sistema está empaquetado para entornos web y cuenta con soporte móvil multiplataforma integrado a través de **Capacitor**.

---

## ✨ Características Principales

### 📦 Módulos del Sistema

* **Gestión Multi-Tenant:** Onboarding y flujo de solicitud de acceso (`/solicitar-acceso`) con carga de documentos oficiales (RUT, Cámara de Comercio). Paywall y control de suscripción en tiempo real (bloqueo automático/apertura con candado).
* **Gestión de Equipos y Jugadores:** Asignación de categorías (Formativo, Amateur, Profesional) y subcategorías. Fichas de perfiles físicos con dorsal, posición, pierna hábil, etc.
* **Control Deportivo Directo:**
  - **Entrenamientos:** Planificación, asignación por equipo y control de asistencia con toques rápidos (inline toggle).
  - **Competencias:** Creación de torneos y programación de partidos (local/visitante).
  - **Rendimiento:** Evaluaciones numéricas (0-10) del staff hacia los jugadores, minutos jugados, goles, tarjetas y reportes exportables.
* **Historial Médico:** Gestión de lesiones, clasificaciones de gravedad, control médico e indicador de aptitud física de jugadores.
* **Módulo Financiero (Pagos):** Control de mensualidades, estado de cobros (pendiente, pagado, rechazado) en pesos colombianos (COP).
* **Calendario Unificado:** Visualización interactiva codificada por colores (Azul para entrenamientos, Verde para partidos).
* **Dashboard e Informes:** Gráficos robustos implementados con Apex Charts (analíticas de asistencia, usuarios por rol, torneos activos, y balances de ingresos).
* **Seguridad Avanzada (2FA):** Autenticación de doble factor mediante OTP enviadas por correo electrónico.

---

## 🛠️ Stack Tecnológico

| Componente | Tecnología |
| :--- | :--- |
| **Framework Backend** | Laravel 12.0 (PHP ^8.2 / 8.3) |
| **Panel de Control** | Filament v4.0 (Livewire v3 / Flux v2.9) |
| **Base de Datos** | MySQL / PostgreSQL |
| **Caché y Colas** | Redis (Predis ^3.4) |
| **Búsqueda Full-Text** | Laravel Scout (Soporte Algolia y Meilisearch) |
| **Frontend Público** | Inertia.js v2.0 + React (@inertiajs/react) |
| **Diseño y Estilos** | Tailwind CSS v4.2 |
| **Visualización Gráfica** | Filament Apex Charts v5.0 |
| **Compilación Móvil** | Capacitor v6 (@capacitor/core, @capacitor/android) |
| **Contenedores** | Docker & Laravel Sail |

---

## 🏗️ Arquitectura de Paneles (Filament)

La plataforma distribuye el acceso de forma segura e independiente según el rol del usuario:

| Panel | Ruta | Tema Principal | Audiencia y Propósito |
| :--- | :--- | :--- | :--- |
| **Admin** | `/admin` | Azul | Gestión total del club, finanzas, reportes, alta de personal y configuración general. |
| **Entrenador** | `/entrenador` | Esmeralda (Verde) | Programación deportiva, tácticas, control de asistencia y rendimiento. |
| **Jugador** | `/jugador` | Rojo | Perfil personal, estadísticas de rendimiento, notificaciones y estado de pagos. |
| **Árbitro** | `/arbitro` | Ámbar | Programación y reportes arbitrales de competencias. |

---

## 🔐 Roles y Permisos (RBAC)

Integrado mediante **Spatie Permission** y **Filament Shield**:
* `super_admin`: Acceso global para soporte y aprobación de nuevos clubes en la plataforma SaaS.
* `Administrador`: Rol de dueño de club. Acceso completo al aislamiento de su Tenant.
* `Entrenador`: Gestiona equipos, entrenamientos, partidos y rendimientos de sus categorías asignadas.
* `Jugador`: Vista limitada a sus propias analíticas y notificaciones.
* `Arbitro`: Gestión de partidos asignados en torneos.

---

## 🚀 Instalación y Configuración Local

### Requisitos Previos

* Docker & Docker Compose
* Node.js (v18+)
* PHP ^8.2 o superior (si se corre local sin contenedor)
* Composer (v2+)

### Opción A: Levantar con Docker (Recomendado)

1. **Clonar el repositorio:**
   ```bash
   git clone https://github.com/tu-usuario/fitcontrol.git
   cd fitcontrol
   ```

2. **Copiar variables de entorno:**
   ```bash
   cp .env.example .env
   ```

3. **Iniciar los contenedores con Sail:**
   ```bash
   ./vendor/bin/sail up -d
   ```

4. **Instalar dependencias e iniciar base de datos:**
   ```bash
   ./vendor/bin/sail composer install
   ./vendor/bin/sail php artisan key:generate
   ./vendor/bin/sail php artisan migrate --seed
   ./vendor/bin/sail npm install
   ./vendor/bin/sail npm run build
   ```

### Opción B: Levantar Localmente (Sin Docker)

1. **Instalar dependencias de PHP y Node:**
   ```bash
   composer install
   npm install
   ```

2. **Configurar el entorno:**
   * Crea el archivo `.env` a partir de `.env.example`.
   * Genera la clave de la aplicación:
     ```bash
     php artisan key:generate
     ```
   * Modifica los accesos a la base de datos en tu `.env`.

3. **Ejecutar migraciones y seeders:**
   ```bash
   php artisan migrate --seed
   ```

4. **Iniciar el servidor de desarrollo en paralelo:**
   ```bash
   composer dev
   ```
   *Este comando levantará concurrentemente el servidor web (`php artisan serve`), las colas de Redis/Database (`php artisan queue:listen`), logs interactivos (`php artisan pail`) y el compilador de assets frontend (`npm run dev`).*

---

## 📱 Compilación Móvil (Capacitor)

El proyecto está configurado para exportar su frontend responsivo a una app móvil nativa de Android:

1. **Compilar los archivos estáticos de React:**
   ```bash
   npm run build
   ```

2. **Sincronizar assets con Capacitor:**
   ```bash
   npx cap sync
   ```

3. **Abrir el proyecto en Android Studio:**
   ```bash
   npx cap open android
   ```

4. **Configuración de Host:**
   Asegúrate de configurar la URL del servidor web en el archivo `capacitor.config.json` para conectarse remotamente o apuntar a tu localhost.

---

## 🧪 Ejecución de Pruebas

Para ejecutar las pruebas unitarias y de integración dentro del entorno de desarrollo:

* **Con Docker/Sail:**
  ```bash
  ./vendor/bin/sail php artisan test
  ```
* **Localmente:**
  ```bash
  php artisan test
  ```

---

## 📝 Licencia

Este proyecto es software privado y propietario. Todos los derechos reservados.
