# Plan de Implementación de Nuevos Features

Este documento detalla la arquitectura y los pasos necesarios para implementar los requerimientos solicitados: Rol de Árbitro, Traspasos, Convocatorias y Bloqueo de Tenant.

## 1. Rol de Árbitro y Gestión de Partidos

### 1.1 Nuevo Panel y Rol
- **Spatie Permissions:** Crear el rol `Arbitro`.
- **Panel de Filament:** Crear `ArbitroPanelProvider.php` (ruta `/arbitro`).
- **Control de Acceso:** Modificar el método `canAccessPanel` en `User.php` para permitir el acceso al panel si tiene el rol `Arbitro`.

### 1.2 Asignación y Aceptación de Partidos
- **Modificación de `Partido.php`:**
  - Agregar columna `arbitro_id` (nullable) referenciando a `users`.
  - Agregar columna `estado_arbitro` (enum: `pendiente`, `aceptado`, `rechazado`).
  - Agregar columna `estado_partido` (enum: `programado`, `en_juego`, `finalizado`).
- **Flujo en Panel Árbitro:**
  - Listado de partidos asignados en estado `pendiente`.
  - Acciones (Actions) en la tabla para "Aceptar" o "Rechazar".

### 1.3 Registro de Novedades y Sanciones post-partido
- **Nuevos Modelos:**
  - `IncidenciaPartido`: Relaciona `partido_id`, `jugador_id`, `tipo` (amarilla, roja, lesion, observacion), `minuto`, `descripcion`.
  - `Sancion`: Relaciona `jugador_id`, `partido_id_origen`, `cantidad_partidos_suspension`, `partidos_cumplidos`, `estado` (activa, cumplida).
- **Flujo:**
  - Cuando el partido pasa a `finalizado`, se habilita un botón "Registrar Novedades".
  - Se abre un modal/página para añadir incidencias. Si se añade una "roja" o falta grave, se dispara un Observer que crea automáticamente una `Sancion`.
  - En el **Panel de Jugador**, se crea un widget o página para visualizar sus Sanciones Activas.
  - El Árbitro podrá tener un recurso de solo lectura para ver el historial de sanciones globales o del torneo.

## 2. Convocatorias de Jugadores

Para manejar las convocatorias a los partidos de forma eficiente:

- **Nuevo Modelo / Pivot `Convocatoria`:**
  - Tabla `convocatorias` con `partido_id`, `jugador_id`, `equipo_id`, `estado_asistencia` (convocado, confirmado, rechazado, asistio, falto).
- **Flujo (Entrenador):**
  - En el recurso de Partido, el Entrenador usa una acción "Convocar Jugadores" que abre un listado de su equipo (filtrando los que tienen `Sancion` activa para no dejarlos convocar).
  - **Obligatoriedad:** Solo los jugadores que tengan el estado `confirmado` podrán ser añadidos a la alineación titular o banca. Si no confirman, el sistema bloqueará su selección.
- **Flujo (Jugador):**
  - En el Panel de Jugador, recibe una Notificación.
  - Ve la convocatoria y **debe** Confirmar Asistencia o Rechazarla para poder jugar.

## 3. Traspasos de Jugadores (Cambiar de Equipo)

- **Nuevo Modelo `Traspaso`:**
  - Campos: `jugador_id`, `equipo_origen_id`, `equipo_destino_id`, `estado` (pendiente, aprobado, rechazado).
- **Flujo:**
  - El **equipo de destino** (el club que quiere fichar al jugador) inicia la solicitud buscando el perfil del jugador.
  - El administrador/entrenador del `equipo_origen` recibe una notificación.
  - En su panel, el `equipo_origen` ve la lista de "Solicitudes de Traspaso" y puede Aceptar o Rechazar.
  - Si acepta, un Observer actualiza la tabla pivot `historial_equipo` (cerrando la fecha fin del origen y creando el registro en el destino).

## 4. Bloqueo de Tenant y Paywall (Candadito)

### 4.1 Lógica de Bloqueo a Nivel Base de Datos
- **Modelo `Tenant.php`:**
  - Agregar columna `is_locked` (boolean, default false) o `estado_pago` (enum: al_dia, pendiente).
- **Flujo Simulado de Pagos:**
  - Por ahora, el sistema será **manual/simulado**. En el panel Super Admin existirá un botón/acción para marcar a un Tenant como "Al día" o "Bloqueado", simulando el pago sin integrar pasarelas de pago reales.
- **Middleware Global `CheckTenantPayment`:**
  - Se aplicará a todas las rutas de los paneles (Admin, Entrenador, Jugador).
  - Si `is_locked` es true, redirige a una vista central de "Pago Requerido" (el candadito), donde el sistema informará que las funciones están bloqueadas por falta de pago.

### 4.2 Cierre en Tiempo Real por WebSockets
- **Infraestructura:** El proyecto ya cuenta con el setup de Broadcasting con Pusher (`broadcasting.php`).
- **Evento Laravel `TenantLocked`:**
  - Cuando el Super Admin simula el bloqueo (cambia el estado de un tenant a bloqueado), se dispara este evento en un canal privado `tenant.{id}`.
- **Frontend (Echo.js / Filament):**
  - Inyectar un script en `panels::head.end` o usar los hooks de Echo integrados en Filament para escuchar `TenantLocked`.
  - Al recibir el evento, hacer un `window.location.reload()`, lo que obligará al navegador a recargar y atrapará al usuario en el middleware `CheckTenantPayment` instantáneamente.
