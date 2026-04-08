# FitControl — Documento de Requisitos (Ingeniería Inversa)

---

## 1. DESCRIPCIÓN GENERAL DEL PROYECTO

**FitControl** es una **plataforma SaaS multiinquilino** construida con Laravel 12 + Filament Admin, diseñada para la gestión de clubes deportivos (orientado al fútbol/soccer). Proporciona paneles basados en roles para administradores y jugadores, cubriendo equipos, entrenamientos, partidos, torneos, pagos, instalaciones y registros médicos.

**Stack Tecnológico:**
- Backend: Laravel 12 (PHP 8.x)
- Panel Admin: Filament v4
- Frontend: React + Inertia.js + Vite
- RBAC: Spatie Laravel-Permission + FilamentShield
- Gráficos: Filament Apex Charts Plugin
- Base de datos: SQLite (por defecto), soporta MySQL/PostgreSQL
- Colas: Driver de base de datos
- Sesiones: Driver de base de datos

---

## 2. REQUISITOS FUNCIONALES

### RF-1: Registro y Aprobación de Inquilinos (Club)

| ID | Requisito |
|----|-----------|
| RF-1.1 | Cualquier visitante puede enviar una **solicitud de registro de club** vía `/solicitar-acceso`, proporcionando: nombre del club, NIT, tipo de club (formativo/amateur/profesional), ciudad, país, email corporativo y datos del encargado. |
| RF-1.2 | Al enviar, el inquilino se crea con estado `pendiente`. |
| RF-1.3 | El sistema notifica a todos los usuarios con rol `admin` sobre la nueva solicitud pendiente. |
| RF-1.4 | Un usuario **super_admin** puede ver todas las solicitudes pendientes en la página **TenantRequests** (`/admin`). |
| RF-1.5 | El super_admin puede **aprobar** una solicitud: cambia el estado a `activo`, genera un `register_token` UUID y envía un correo de aprobación con una URL de registro única (`/register-admin/{token}`). |
| RF-1.6 | El super_admin puede **rechazar** una solicitud: cambia el estado a `suspendido` y envía un correo de rechazo. |
| RF-1.7 | El administrador designado del inquilino aprobado puede registrar su cuenta vía la URL con token (`/register-admin/{token}`). Solo se permite un administrador por inquilino. |
| RF-1.8 | Al registrar el administrador, el `register_token` se anula y se le asigna el rol `Administrador` al usuario. |
| RF-1.9 | Un flujo de **onboarding** (`/onboarding`) recopila datos completos del club incluyendo carga de documentos (RUT, Cámara de Comercio, escudo) y plan de suscripción (mensual/anual). |

### RF-2: Gestión de Usuarios Multiinquilino

| ID | Requisito |
|----|-----------|
| RF-2.1 | Cada usuario pertenece a exactamente un inquilino vía `tenant_id`. |
| RF-2.2 | Los administradores pueden crear, editar, listar y eliminar usuarios dentro de su inquilino vía **UserResource**. |
| RF-2.3 | Se pueden asignar múltiples roles a los usuarios vía Spatie Permissions (asignación al crear/editar). |
| RF-2.4 | Los usuarios se pueden filtrar por rol y rango de fechas de creación. |
| RF-2.5 | Los usuarios pueden exportar lists de usuarios (PDF/XLSX) vía FilamentExport. |

### RF-3: Gestión de Roles y Permisos (RBAC)

| ID | Requisito |
|----|-----------|
| RF-3.1 | El sistema utiliza **FilamentShield** para control de acceso basado en roles. |
| RF-3.2 | Los administradores pueden crear, ver, editar y eliminar roles vía **RoleResource**. |
| RF-3.3 | Cada rol tiene una matriz de permisos que otorga/deniega acceso a recursos y acciones específicas (Ver, Crear, Actualizar, Eliminar, etc.). |
| RF-3.4 | Los roles son conscientes del inquilino (scoped por equipo). |
| RF-3.5 | Todos los recursos aplican autorización basada en políticas mediante Laravel Policies (UserPolicy, TenantPolicy, TorneoPolicy, RolePolicy, RendimientoPolicy, PartidoPolicy, PagoPolicy, NotificacionPolicy, JugadorPerfilPolicy, InstalacionPolicy, HistorialMedicoPolicy, EquipoPolicy, EquipoUserPolicy, EntrenamientoPolicy, AsistenciaEntrenamientoPolicy). |

