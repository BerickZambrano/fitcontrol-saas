# FitControl — Historias de Usuario

> **Plataforma SaaS Multi-Tenant para Gestión de Clubes Deportivos**  
> Laravel 12 + Filament v4 + React + Inertia.js  
> Total: **56 Historias de Usuario**

---

## 📖 Tabla de Contenidos

- [Historias Transversales (Autenticación, Configuración, Export)](#historias-transversales)
- [Historias del Visitante](#historias-del-visitante)
- [Historias del Super Admin](#historias-del-super-admin)
- [Historias del Administrador (Tenant Admin)](#historias-del-administrador-tenant-admin)
- [Historias del Entrenador (Coach)](#historias-del-entrenador-coach)
- [Historias del Jugador (Player)](#historias-del-jugador-player)
- [Historias Multi-Tenant / Sistema](#historias-multi-tenant--sistema)
- [Resumen por Actor y Tipo](#resumen-por-actor-y-tipo)

---

## Historias Transversales

### HU-05: Autenticarse (Login)
- **Actor:** Todos los usuarios autenticados
- **Descripción:** Como usuario registrado, quiero iniciar sesión con mis credenciales para acceder al sistema
- **Criterios de Aceptación:**
  - Dado que tengo credenciales válidas, Cuando inicio sesión, Entonces si tengo rol `Administrador` o `super_admin` soy redirigido a `/admin`, de lo contrario a `/jugador`
- **Prioridad:** Alta
- **Módulo:** Autenticación (Filament Auth / Fortify)
- **Tipo:** Transversal

### HU-06: Cerrar Sesión (Logout)
- **Actor:** Todos los usuarios autenticados
- **Descripción:** Como usuario autenticado, quiero cerrar sesión para que mi sesión sea terminada
- **Criterios de Aceptación:**
  - Dado que estoy logueado, Cuando cierro sesión, Entonces soy redirigido a `/login` y la sesión se invalida
- **Prioridad:** Alta
- **Módulo:** Autenticación
- **Tipo:** Transversal

### HU-07: Gestionar Configuración de Perfil
- **Actor:** Todos los usuarios autenticados
- **Descripción:** Como usuario autenticado, quiero actualizar mi nombre y email para que mi información de perfil esté actualizada
- **Criterios de Aceptación:**
  - Dado que estoy logueado, Cuando visito `/settings/profile` y actualizo mi nombre/email, Entonces mi información se guarda y si el email cambió, `email_verified_at` se resetea
- **Prioridad:** Alta
- **Módulo:** Configuración (Livewire)
- **Tipo:** Transversal

### HU-08: Reenviar Verificación de Email
- **Actor:** Todos los usuarios autenticados
- **Descripción:** Como usuario cuyo email no está verificado, quiero reenviar el email de verificación para verificar mi cuenta
- **Criterios de Aceptación:**
  - Dado que mi email no está verificado, Cuando hago clic en reenviar verificación, Entonces se envía una notificación de verificación por email
- **Prioridad:** Media
- **Módulo:** Configuración
- **Tipo:** Transversal

### HU-09: Cambiar Contraseña
- **Actor:** Todos los usuarios autenticados
- **Descripción:** Como usuario autenticado, quiero cambiar mi contraseña para que mi cuenta siga siendo segura
- **Criterios de Aceptación:**
  - Dado que conozco mi contraseña actual, Cuando proporciono contraseña actual + nueva contraseña (confirmada) en `/settings/password`, Entonces mi contraseña se actualiza
- **Prioridad:** Alta
- **Módulo:** Configuración (Livewire)
- **Tipo:** Transversal

### HU-10: Gestionar Autenticación de Dos Factores
- **Actor:** Todos los usuarios autenticados
- **Descripción:** Como usuario autenticado, quiero activar/desactivar la autenticación de dos factores para que mi cuenta tenga una capa extra de seguridad
- **Criterios de Aceptación:**
  - Dado que estoy en `/settings/two-factor`, Cuando activo 2FA, Entonces veo un código QR y clave de configuración para mi app autenticadora
  - Cuando confirmo con un código válido de 6 dígitos, Entonces 2FA está completamente habilitado
  - Cuando desactivo 2FA, Entonces se desactiva para mi cuenta
- **Prioridad:** Media
- **Módulo:** Configuración (Livewire + Fortify)
- **Tipo:** Transversal

### HU-11: Gestionar Configuración de Apariencia
- **Actor:** Todos los usuarios autenticados
- **Descripción:** Como usuario autenticado, quiero personalizar la apariencia de la interfaz para que se adapte a mis preferencias
- **Criterios de Aceptación:**
  - Dado que estoy en `/settings/appearance`, Cuando cambio la configuración de apariencia, Entonces los cambios se aplican
- **Prioridad:** Baja
- **Módulo:** Configuración (Livewire)
- **Tipo:** Transversal

### HU-12: Eliminar Mi Cuenta
- **Actor:** Todos los usuarios autenticados
- **Descripción:** Como usuario autenticado, quiero eliminar mi cuenta confirmando mi contraseña para que mis datos sean eliminados permanentemente
- **Criterios de Aceptación:**
  - Dado que proporciono mi contraseña actual, Cuando confirmo la eliminación de cuenta, Entonces mi usuario se elimina y cierro sesión y soy redirigido a `/`
- **Prioridad:** Media
- **Módulo:** Configuración (Livewire)
- **Tipo:** Transversal

### HU-13: Exportar Datos de Tablas
- **Actor:** Administrador, Super Admin
- **Descripción:** Como usuario admin, quiero exportar datos de tablas (CSV, XLSX, PDF) para analizar datos fuera del sistema
- **Criterios de Aceptación:**
  - Dado que estoy viendo cualquier tabla de recursos (Users, Equipos, Entrenamientos, Partidos, Asistencia, Pagos, JugadorPerfils, HistorialMedicos, Rendimientos, Notificaciones, Instalaciones, EquipoUsers), Cuando hago clic en "Exportar", Entonces puedo descargar los datos en el formato seleccionado
  - Cuando selecciono múltiples registros y hago clic en "Exportar seleccionados", Entonces solo los registros seleccionados se exportan
- **Prioridad:** Media
- **Módulo:** Transversal (FilamentExport)
- **Tipo:** Transversal

---

## Historias del Visitanted

### HU-00: Ver Landing Page
- **Actor:** Visitante
- **Descripción:** Como visitante, quiero ver una landing page con información sobre FitControl para entender el producto antes de registrarme
- **Criterios de Aceptación:**
  - Dado que estoy en la página principal, Cuando visito `/`, Entonces veo la landing page con secciones de Hero, HowWeOperate, HowWeWork, Testimonials, PricingPlans, ContactUs, Tips, Questions, Invitation y Footer
- **Prioridad:** Alta
- **Módulo:** Landing Page (Inertia)
- **Tipo:** Vista pública

### HU-01: Enviar Solicitud de Acceso (Tenant Onboarding)
- **Actor:** Visitante
- **Descripción:** Como potencial administrador de club, quiero enviar una solicitud de acceso con la información de mi club para obtener una cuenta FitControl
- **Criterios de Aceptación:**
  - Dado que estoy en la página de solicitud de acceso, Cuando completo el formulario (nombre, nit, tipo_club, ciudad, pais, email_corporativo, encargado_nombre, encargado_email) y envío, Entonces se crea un registro de tenant pendiente y veo un mensaje de éxito
  - Dado que envío con datos inválidos o duplicados, Entonces veo errores de validación
- **Prioridad:** Alta
- **Módulo:** Tenant Request (`/solicitar-acceso`)
- **Tipo:** Lógica de negocio

### HU-02: Completar Registro de Onboarding (Multi-paso)
- **Actor:** Visitante
- **Descripción:** Como representante de un nuevo club, quiero completar un proceso de onboarding de 3 pasos (datos del club, documentos, selección de plan) para que mi tenant se cree con toda la documentación requerida
- **Criterios de Aceptación:**
  - Dado que estoy en la página de onboarding, Cuando completo el Paso 1 (datos del club: nombre, nombre_corto, ciudad, pais, direccion, telefono, tipo_club, escudo_url), Entonces avanzo al Paso 2
  - Cuando completo el Paso 2 (documentos: nit, encargado_nombre, encargado_email, encargado_telefono, rut_document, camara_comercio), Entonces avanzo al Paso 3
  - Cuando selecciono un plan (mensual/anual) en el Paso 3 y envío, Entonces el tenant se crea con estado "pendiente" y soy redirigido a la página de éxito
- **Prioridad:** Alta
- **Módulo:** Onboarding (`/onboarding`)
- **Tipo:** Lógica de negocio

### HU-03: Ver Confirmación de Éxito de Onboarding
- **Actor:** Visitante
- **Descripción:** Como nuevo usuario que envió onboarding, quiero ver una página de confirmación de éxito para saber que mi solicitud fue recibida
- **Criterios de Aceptación:**
  - Dado que envié exitosamente el formulario de onboarding, Cuando soy redirigido a `/onboarding/success`, Entonces veo un mensaje de confirmación con los próximos pasos explicados
- **Prioridad:** Media
- **Módulo:** Onboarding
- **Tipo:** Vista pública

### HU-04: Registrarse como Tenant Admin vía Link de Invitación
- **Actor:** Visitante (admin de tenant invitado)
- **Descripción:** Como administrador de tenant invitado, quiero registrar mi cuenta usando el link de token enviado por email para acceder al panel admin
- **Criterios de Aceptación:**
  - Dado que recibí un email de aprobación con un token de registro, Cuando visito `/register-admin/{token}` y completo nombre, email, contraseña, Entonces mi usuario se crea con el rol "Administrador" y soy redirigido a `/admin`
  - Dado que el tenant ya tiene un admin, Cuando intento registrarme, Entonces obtengo un error 403
  - Dado un token inválido, Cuando visito el link, Entonces obtengo un error 404
- **Prioridad:** Alta
- **Módulo:** Admin Register
- **Tipo:** Lógica de negocio

### HU-54: Recibir Email de Aprobación/Rechazo de Tenant
- **Actor:** Visitante (solicitante de tenant)
- **Descripción:** Como alguien que envió una solicitud de tenant, quiero recibir una notificación por email cuando mi solicitud sea aprobada o rechazada para saber el estado
- **Criterios de Aceptación:**
  - Dado que un super_admin aprueba mi tenant, Cuando se activa la acción de aprobación, Entonces recibo un email con asunto "Tu solicitud fue aprobada" conteniendo un link de registro
  - Dado que un super_admin rechaza mi tenant, Cuando se activa la acción de rechazo, Entonces recibo un email con asunto "Tu solicitud no fue aprobada"
- **Prioridad:** Alta
- **Módulo:** Mail (TenantApprovedMail, TenantRejectedMail)
- **Tipo:** Lógica de negocio (notificación)

---

## Historias del Super Admin

### HU-14: Ver Dashboard con Widgets Globales del Sistema
- **Actor:** Super Admin
- **Descripción:** Como super admin, quiero ver un dashboard con estadísticas de todo el sistema para monitorear toda la plataforma
- **Criterios de Aceptación:**
  - Dado que soy super_admin, Cuando visito el dashboard admin, Entonces veo widgets: TotalUsuarios (por rol), TotalEquipos (por categoría), TorneosActivos (por estado), AsistenciaPorMes, EntrenamientosPorMes, JugadoresNoAptos y PagosDelMes
  - Todos los widgets muestran datos de TODOS los tenants (no filtrados)
- **Prioridad:** Alta
- **Módulo:** Admin Dashboard
- **Tipo:** Lógica de negocio (vista)

### HU-15: Ver Calendario con Eventos
- **Actor:** Super Admin, Administrador
- **Descripción:** Como usuario admin, quiero ver un calendario con todos los entrenamientos y partidos para ver el horario de un vistazo
- **Criterios de Aceptación:**
  - Dado que accedo a la página Calendario, Cuando veo el calendario, Entonces veo todos los eventos de Entrenamiento (azul) y Partido (verde) con sus fechas y descripciones
- **Prioridad:** Media
- **Módulo:** Admin Pages / Calendario
- **Tipo:** Lógica de negocio (vista)

### HU-16: Gestionar Solicitudes de Tenant (Aprobar/Rechazar)
- **Actor:** Super Admin
- **Descripción:** Como super admin, quiero revisar solicitudes pendientes de acceso de tenant y aprobarlas o rechazarlas para controlar quién obtiene acceso a la plataforma
- **Criterios de Aceptación:**
  - Dado que hay tenants pendientes, Cuando veo la página TenantRequests, Entonces veo una tabla con nombre del tenant, email y fecha de solicitud
  - Cuando apruebo un tenant, Entonces el estado del tenant cambia a "activo", se genera un register_token y se envía un email de aprobación
  - Cuando rechazo un tenant, Entonces el estado del tenant cambia a "suspendido" y se envía un email de rechazo
- **Prioridad:** Alta
- **Módulo:** Admin Pages / TenantRequests
- **Tipo:** Lógica de negocio

### HU-17: Gestionar Tenants (CRUD)
- **Actor:** Super Admin
- **Descripción:** Como super admin, quiero crear, ver, editar y eliminar tenants para gestionar todos los clubes en la plataforma
- **Criterios de Aceptación:**
  - Dado que accedo al recurso Tenants, Cuando listo tenants, Entonces veo todos los campos del tenant (nombre, subdominio, estado, nit, tipo_club, plan, etc.)
  - Cuando apruebo un tenant pendiente desde la tabla, Entonces el estado se vuelve "activo", se genera un register_token y se envía un email de aprobación
  - Cuando rechazo un tenant pendiente, Entonces el estado se vuelve "rechazado" y se envía un email de rechazo
  - Cuando edito un tenant, Entonces puedo actualizar cualquier campo
- **Prioridad:** Alta
- **Módulo:** Tenants Resource
- **Tipo:** CRUD + Lógica de negocio (acciones aprobar/rechazar)

### HU-18: Gestionar Usuarios (CRUD)
- **Actor:** Super Admin, Administrador
- **Descripción:** Como admin, quiero crear, ver, editar y eliminar usuarios para gestionar quién tiene acceso al sistema
- **Criterios de Aceptación:**
  - Dado que accedo al recurso Users, Cuando listo usuarios, Entonces veo nombre, email y roles con badges
  - Cuando creo/edito un usuario, Entonces puedo establecer nombre, email, contraseña, tenant y múltiples roles
  - Cuando filtro por rol o fecha de creación, Entonces los resultados se filtran
  - Cuando elimino un usuario, Entonces el usuario se elimina
- **Prioridad:** Alta
- **Módulo:** Users Resource
- **Tipo:** CRUD

### HU-19: Gestionar Roles y Permisos (CRUD + Shield)
- **Actor:** Super Admin
- **Descripción:** Como super admin, quiero crear, ver, editar y eliminar roles con permisos específicos para controlar el acceso a diferentes partes del sistema
- **Criterios de Aceptación:**
  - Dado que accedo al recurso Roles (FilamentShield), Cuando listo roles, Entonces veo nombre, guard_name, team y conteo de permisos
  - Cuando creo/edito un rol, Entonces puedo establecer nombre, guard_name, team y seleccionar permisos individuales vía componentes de formulario Shield
  - Cuando veo un rol, Entonces veo sus detalles completos de permisos
  - Cuando el multi-tenant está habilitado, Entonces los roles están scoped al tenant actual
- **Prioridad:** Alta
- **Módulo:** Roles Resource (FilamentShield)
- **Tipo:** CRUD + Lógica de negocio (gestión de permisos)

### HU-20: Ver Gráfico de Usuarios Global del Sistema
- **Actor:** Super Admin
- **Descripción:** Como super admin, quiero ver un gráfico donut de usuarios por rol en todos los tenants para entender la distribución de usuarios
- **Criterios de Aceptación:**
  - Dado que soy super_admin viendo el dashboard, Cuando el widget TotalUsuarios carga, Entonces veo un gráfico donut con conteos de Jugadores, Entrenadores y Administradores en todos los tenants
- **Prioridad:** Media
- **Módulo:** Widgets / TotalUsuarios
- **Tipo:** Lógica de negocio (vista)

### HU-21: Ver Gráfico de Equipos Global del Sistema
- **Actor:** Super Admin
- **Descripción:** Como super admin, quiero ver un gráfico donut de equipos por categoría en todos los tenants para entender la distribución de equipos
- **Criterios de Aceptación:**
  - Dado que soy super_admin viendo el dashboard, Cuando el widget TotalEquipos carga, Entonces veo un gráfico donut con conteos de equipos Profesional, Amateur y Formativo
- **Prioridad:** Media
- **Módulo:** Widgets / TotalEquipos
- **Tipo:** Lógica de negocio (vista)

### HU-22: Ver Gráfico de Torneos Global del Sistema
- **Actor:** Super Admin
- **Descripción:** Como super admin, quiero ver un gráfico circular de torneos por estado para monitorear la actividad de torneos
- **Criterios de Aceptación:**
  - Dado que soy super_admin, Cuando el widget TorneosActivos carga, Entonces veo un gráfico circular con conteos de torneos Activos, Finalizados y En progreso en todos los tenants
- **Prioridad:** Media
- **Módulo:** Widgets / TorneosActivos
- **Tipo:** Lógica de negocio (vista)

### HU-23: Ver Resumen de Pagos Global del Sistema
- **Actor:** Super Admin
- **Descripción:** Como super admin, quiero ver total de pagos recaudados este mes y un gráfico circular de pagos por mes para monitorear ingresos
- **Criterios de Aceptación:**
  - Dado que soy super_admin, Cuando el widget PagosDelMes carga, Entonces veo el monto total recaudado este mes en todos los tenants
  - Cuando el widget PagosPorMes carga, Entonces veo un gráfico circular de totales de pago de los últimos 12 meses
- **Prioridad:** Media
- **Módulo:** Widgets / PagosDelMes, PagosPorMes
- **Tipo:** Lógica de negocio (vista)

### HU-24: Ver Gráfico de Asistencia Global del Sistema
- **Actor:** Super Admin
- **Descripción:** Como super admin, quiero ver un gráfico de barras de asistencia (presentes vs ausentes) por mes para rastrear tendencias de participación
- **Criterios de Aceptación:**
  - Dado que soy super_admin, Cuando el widget AsistenciaPorMes carga, Entonces veo un gráfico de barras con conteos de presentes y ausentes de los últimos 12 meses en todos los tenants
- **Prioridad:** Media
- **Módulo:** Widgets / AsistenciaPorMes
- **Tipo:** Lógica de negocio (vista)

### HU-25: Ver Gráfico de Entrenamientos Global del Sistema
- **Actor:** Super Admin
- **Descripción:** Como super admin, quiero ver un gráfico de línea de entrenamientos por mes para monitorear la frecuencia de entrenamiento
- **Criterios de Aceptación:**
  - Dado que soy super_admin, Cuando el widget EntrenamientosPorMes carga, Entonces veo un gráfico de línea con conteos de entrenamientos de los últimos 12 meses en todos los tenants
- **Prioridad:** Media
- **Módulo:** Widgets / EntrenamientosPorMes
- **Tipo:** Lógica de negocio (vista)

### HU-26: Ver Estado de Aptitud de Jugadores
- **Actor:** Super Admin, Administrador
- **Descripción:** Como admin, quiero ver un gráfico donut de jugadores aptos vs no aptos para jugar para monitorear la salud general de jugadores
- **Criterios de Aceptación:**
  - Dado que veo el dashboard, Cuando el widget JugadoresNoAptos carga, Entonces veo un gráfico donut mostrando conteos de "Aptos" y "No aptos" basados en registros médicos
- **Prioridad:** Media
- **Módulo:** Widgets / JugadoresNoAptos
- **Tipo:** Lógica de negocio (vista)

### HU-27: Enviar Emails (Individual y Masivo)
- **Actor:** Super Admin, Administrador
- **Descripción:** Como admin, quiero enviar emails individualmente o en masa vía CSV para comunicarme con usuarios
- **Criterios de Aceptación:**
  - Dado que accedo a la página SendEmails, Cuando selecciono modo "individual" y proporciono email del destinatario, nombre, asunto y cuerpo, Entonces el email se envía vía Spring Mail Service
  - Cuando selecciono modo "masivo" y subo un archivo CSV con asunto y cuerpo, Entonces los emails se envían a todos los destinatarios en el CSV
  - Cuando la operación tiene éxito, Entonces veo una notificación de éxito
  - Cuando falla, Entonces veo una notificación de error
- **Prioridad:** Media
- **Módulo:** SendEmails Page
- **Tipo:** Lógica de negocio

### HU-55: Super Admin Recibe Notificación de Nuevo Tenant
- **Actor:** Sistema / Super Admin
- **Descripción:** Como super admin, quiero ser notificado cuando un nuevo tenant envía una solicitud de acceso para revisarla prontamente
- **Criterios de Aceptación:**
  - Dado que un nuevo tenant se crea vía onboarding, Cuando ApprovalService notifica a admins, Entonces usuarios con rol "admin" reciben una notificación por email con detalles del tenant y un link para revisar
- **Prioridad:** Media
- **Módulo:** ApprovalService / Notificaciones
- **Tipo:** Lógica de negocio (notificación)

### HU-56: Ver Widget de Solicitudes Pendientes
- **Actor:** Super Admin
- **Descripción:** Como super admin, quiero ver un widget mostrando solicitudes pendientes de tenant en mi dashboard para revisarlas rápidamente
- **Criterios de Aceptación:**
  - Dado que soy super_admin viendo el dashboard, Cuando existen solicitudes pendientes, Entonces puedo verlas en el widget SolicitudesPendientes
- **Prioridad:** Media
- **Módulo:** Widgets / SolicitudesPendientes
- **Tipo:** Lógica de negocio (vista)

---

## Historias del Administrador (Tenant Admin)

### HU-28: Ver Dashboard Scoped por Tenant
- **Actor:** Administrador
- **Descripción:** Como administrador de tenant, quiero ver un dashboard con estadísticas limitadas a mi tenant para monitorear la actividad de mi club
- **Criterios de Aceptación:**
  - Dado que soy Administrador (no super_admin), Cuando visito el dashboard, Entonces todos los widgets muestran datos filtrados por mi tenant_id únicamente
- **Prioridad:** Alta
- **Módulo:** Admin Dashboard
- **Tipo:** Lógica de negocio (vista)

### HU-29: Gestionar Equipos (CRUD)
- **Actor:** Administrador
- **Descripción:** Como administrador, quiero crear, ver, editar y eliminar equipos para organizar los equipos de mi club
- **Criterios de Aceptación:**
  - Dado que accedo al recurso Equipos, Cuando listo equipos, Entonces veo nombre, categoría, logo, ubicación, contacto, subcategoría y fechas de creación/actualización
  - Cuando creo/edito un equipo, Entonces puedo establecer nombre, categoría (profesional/amateur/formativo), logo_equipo, ubicación, contacto y subcategoría
  - Cuando filtro por categoría, subcategoría, ubicación o fecha de creación, Entonces los resultados se filtran
  - Todas las operaciones están limitadas a mi tenant
- **Prioridad:** Alta
- **Módulo:** Equipos Resource
- **Tipo:** CRUD

### HU-30: Gestionar Asignaciones Equipo-Usuario (EquipoUsers) (CRUD)
- **Actor:** Administrador
- **Descripción:** Como administrador, quiero asignar jugadores a equipos y gestionar sus períodos de membresía para organizar las listas de equipos
- **Criterios de Aceptación:**
  - Dado que accedo al recurso EquipoUsers, Cuando listo asignaciones, Entonces veo equipo, jugador, fecha_inicio y fecha_fin
  - Cuando creo/edito una asignación, Entonces puedo seleccionar un equipo, un jugador (filtrado a Jugadores en mi tenant a menos que sea super_admin) y fechas de inicio/fin
  - Cuando filtro por "solo jugadores activos", Entonces veo solo registros donde fecha_fin es nulo
- **Prioridad:** Alta
- **Módulo:** EquipoUsers Resource
- **Tipo:** CRUD

### HU-31: Gestionar Entrenamientos (CRUD)
- **Actor:** Administrador
- **Descripción:** Como administrador, quiero crear, ver, editar y eliminar sesiones de entrenamiento para programar y gestionar prácticas del equipo
- **Criterios de Aceptación:**
  - Dado que accedo al recurso Entrenamientos, Cuando listo entrenamientos, Entonces veo nombre, fecha, hora, ubicación y equipo
  - Cuando creo/edito un entrenamiento, Entonces puedo establecer nombre, fecha, hora, ubicación y equipo
  - Todas las operaciones están limitadas a mi tenant vía trait BelongsToTenant
- **Prioridad:** Alta
- **Módulo:** Entrenamientos Resource
- **Tipo:** CRUD

### HU-32: Gestionar Asistencia a Entrenamientos (CRUD)
- **Actor:** Administrador
- **Descripción:** Como administrador, quiero registrar y editar asistencia para sesiones de entrenamiento para rastrear la participación de jugadores
- **Criterios de Aceptación:**
  - Dado que accedo al recurso AsistenciaEntrenamientos, Cuando listo asistencia, Entonces veo fecha/hora/ubicación del entrenamiento, nombre del jugador y toggle presente
  - Cuando creo/edito asistencia, Entonces puedo seleccionar el entrenamiento, el jugador y alternar presencia
  - Cuando filtro por presente/ausente, jugador o rango de fechas, Entonces los resultados se filtran
- **Prioridad:** Alta
- **Módulo:** AsistenciaEntrenamientos Resource
- **Tipo:** CRUD

### HU-33: Gestionar Partidos (CRUD)
- **Actor:** Administrador
- **Descripción:** Como administrador, quiero crear, ver, editar y eliminar partidos para gestionar el calendario de competiciones
- **Criterios de Aceptación:**
  - Dado que accedo al recurso Partidos, Cuando listo partidos, Entonces veo equipo local, equipo visitante, fecha, hora, resultado y fecha de creación
  - Cuando creo/edito un partido, Entonces puedo establecer fecha, hora, equipo_local, equipo_visitante, resultado y torneo
  - Cuando filtro por equipo local, equipo visitante o rango de fechas, Entonces los resultados se filtran
- **Prioridad:** Alta
- **Módulo:** Partidos Resource
- **Tipo:** CRUD

### HU-34: Gestionar Pagos (CRUD)
- **Actor:** Administrador
- **Descripción:** Como administrador, quiero crear, ver, editar y eliminar pagos para rastrear transacciones financieras
- **Criterios de Aceptación:**
  - Dado que accedo al recurso Pagos, Cuando listo pagos, Entonces veo usuario, fecha, monto (COP), estado (badge) y fecha de creación
  - Cuando creo/edito un pago, Entonces puedo seleccionar un usuario (filtrado a mi tenant), establecer fecha, monto y estado (pendiente/pagado/rechazado)
  - Cuando filtro por usuario, estado, rango de fechas o rango de montos, Entonces los resultados se filtran
- **Prioridad:** Alta
- **Módulo:** Pagos Resource
- **Tipo:** CRUD

### HU-35: Gestionar Perfiles de Jugadores (CRUD)
- **Actor:** Administrador
- **Descripción:** Como administrador, quiero crear, ver, editar y eliminar perfiles de jugadores para gestionar datos físicos y posicionales de jugadores
- **Criterios de Aceptación:**
  - Dado que accedo al recurso JugadorPerfils, Cuando listo perfiles, Entonces veo nombre del jugador, posición (badge), dorsal, altura, peso, pierna_habil (badge) y fecha de creación
  - Cuando creo/edito un perfil, Entonces puedo seleccionar un usuario, establecer dorsal (0-99), posición (portero/defensa/mediocampo/delantero), altura (100-230cm), peso (30-150kg) y pierna_habil (derecha/izquierda/ambas)
- **Prioridad:** Alta
- **Módulo:** JugadorPerfils Resource
- **Tipo:** CRUD

### HU-36: Gestionar Registros Médicos (HistorialMedico) (CRUD)
- **Actor:** Administrador
- **Descripción:** Como administrador, quiero crear, ver, editar y eliminar registros médicos para rastrear la salud y estado de aptitud de jugadores
- **Criterios de Aceptación:**
  - Dado que accedo al recurso HistorialMedicos, Cuando listo registros, Entonces veo jugador, tipo_lesion (badge), descripción, gravedad (badge), fecha_inicio, fecha_fin, apto (icono) y fecha de registro
  - Cuando creo/edito un registro, Entonces puedo seleccionar un usuario, establecer tipo_lesion (lesión/enfermedad/control), descripción, gravedad (leve/media/grave), toggle apto y rango de fechas
  - Cuando filtro por jugador, tipo, gravedad, estado apto o rango de fechas, Entonces los resultados se filtran
- **Prioridad:** Alta
- **Módulo:** HistorialMedicos Resource
- **Tipo:** CRUD

### HU-37: Gestionar Registros de Rendimiento (CRUD)
- **Actor:** Administrador
- **Descripción:** Como administrador, quiero crear, ver, editar y eliminar registros de rendimiento de jugadores para rastrear estadísticas y evaluaciones de partidos
- **Criterios de Aceptación:**
  - Dado que accedo al recurso Rendimientos, Cuando listo registros, Entonces veo jugador, minutos, goles, asistencias, tarjetas amarillas, tarjetas rojas, evaluación y fecha de registro
  - Cuando creo/edito un registro, Entonces puedo seleccionar un usuario y partido, y establecer minutos_jugados (0-120), goles, asistencias, tarjetas_amarillas, tarjetas_rojas y evaluación (0-10)
  - Cuando filtro por jugador, rango de fechas o rango de evaluación, Entonces los resultados se filtran
- **Prioridad:** Alta
- **Módulo:** Rendimientos Resource
- **Tipo:** CRUD

### HU-38: Gestionar Notificaciones (CRUD)
- **Actor:** Administrador
- **Descripción:** Como administrador, quiero crear, ver, editar y eliminar notificaciones para gestionar comunicaciones dentro del sistema
- **Criterios de Aceptación:**
  - Dado que accedo al recurso Notificacions, Cuando listo notificaciones, Entonces veo título, mensaje (truncado), leído (icono) y fecha
  - Cuando creo/edito una notificación, Entonces puedo establecer título, mensaje y toggle leído
  - Cuando filtro por estado leído/no leído o fecha, Entonces los resultados se filtran
- **Prioridad:** Media
- **Módulo:** Notificacions Resource
- **Tipo:** CRUD

### HU-39: Gestionar Instalaciones (CRUD)
- **Actor:** Administrador
- **Descripción:** Como administrador, quiero crear, ver, editar y eliminar instalaciones para gestionar la infraestructura del club
- **Criterios de Aceptación:**
  - Dado que accedo al recurso Instalacions, Cuando listo instalaciones, Entonces veo nombre, tipo (badge), ubicación, capacidad, estado (badge) y fecha de creación
  - Cuando creo/edito una instalación, Entonces puedo establecer nombre, tipo (cancha/gimnasio/piscina/estadio), ubicación, capacidad y estado (disponible/ocupada/mantenimiento)
  - Cuando filtro por tipo, estado o fecha de creación, Entonces los resultados se filtran
- **Prioridad:** Media
- **Módulo:** Instalacions Resource
- **Tipo:** CRUD

### HU-40: Ver Gráfico de Pagos Mensuales (Scoped por Tenant)
- **Actor:** Administrador
- **Descripción:** Como administrador, quiero ver un gráfico circular de pagos por mes para mi tenant para analizar tendencias financieras de mi club
- **Criterios de Aceptación:**
  - Dado que soy Administrador, Cuando el widget PagosPorMes carga, Entonces veo un gráfico circular mostrando totales de pago de los últimos 12 meses, filtrados por mi tenant_id
- **Prioridad:** Media
- **Módulo:** Widgets / PagosPorMes
- **Tipo:** Lógica de negocio (vista)

---

## Historias del Entrenador (Coach)

### HU-46: Acceder al Panel Admin (como Entrenador)
- **Actor:** Entrenador
- **Descripción:** Como entrenador, quiero acceder al panel admin (vía enrutamiento basado en rol) para gestionar las actividades de mi equipo
- **Criterios de Aceptación:**
  - Dado que tengo el rol "Entrenador", Cuando inicio sesión, Entonces soy redirigido a `/admin` (igual que Administrador vía LoginResponse)
  - Dado que tengo permisos otorgados vía FilamentShield, Cuando accedo a recursos para los que tengo permiso, Entonces puedo realizar operaciones CRUD
- **Prioridad:** Alta
- **Módulo:** Autenticación / Panel Admin
- **Tipo:** Transversal

### HU-47: Gestionar Entrenamientos (Permisos de Entrenador vía Shield)
- **Actor:** Entrenador
- **Descripción:** Como entrenador, quiero gestionar sesiones de entrenamiento para mi equipo para programar prácticas
- **Criterios de Aceptación:**
  - Dado que tengo los permisos Shield apropiados, Cuando accedo a Entrenamientos, Entonces puedo crear, ver, editar y eliminar entrenamientos
  - Todas las operaciones están limitadas a mi tenant vía BelongsToTenant
- **Prioridad:** Alta
- **Módulo:** Entrenamientos Resource
- **Tipo:** CRUD

### HU-48: Gestionar Asistencia (Permisos de Entrenador vía Shield)
- **Actor:** Entrenador
- **Descripción:** Como entrenador, quiero registrar asistencia para sesiones de entrenamiento para rastrear qué jugadores asisten
- **Criterios de Aceptación:**
  - Dado que tengo los permisos Shield apropiados, Cuando accedo a AsistenciaEntrenamientos, Entonces puedo crear, ver, editar y eliminar registros de asistencia
- **Prioridad:** Alta
- **Módulo:** AsistenciaEntrenamientos Resource
- **Tipo:** CRUD

### HU-49: Gestionar Registros de Rendimiento (Permisos de Entrenador vía Shield)
- **Actor:** Entrenador
- **Descripción:** Como entrenador, quiero registrar y evaluarar el rendimiento de jugadores en partidos para rastrear el desarrollo de jugadores
- **Criterios de Aceptación:**
  - Dado que tengo los permisos Shield apropiados, Cuando accedo a Rendimientos, Entonces puedo crear, ver, editar y eliminar registros de rendimiento con estadísticas (minutos, goles, asistencias, tarjetas, evaluación)
- **Prioridad:** Alta
- **Módulo:** Rendimientos Resource
- **Tipo:** CRUD

### HU-50: Gestionar Partidos (Permisos de Entrenador vía Shield)
- **Actor:** Entrenador
- **Descripción:** Como entrenador, quiero gestionar registros de partidos y resultados para rastrear el historial de competiciones
- **Criterios de Aceptación:**
  - Dado que tengo los permisos Shield apropiados, Cuando accedo a Partidos, Entonces puedo crear, ver, editar y eliminar partidos
- **Prioridad:** Alta
- **Módulo:** Partidos Resource
- **Tipo:** CRUD

### HU-51: Ver Equipos (Permisos de Entrenador vía Shield)
- **Actor:** Entrenador
- **Descripción:** Como entrenador, quiero ver información del equipo para ver los detalles de mi equipo
- **Criterios de Aceptación:**
  - Dado que tengo los permisos Shield apropiados, Cuando accedo a Equipos, Entonces puedo ver y gestionar datos del equipo
- **Prioridad:** Media
- **Módulo:** Equipos Resource
- **Tipo:** CRUD

---

## Historias del Jugador (Player)

### HU-41: Ver Dashboard de Jugador
- **Actor:** Jugador
- **Descripción:** Como jugador, quiero ver mi dashboard personal para ver rápidamente mi resumen de actividad
- **Criterios de Aceptación:**
  - Dado que soy Jugador, Cuando inicio sesión, Entonces soy redirigido a `/jugador` y veo mi dashboard personal con el dashboard Filament por defecto
- **Prioridad:** Alta
- **Módulo:** Jugador Dashboard
- **Tipo:** Lógica de negocio (vista)

### HU-42: Ver Mi Gráfico de Entrenamientos
- **Actor:** Jugador
- **Descripción:** Como jugador, quiero ver un gráfico de línea de entrenamientos por mes para rastrear mi schedule de entrenamiento
- **Criterios de Aceptación:**
  - Dado que estoy logueado como jugador, Cuando el widget EntrenamientosPorMes carga en el panel Jugador, Entonces veo un gráfico de línea mostrando conteos de entrenamientos de los últimos 12 meses en mi tenant
- **Prioridad:** Media
- **Módulo:** Jugador Widgets / EntrenamientosPorMes
- **Tipo:** Lógica de negocio (vista)

### HU-43: Ver Mi Gráfico de Asistencia
- **Actor:** Jugador
- **Descripción:** Como jugador, quiero ver un gráfico de barras de mi asistencia (presente vs ausente) por mes para rastrear mi participación
- **Criterios de Aceptación:**
  - Dado que estoy logueado como jugador, Cuando el widget AsistenciaPorMes carga, Entonces veo un gráfico de barras con mis conteos personales de presente/ausente de los últimos 12 meses (filtrados por mi user_id)
- **Prioridad:** Media
- **Módulo:** Jugador Widgets / AsistenciaPorMes
- **Tipo:** Lógica de negocio (vista)

### HU-44: Gestionar Mi Perfil de Jugador
- **Actor:** Jugador
- **Descripción:** Como jugador, quiero ver y editar mi información de perfil de jugador (posición, dorsal, altura, peso, pierna hábil) para que mis datos estén actualizados
- **Criterios de Aceptación:**
  - Dado que accedo al recurso JugadorPerfils en el panel Jugador, Cuando listo perfiles, Entonces veo perfiles limitados a mi contexto
  - Cuando creo/edito mi perfil, Entonces puedo establecer user_id, dorsal, posición, altura, peso y pierna_habil
- **Prioridad:** Alta
- **Módulo:** Jugador Resources / JugadorPerfils
- **Tipo:** CRUD

### HU-45: Editar Mi Perfil Personal (Página PlayerProfile)
- **Actor:** Jugador
- **Descripción:** Como jugador, quiero editar mi perfil personal (nombre, email, teléfono, avatar) para que la información de mi cuenta esté actualizada
- **Criterios de Aceptación:**
  - Dado que accedo a "Mi Perfil" en el panel Jugador, Cuando actualizo mi nombre, email, teléfono o avatar y guardo, Entonces mi registro de usuario se actualiza y veo una notificación de éxito
- **Prioridad:** Alta
- **Módulo:** Jugador Pages / PlayerProfile
- **Tipo:** Lógica de negocio

---

## Historias Multi-Tenant / Sistema

### HU-52: Aislamiento de Datos por Tenant vía Trait BelongsToTenant
- **Actor:** Sistema (todos los roles)
- **Descripción:** Como sistema, quiero filtrar automáticamente todas las consultas de modelos por tenant_id (excepto para super_admin) para que los datos estén correctamente aislados entre tenants
- **Criterios de Aceptación:**
  - Dado que un modelo usa el trait BelongsToTenant, Cuando un usuario no-super_admin consulta el modelo, Entonces solo se devuelven registros que coinciden con su tenant_id
  - Dado que cualquier usuario crea un modelo con BelongsToTenant, Cuando el registro se crea, Entonces tenant_id se asigna automáticamente desde el tenant del usuario
- **Prioridad:** Alta
- **Módulo:** Trait BelongsToTenant
- **Tipo:** Transversal (infraestructura)

---

## Resumen por Actor y Tipo

### Distribución por Actor

| Actor | Historias | IDs |
|-------|-----------|-----|
| **Visitante** | 6 | HU-00, HU-01, HU-02, HU-03, HU-04, HU-54 |
| **Super Admin** | 20 | HU-05-HU-27, HU-52, HU-55, HU-56 |
| **Administrador** | 27 | HU-05-HU-13, HU-15, HU-18, HU-26-HU-40, HU-52, HU-53 |
| **Entrenador** | 13 | HU-05-HU-13, HU-46-HU-52 |
| **Jugador** | 13 | HU-05-HU-12, HU-41-HU-45, HU-52 |

### Clasificación por Tipo

| Tipo | Cantidad | Historias |
|------|----------|-----------|
| **CRUD** | 17 | HU-17, HU-18, HU-19, HU-29-HU-39, HU-44, HU-47-HU-51 |
| **Lógica de Negocio** | 14 | HU-01, HU-02, HU-04, HU-16, HU-27, HU-45, HU-53, HU-54, HU-55 |
| **Vistas/Dashboards** | 14 | HU-00, HU-03, HU-14, HU-20-HU-26, HU-28, HU-40, HU-41-HU-43, HU-56 |
| **Transversales** | 11 | HU-05-HU-13, HU-52 |

### Matriz de Acceso por Módulo

| Módulo | Visitante | Super Admin | Administrador | Entrenador | Jugador |
|--------|-----------|-------------|---------------|------------|---------|
| Landing Page | ✅ | ✅ | ✅ | ✅ | ✅ |
| Solicitud Acceso | ✅ | — | — | — | — |
| Onboarding | ✅ | — | — | — | — |
| Tenant Requests | — | ✅ | — | — | — |
| Tenants CRUD | — | ✅ | — | — | — |
| Usuarios CRUD | — | ✅ | ✅ | ⚠️ | — |
| Roles/Permisos | — | ✅ | ⚠️ | ⚠️ | — |
| Equipos CRUD | — | ✅ | ✅ | ⚠️ | — |
| EquipoUsers CRUD | — | ✅ | ✅ | ⚠️ | — |
| Entrenamientos CRUD | — | ✅ | ✅ | ✅ | — |
| Asistencia CRUD | — | ✅ | ✅ | ✅ | — |
| Partidos CRUD | — | ✅ | ✅ | ✅ | — |
| Torneos CRUD | — | ✅ | ✅ | ⚠️ | — |
| Pagos CRUD | — | ✅ | ✅ | — | — |
| JugadorPerfils CRUD | — | ✅ | ✅ | ⚠️ | ✅ |
| HistorialMedico CRUD | — | ✅ | ✅ | ⚠️ | — |
| Rendimientos CRUD | — | ✅ | ✅ | ✅ | — |
| Notificaciones CRUD | — | ✅ | ✅ | ⚠️ | — |
| Instalaciones CRUD | — | ✅ | ✅ | ⚠️ | — |
| Calendario | — | ✅ | ✅ | ⚠️ | — |
| SendEmails | — | ✅ | ✅ | — | — |
| Dashboard Admin | — | ✅ | ✅ | ✅ | — |
| Dashboard Jugador | — | — | — | — | ✅ |
| Configuración Cuenta | ✅* | ✅ | ✅ | ✅ | ✅ |
| Exportar Datos | — | ✅ | ✅ | ⚠️ | — |

> ✅ = Acceso completo  
> ⚠️ = Acceso según permisos Shield  
> — = Sin acceso  
> ✅* = Solo páginas públicas (Landing, Onboarding)

---

*Documento generado el 7 de abril de 2026*  
**FitControl** — Plataforma SaaS Multi-Tenant para Gestión de Clubes Deportivos
