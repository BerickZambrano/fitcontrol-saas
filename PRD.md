# Product Requirements Document (PRD) - FitControl SaaS

## 1. Visión General del Proyecto
**FitControl** es una plataforma SaaS (Software as a Service) multi-tenant diseñada para la gestión integral de clubes deportivos, academias y equipos. Permite administrar todas las operaciones del club, desde la gestión de usuarios (jugadores, entrenadores, administradores) hasta la planificación de entrenamientos, partidos, historiales médicos, rendimiento y pagos.

El proyecto está diseñado de forma modular, con paneles de control diferenciados según el rol del usuario, y cuenta con soporte para ser empaquetado como aplicación móvil mediante Capacitor.

## 2. Stack Tecnológico
El proyecto utiliza un stack moderno basado en el ecosistema de Laravel y tecnologías de frontend reactivas:

### Backend
- **Framework:** Laravel 12.0 (PHP ^8.2)
- **Panel de Administración:** Filament v4.0 (con Livewire v3 / Flux v2.9)
- **Base de Datos:** MySQL / PostgreSQL (soportado por Laravel), con Redis (Predis ^3.4) para caché y colas.
- **Búsqueda Full-Text:** Laravel Scout con soporte para Algolia (algoliasearch-client-php) y Meilisearch.

### Frontend
- **Landing y Onboarding:** Inertia.js v2.0 con React (@inertiajs/react).
- **Estilos:** Tailwind CSS v4.2 (con PostCSS y Autoprefixer).
- **Gráficos:** Filament Apex Charts v5.0.
- **Empaquetador de Assets:** Vite v7.0 (con laravel-vite-plugin y @vitejs/plugin-react).

### Móvil
- **Framework Cross-Platform:** Capacitor v6 (@capacitor/core, @capacitor/android, @capacitor/app).
- La aplicación web sirve como base para una app móvil nativa de Android, configurada para conectarse a la URL de la aplicación web (`capacitor.config.json`).

### Infraestructura y Desarrollo
- **Contenedores:** Docker y Laravel Sail (`docker-compose.yml`, `Dockerfile`).
- **Análisis de Código / Testing:** PHPUnit v11.5, Laravel Pint, Laravel Pail.
- **Exportaciones:** Filament Export v4.0 y Maatwebsite Excel v3.1.

## 3. Arquitectura y Multi-Tenancy
El núcleo del sistema se basa en una arquitectura **Multi-Tenant** donde la entidad central es el `Tenant` (que representa a un Club, Academia o Institución).
- Cada `User` pertenece a un `Tenant` (relación `tenant_id` en el modelo User).
- Toda la información operativa (equipos, jugadores, entrenamientos) está lógicamente aislada por `Tenant`.
- Flujo de creación de Tenant: Existe un proceso de *Onboarding* (`/onboarding`) y solicitud de acceso (`/solicitar-acceso`) que permite a nuevos clubes registrarse y configurar su entorno. Además, los administradores reciben invitaciones mediante tokens (`/register-admin/{token}`).

## 4. Roles y Paneles (Filament)
El sistema utiliza **Spatie Permission** integrado con **Filament Shield** para el manejo de roles y permisos granulares. Los accesos están divididos en tres paneles principales (Providers de Filament):

1. **Admin Panel (`AdminPanelProvider.php`):**
   - **Roles permitidos:** `Administrador`, `super_admin`.
   - **Propósito:** Gestión global del club (tenant), configuración, cobros (pagos), creación de equipos, instalaciones, y personal.

2. **Entrenador Panel (`EntrenadorPanelProvider.php`):**
   - **Roles permitidos:** `Entrenador`.
   - **Propósito:** Gestión deportiva directa. Planificación de entrenamientos, toma de asistencia, evaluación de rendimiento, y tácticas de los partidos.

3. **Jugador Panel (`JugadorPanelProvider.php`):**
   - **Roles permitidos:** `Jugador`.
   - **Propósito:** Vista de usuario final. Permite a los jugadores ver sus próximos entrenamientos, partidos, métricas de rendimiento, notificaciones y pagos pendientes.

## 5. Modelos de Datos (Entidades Core)
El dominio del sistema se modela con las siguientes entidades principales ubicadas en `app/Models`:

- **User:** Gestiona la autenticación. Implementa `HasRoles`, `Searchable` (para Algolia/Meilisearch), y SoftDeletes. Soporta autenticación de doble factor (2FA) mediante OTP (One-Time Password).
- **Tenant:** Entidad del club u organización.
- **Equipo & EquipoUser:** Definición de los equipos del club y la tabla pivote para asignar usuarios (entrenadores y jugadores) a los equipos.
- **JugadorPerfil:** Relación 1:1 con el `User` (si es jugador). Almacena métricas físicas, posición, y estadísticas específicas.
- **Entrenamiento & AsistenciaEntrenamiento:** Planificación de sesiones de práctica. Registro de quién asistió o faltó a cada sesión.
- **Partido:** Registro de encuentros oficiales o amistosos, alineaciones y resultados.
- **Rendimiento:** Métricas y evaluaciones de los jugadores en entrenamientos o partidos (estadísticas, notas, observaciones).
- **HistorialMedico:** Seguimiento de lesiones, tratamientos y altas médicas de los jugadores.
- **Instalacion:** Gestión de canchas, gimnasios o espacios físicos que el club utiliza para sus actividades.
- **Torneo:** Agrupación de partidos bajo una competición específica.
- **Notificacion:** Sistema de alertas y comunicados internos del club hacia sus miembros.
- **Pago:** Módulo financiero para controlar cuotas, mensualidades u otros cobros a los jugadores.
- **GeneratedReport:** Archivos generados asíncronamente (Excel/PDF) con estadísticas exportadas de Filament.

## 6. Seguridad y Autenticación
- **2FA (Two-Factor Authentication):** El modelo de usuario incluye `two_factor_type`, `two_factor_otp`, y expiración de OTP. La ruta `/2fa/verify` gestiona el flujo (basado en Livewire).
- **Caché de Sesión/Roles:** El sistema borra proactivamente la caché de la información y roles del usuario (`clearUserInfoCache`) ante cualquier cambio, para evitar privilegios obsoletos o datos entre tenants (cross-tenant leaks).

## 7. Scripts y Flujos de Ejecución
En el `package.json` y `composer.json` se definen comandos clave para el entorno local:
- `npm run dev` y `php artisan serve` se ejecutan en paralelo junto con `php artisan queue:listen` usando `concurrently` (comando `composer dev`).
- El proyecto depende fuertemente de colas (Jobs/Queues) para el envío de correos (ej. OTP) y generación de reportes pesados.

## 8. Notas Adicionales para Agentes IA
- **Si vas a crear/modificar UI de Filament:** Ten en cuenta que el proyecto usa **Filament v4**. Asegúrate de generar los Resources/Pages en el provider correcto (`Admin`, `Entrenador` o `Jugador`).
- **Si vas a tocar el Frontend Público:** La landing page y el onboarding están en **Inertia + React**.
- **Móvil:** Cualquier cambio en CSS/JS debe ser compatible con la vista web de Capacitor en dispositivos Android.
- **Multitenancy:** Siempre que crees un modelo nuevo, verifica si necesita pertenecer a un `Tenant` (usando un trait como `BelongsToTenant` o scope global) para mantener el aislamiento de datos.