### RF-4: Gestión de Equipos

| ID | Requisito |
|----|-----------|
| RF-4.1 | Los administradores pueden crear, editar, listar y eliminar equipos vía **EquipoResource**. |
| RF-4.2 | Cada equipo tiene: nombre, categoría (profesional/amateur/formativo), logo (carga de imagen), ubicación, información de contacto y subcategoría (Sub-10 hasta Sub-18, Profesional). |
| RF-4.3 | Los equipos se pueden filtrar por categoría (Masculino/Femenino/Mixto), subcategoría y ubicación. |
| RF-4.4 | Los datos de equipos están limitados automáticamente al inquilino del usuario autenticado vía alcance global. |

### RF-5: Membresía Equipo-Usuario (EquipoUser)

| ID | Requisito |
|----|-----------|
| RF-5.1 | Los administradores pueden asignar usuarios a equipos vía **EquipoUserResource**, registrando fechas de inicio y fin. |
| RF-5.2 | Las membresías activas son aquellas donde `fecha_fin` es nulo. |
| RF-5.3 | Las membresías se pueden filtrar por equipo, jugador, rango de fechas y estado activo. |
| RF-5.4 | El sistema mantiene un historial de membresías por usuario. |

### RF-6: Gestión de Perfiles de Jugadores

| ID | Requisito |
|----|-----------|
| RF-6.1 | Cada jugador puede tener un perfil extendido vía **JugadorPerfil**, que contiene: posición de juego (portero/defensa/mediocampo/delantero), número de camiseta (dorsal), altura (cm), peso (kg) y pierna hábil (derecha/izquierda/ambas). |
| RF-6.2 | Los perfiles se pueden filtrar por jugador, posición, pierna hábil y fecha de creación. |
| RF-6.3 | La posición y pierna hábil se muestran con badges codificados por colores. |
| RF-6.4 | Los jugadores tienen su propio panel (`/jugador`) con una página **PlayerProfile** para ver/editar su información personal (nombre, email, teléfono, avatar). |

### RF-7: Gestión de Sesiones de Entrenamiento

| ID | Requisito |
|----|-----------|
| RF-7.1 | Los administradores pueden crear, editar, listar y eliminar sesiones de entrenamiento vía **EntrenamientoResource**. |
| RF-7.2 | Cada entrenamiento tiene: nombre, fecha, hora, ubicación y equipo asignado. |
| RF-7.3 | Los entrenamientos se limitan automáticamente al inquilino actual. |

### RF-8: Registro de Asistencia a Entrenamientos

| ID | Requisito |
|----|-----------|
| RF-8.1 | Los administradores pueden registrar asistencia para cada sesión de entrenamiento vía **AsistenciaEntrenamientoResource**. |
| RF-8.2 | Cada registro vincula un jugador (usuario) a un entrenamiento con un indicador booleano `presente`. |
| RF-8.3 | La asistencia se puede alternar directamente en la tabla vía ToggleColumn. |
| RF-8.4 | Los registros se pueden filtrar por estado de asistencia, rango de fechas, jugador y filtro "solo presentes". |

### RF-9: Gestión de Partidos

| ID | Requisito |
|----|-----------|
| RF-9.1 | Los administradores pueden crear, editar, listar y eliminar partidos vía **PartidoResource**. |
| RF-9.2 | Cada partido tiene: fecha, hora, equipo local, equipo visitante, resultado/marcador y torneo asignado. |
| RF-9.3 | Los partidos están limitados al inquilino actual vía alcance global. |
| RF-9.4 | Los partidos se pueden filtrar por equipo local, equipo visitante y rango de fechas. |

### RF-10: Gestión de Torneos

| ID | Requisito |
|----|-----------|
| RF-10.1 | Los administradores pueden crear, editar, listar y eliminar torneos vía **TorneoResource**. |
| RF-10.2 | Cada torneo tiene: nombre, categoría, fecha de inicio, fecha de fin y estado (próximo/activo/completado). |
| RF-10.3 | Los equipos se pueden asociar a torneos vía la tabla pivote `equipo_torneo`. |
| RF-10.4 | Los partidos pertenecen a un torneo vía FK `torneo_id`. |

### RF-11: Estadísticas de Rendimiento por Jugador

| ID | Requisito |
|----|-----------|
| RF-11.1 | Los administradores pueden registrar rendimiento por partido vía **RendimientoResource**. |
| RF-11.2 | Cada registro rastrea: minutos jugados, goles, asistencias, tarjetas amarillas, tarjetas rojas y una calificación numérica (0-10). |
| RF-11.3 | Los registros se pueden filtrar por jugador, rango de fechas y rango de calificación. |
| RF-11.4 | Los registros se pueden exportar (PDF por defecto / XLSX para exportación masiva). |

### RF-12: Historial Médico

| ID | Requisito |
|----|-----------|
| RF-12.1 | Los administradores pueden crear, editar, listar y eliminar registros médicos vía **HistorialMedicoResource**. |
| RF-12.2 | Cada registro contiene: jugador, tipo de lesión (lesión/enfermedad/control), descripción, gravedad (leve/media/grave), fecha de inicio, fecha de fin y autorización deportiva (apto booleano). |
| RF-12.3 | Los registros se pueden filtrar por jugador, tipo de lesión, gravedad, estado de aptitud y rango de fechas. |
| RF-12.4 | El tipo de lesión y gravedad se muestran con badges codificados por colores. |

### RF-13: Gestión de Pagos

| ID | Requisito |
|----|-----------|
| RF-13.1 | Los administradores pueden registrar y gestionar pagos vía **PagoResource**. |
| RF-13.2 | Cada pago registra: usuario pagador, fecha, monto (moneda COP) y estado (pendiente/pagado/rechazado). |
| RF-13.3 | Los pagos están limitados al inquilino actual (usuarios filtrados por tenant_id). |
| RF-13.4 | Los pagos se pueden filtrar por usuario, estado, rango de fechas y rango de monto. |

### RF-14: Gestión de Instalaciones

| ID | Requisito |
|----|-----------|
| RF-14.1 | Los administradores pueden crear, editar, listar y eliminar instalaciones vía **InstalacionResource**. |
| RF-14.2 | Cada instalación tiene: nombre, tipo (cancha/gimnasio/piscina/estadio), ubicación, capacidad y estado (disponible/ocupada/mantenimiento). |
| RF-14.3 | Las instalaciones se pueden filtrar por tipo, estado y fecha de creación. |
| RF-14.4 | El tipo y estado de la instalación se muestran con badges codificados por colores. |

### RF-15: Gestión de Notificaciones

| ID | Requisito |
|----|-----------|
| RF-15.1 | Los administradores pueden crear, editar, listar y eliminar notificaciones internas vía **NotificacionResource**. |
| RF-15.2 | Cada notificación tiene: título, cuerpo del mensaje y estado leído/no leído. |
| RF-15.3 | Las notificaciones se pueden filtrar por estado de lectura y fecha de creación. |

### RF-16: Vista de Calendario

| ID | Requisito |
|----|-----------|
| RF-16.1 | Los administradores pueden ver un calendario unificado (`/admin/calendario`) con todas las sesiones de entrenamiento y partidos. |
| RF-16.2 | Los eventos de entrenamiento se muestran en azul (`#2563eb`). |
| RF-16.3 | Los eventos de partido se muestran en verde (`#16a34a`). |

### RF-17: Dashboard y Widgets Analíticos

| ID | Requisito |
|----|-----------|
| RF-17.1 | El dashboard admin muestra los siguientes widgets: **TotalUsuarios** (conteo total de usuarios), **TotalEquipos** (total de equipos), **TorneosActivos** (torneos activos), **AsistenciaPorMes** (gráfico de asistencia mensual), **EntrenamientosPorMes** (gráfico de entrenamientos mensuales), **JugadoresNoAptos** (jugadores sin aptitud médica), **PagosDelMes** (pagos del mes actual). |
| RF-17.2 | El dashboard del jugador (`/jugador`) muestra: **AsistenciaPorMes** y **EntrenamientosPorMes** específicos del jugador. |
| RF-17.3 | El acceso al dashboard está controlado por el permiso `View: Dashboard`. |

### RF-18: Gestión de Cuenta de Usuario (Configuración)

| ID | Requisito |
|----|-----------|
| RF-18.1 | Los usuarios autenticados pueden actualizar su perfil (nombre, email) vía `/settings/profile`. Los cambios de email activan re-verificación. |
| RF-18.2 | Los usuarios pueden cambiar su contraseña vía `/settings/password`, requiriendo confirmación de la contraseña actual. |
| RF-18.3 | Los usuarios pueden activar/desactivar autenticación de dos factores (2FA) vía `/settings/two-factor`, con escaneo de código QR y confirmación de código de verificación. |
| RF-18.4 | Los usuarios pueden ver y regenerar códigos de recuperación 2FA. |
| RF-18.5 | Los usuarios pueden eliminar su propia cuenta vía `/settings`, requiriendo confirmación de contraseña. |
| RF-18.6 | Los usuarios pueden cerrar sesión, lo cual invalida la sesión y regenera el token CSRF. |

### RF-19: Exportación de Datos

| ID | Requisito |
|----|-----------|
| RF-19.1 | Casi todos los recursos soportan exportación de datos vía plugin **FilamentExport**. |
| RF-19.2 | La exportación individual tiene formato PDF por defecto con descarga directa. |
| RF-19.3 | La exportación masiva (registros seleccionados) tiene formato XLSX por defecto. |
| RF-19.4 | La exportación está disponible tanto como acción de encabezado como acción masiva. |

### RF-20: Landing Page y Páginas Públicas

| ID | Requisito |
|----|-----------|
| RF-20.1 | La URL raíz (`/`) renderiza una **Landing page** vía Inertia.js + React. |
| RF-20.2 | La ruta `/dashboard` requiere autenticación y verificación de email. |
| RF-20.3 | La ruta `/test-mail` proporciona un endpoint de prueba de configuración de correo. |

---

## 3. REQUISITOS NO FUNCIONALES

### RNF-1: Arquitectura

| ID | Requisito |
|----|-----------|
| RNF-1.1 | **Arquitectura SaaS Multiinquilino**: El aislamiento de datos por inquilino se aplica vía FK `tenant_id` en la mayoría de modelos y el trait `BelongsToTenant`. |
| RNF-1.2 | **Alcances Globales**: Los modelos (Equipo, Partido, Torneo) aplican alcances globales que filtran automáticamente las consultas por el `tenant_id` del usuario autenticado. |
| RNF-1.3 | **Arquitectura de Panel Dual**: Dos paneles Filament separados — **Panel Admin** (`/admin`, tema azul) para administradores y **Panel Jugador** (`/jugador`, tema rojo) para jugadores. |
| RNF-1.4 | **Capa de Servicio**: La lógica de negocio se abstrae en servicios (ej. `ApprovalService` para orquestación de notificaciones a admin). |
| RNF-1.5 | **UI Basada en Componentes**: Los Filament Resources delegan esquemas de formulario/tabla a clases separadas `*Form.php` y `*Table.php`. |
| RNF-1.6 | **Frontend Híbrido**: Combina Filament (Blade/Livewire) para paneles admin con Inertia.js + React para páginas públicas (landing, onboarding). |

### RNF-2: Seguridad y Autorización

| ID | Requisito |
|----|-----------|
| RNF-2.1 | **Hash de Contraseñas**: Todas las contraseñas se hashean usando el cast `hashed` de Laravel (bcrypt, 12 rounds según `.env.example`). |
| RNF-2.2 | **Autenticación de Dos Factores (2FA)**: Soporte completo TOTP vía Laravel Fortify, con visualización de código QR, confirmación de código de verificación y gestión de códigos de recuperación. |
| RNF-2.3 | **Control de Acceso Basado en Roles (RBAC)**: Spatie Laravel-Permission + FilamentShield aplica permisos granulares por recurso y acción vía Policies. |
| RNF-2.4 | **Aplicación de Políticas**: Cada modelo tiene una clase Policy correspondiente que delega a cadenas de permisos Shield (ej. `View:User`, `Create:Tenant`). |
| RNF-2.5 | **Seguridad de Sesión**: Las sesiones se almacenan en la base de datos, encriptadas, con vida útil de 120 minutos. La sesión se invalida y el token CSRF se regenera al cerrar sesión. |
| RNF-2.6 | **Protección CSRF**: Todos los formularios están protegidos vía middleware `VerifyCsrfToken` de Laravel. |
| RNF-2.7 | **Encriptación de Cookies**: Todas las cookies se encriptan vía middleware `EncryptCookies`. |
| RNF-2.8 | **Verificación de Email**: Los usuarios deben verificar su email. Cambiar el email resetea `email_verified_at` y requiere re-verificación. |
| RNF-2.9 | **Registro Tokenizado**: El registro del admin del inquilino usa tokens UUID de un solo uso (`register_token`) que se anulan tras su uso. |
| RNF-2.10 | **Un Admin por Inquilino**: El sistema verifica que solo un usuario admin pueda existir por inquilino (comprobado durante el registro). |
| RNF-2.11 | **Restricción de Página de Inquilinos**: La página TenantRequests está restringida exclusivamente al rol `super_admin`. |

### RNF-3: Gestión de Datos

| ID | Requisito |
|----|-----------|
| RNF-3.1 | **Motor de Base de Datos**: Por defecto SQLite; configurable para MySQL/PostgreSQL. |
| RNF-3.2 | **Sistema de Colas**: Driver de colas basado en base de datos para procesamiento asíncrono de jobs (envío de correos). |
| RNF-3.3 | **Cola de Correos**: Los correos de aprobación/rechazo de inquilinos se encolan (trait `Queueable`) para evitar bloquear el ciclo de la solicitud. |
| RNF-3.4 | **Almacenamiento de Archivos**: Los archivos subidos (logos, documentos) se almacenan en disco `local` bajo `tenants/documentos/` y `tenants/logos/`. Avatares bajo `avatars/`. |
| RNF-3.5 | **Validación de Documentos**: Los documentos subidos (RUT, Cámara de Comercio) deben ser PDF/JPG/JPEG/PNG, máx 5MB. El logo del club debe ser imagen, máx 2MB. |
| RNF-3.6 | **Sin Eliminación Suave**: Todas las eliminaciones son permanentes. No se usa el trait `SoftDeletes` ni acciones de restaurar/eliminación forzada. |
| RNF-3.7 | **Casteo de Booleanos**: Los campos booleanos (`presente`, `leido`, `apto`) se castean desde/hacia valores de base de datos. El campo `presente` usa un mutador personalizado para almacenar como cadena `'true'`/`'false'`. |
| RNF-3.8 | **Casteo de JSON**: El campo `colores_oficiales` en Tenant se castea desde/hacia un array PHP (almacenado como JSON). |
| RNF-3.9 | **Moneda**: Los montos de pago se muestran en Peso Colombiano (COP). |
| RNF-3.10 | **Indexación de Rendimiento**: Una migración dedicada (`add_performance_indexes_to_tables`) agrega índices de base de datos para optimización de consultas. |

### RNF-4: Validación de Datos

| ID | Requisito |
|----|-----------|
| RNF-4.1 | **Registro de Inquilino**: Valida campos requeridos (nombre, unicidad de NIT, unicidad de email, enum de tipo de club, ciudad, país, encargado). |
| RNF-4.2 | **Registro de Admin**: Valida nombre, email (único), contraseña (mín 8 caracteres + confirmación). |
| RNF-4.3 | **Onboarding**: Valida todos los campos requeridos incluyendo cargas de archivos con restricciones de tipo MIME y tamaño. |
| RNF-4.4 | **Validación de Formularios**: Los formularios Filament aplican validación a nivel de campo (requerido, longitud máxima, rangos numéricos, restricciones de fecha, existencia de relación). |
| RNF-4.5 | **Cambio de Contraseña**: Requiere coincidencia de contraseña actual y que la nueva cumpla las reglas de complejidad por defecto de Laravel. |
| RNF-4.6 | **Eliminación de Cuenta**: Requiere confirmación de contraseña actual antes de la eliminación. |

### RNF-5: Rendimiento y Escalabilidad

| ID | Requisito |
|----|-----------|
| RNF-5.1 | **Indexación de Base de Datos**: Se agregan índices de rendimiento en columnas de consulta frecuente en todas las tablas. |
| RNF-5.2 | **Limitación de Consultas**: Los alcances globales reducen los resultados a datos específicos del inquilino, previniendo consultas entre inquilinos. |
| RNF-5.3 | **Entrega Asíncrona de Correos**: Los correos se encolan para evitar latencia en la solicitud. |
| RNF-5.4 | **Manejo de Fallback**: Si no existen usuarios admin para notificaciones, el sistema recurre a logging en lugar de fallar. |
| RNF-5.5 | **Paginación**: Las tablas Filament implementan paginación por defecto para conjuntos de datos grandes. |
| RNF-5.6 | **Columnas Buscables**: Las columnas clave en todos los recursos están marcadas como buscables para filtrado eficiente. |

### RNF-6: Estándares de UI/UX

| ID | Requisito |
|----|-----------|
| RNF-6.1 | **Tema del Panel Admin**: Esquema de color primario azul (`Color::Blue`). |
| RNF-6.2 | **Tema del Panel Jugador**: Esquema de color primario rojo (`Color::Red`). |
| RNF-6.3 | **Codificación de Colores en Badges**: Los campos de estado, tipo y categoría usan badges con colores consistentes (ej. success=verde, warning=amarillo, danger=rojo, primary=azul). |
| RNF-6.4 | **Emojis de Tarjetas**: Los emojis de tarjeta amarilla y roja se usan directamente en las columnas de la tabla Rendimiento. |
| RNF-6.5 | **Diseño Responsivo**: El dashboard usa un layout de cuadrícula de 4 columnas en breakpoints por defecto. |
| RNF-6.6 | **Renderizado de Imágenes**: Los logos de equipo se renderizan como imágenes redondeadas en las tablas. |
| RNF-6.7 | **Columnas Toggle**: El campo `presente` de asistencia soporta edición toggle inline directamente en la vista de tabla. |

### RNF-7: Localización

| ID | Requisito |
|----|-----------|
| RNF-7.1 | **Idioma por Defecto**: Inglés (`APP_LOCALE=en`). |
| RNF-7.2 | **Idioma de la UI**: Todas las etiquetas, grupos de navegación y encabezados de columna de Filament están en **español** (ej. "Equipos", "Entrenamientos", "Competencias", "Finanzas", "Administracion", "Jugadores"). |

### RNF-8: Observabilidad

| ID | Requisito |
|----|-----------|
| RNF-8.1 | **Logging**: Los logs de la aplicación se escriben en el canal `stack` a nivel `debug` por defecto. |
| RNF-8.2 | **Logging de Fallback de Notificación**: Si la notificación al admin falla, la solicitud se registra vía `\Log::warning()` en lugar de crashear. |
| RNF-8.3 | **Pista de Auditoría**: Los timestamps `created_at` y `updated_at` se rastrean y muestran en la mayoría de recursos. |

### RNF-9: Pruebas

| ID | Requisito |
|----|-----------|
| RNF-9.1 | El proyecto incluye un directorio `tests/` con configuración de PHPUnit (`phpunit.xml`). |
| RNF-9.2 | La suite de pruebas se puede ejecutar vía `phpunit.xml`. |

---

## 4. ACTORES DEL SISTEMA Y ROLES

| Actor | Descripción | Nivel de Acceso |
|-------|-------------|-----------------|
| **Visitante** | Usuario no autenticado | Puede ver landing page, enviar solicitud de registro de inquilino |
| **Solicitante de Inquilino** | Persona que envía registro de club | Accede a `/solicitar-acceso` y `/onboarding` |
| **Admin de Inquilino (Administrador)** | Primer usuario registrado de un inquilino aprobado | Acceso completo al panel `/admin`, todos los recursos dentro de su inquilino |
| **Super Admin** | Administrador a nivel de plataforma | Acceso a TenantRequests, puede aprobar/rechazar solicitudes de inquilinos |
| **Jugador** | Usuario atleta final | Acceso al panel `/jugador`, perfil propio, estadísticas personales |
| **Staff/Entrenador** | Usuarios con roles operativos | Acceso a recursos de entrenamiento, asistencia, partidos y rendimiento según permisos asignados |

---

## 5. RESUMEN DEL MODELO DE DATOS

| Entidad | Campos Clave | Relaciones |
|---------|-------------|------------|
| **Tenant** | nombre, subdominio, estado, nit, tipo_club, plan, register_token, documentos | HasMany User, HasMany Equipo |
| **User** | name, email, password, tenant_id | BelongsTo Tenant, BelongsToMany Roles, HasOne JugadorPerfil, HasMany Pago, HasMany HistorialMedico, HasMany Notificacion, HasMany Rendimiento |
| **Equipo** | nombre, categoria, logo_equipo, ubi_equipo, contacto_equipo, tenant_id | BelongsTo Tenant, BelongsToMany Torneo, BelongsToMany User (vía historial_equipo) |
| **EquipoUser** | equipo_id, user_id, fecha_inicio, fecha_fin, tenant_id | BelongsTo Equipo, BelongsTo User |
| **JugadorPerfil** | user_id, posicion, dorsal, altura, peso, pierna_habil, tenant_id | BelongsTo User |
| **Entrenamiento** | nombre, fecha, hora, ubicacion, equipo_id, tenant_id | BelongsTo Equipo, HasMany AsistenciaEntrenamiento |
| **AsistenciaEntrenamiento** | entrenamiento_id, user_id, presente, tenant_id | BelongsTo Entrenamiento, BelongsTo User |
| **Partido** | fecha, hora, equipo_local_id, equipo_visitante_id, resultado, torneo_id, tenant_id | BelongsTo Equipo (local), BelongsTo Equipo (visitante), BelongsTo Torneo |
| **Torneo** | nombre, categoria, fecha_inicio, fecha_fin, estado, tenant_id | HasMany Partido, BelongsToMany Equipo |
| **Rendimiento** | user_id, partido_id, minutos_jugados, goles, asistencias, tarjetas_amarillas, tarjetas_rojas, evaluacion | BelongsTo User, BelongsTo Partido |
| **HistorialMedico** | user_id, tipo_lesion, descripcion, gravedad, fecha_inicio, fecha_fin, apto | BelongsTo User |
| **Pago** | user_id, fecha, monto, estado, tenant_id | BelongsTo User |
| **Instalacion** | nombre, tipo, ubicacion, capacidad, estado, tenant_id | Sin relaciones definidas |
| **Notificacion** | user_id, titulo, mensaje, leido | BelongsTo User |

---

*Este documento representa un análisis completo de ingeniería inversa del codebase actual de FitControl, capturando todos los requisitos funcionales y no funcionales tal como existen en el código implementado.*
