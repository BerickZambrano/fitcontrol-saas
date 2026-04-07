# FitControl — Módulos del Sistema

> **Plataforma SaaS Multi-Tenant para Gestión de Clubes Deportivos**  
> Laravel 12 + Filament v4 + React + Inertia.js

---

## 📦 Módulos Identificados

### 1. Gestión Multi-Tenant (SaaS)
- Registro y aprobación de clubes/inquilinos
- Onboarding con carga de documentos (RUT, Cámara de Comercio)
- Planes de suscripción (mensual/anual)
- Aislamiento de datos por tenant
- Estados del tenant: `pendiente`, `activo`, `suspendido`
- Tipos de club: `formativo`, `amateur`, `profesional`

**Entidades:** `Tenant`  
**Rutas públicas:** `/solicitar-acceso`, `/onboarding`, `/register-admin/{token}`  
**Recursos Filament:** `TenantResource`  
**Páginas:** `TenantRequests` (super_admin)

---

### 2. Administración de Usuarios y RBAC
- CRUD completo de usuarios
- Roles: `super_admin`, `Administrador`, `Entrenador`, `Jugador`
- Permisos granulares vía FilamentShield + Spatie Permission
- Políticas de autorización por recurso (16 policies)
- Filtros por rol y rango de fechas
- Exportación a PDF/XLSX

**Entidades:** `User`, `Role`, `Permission`  
**Recursos Filament:** `UsersResource`, `RoleResource` (Shield)

---

### 3. Gestión de Equipos
- Creación de equipos por categoría (profesional/amateur/formativo)
- Subcategorías (Sub-10 hasta Sub-18, Profesional)
- Logos e información de contacto
- Historial de membresías equipo-jugador
- Filtros por categoría, subcategoría y ubicación

**Entidades:** `Equipo`, `EquipoUser`  
**Recursos Filament:** `EquipoResource`, `EquipoUserResource`  
**Grupo de navegación:** "Equipos"

---

### 4. Perfiles de Jugadores
- Posición de juego (portero/defensa/mediocampo/delantero)
- Número de dorsal, altura (cm), peso (kg)
- Pierna hábil (derecha/izquierda/ambas)
- Panel exclusivo `/jugador` para jugadores
- Edición de perfil personal (nombre, email, teléfono, avatar)

**Entidades:** `JugadorPerfil`  
**Recursos Filament:** `JugadorPerfilResource` (Admin + Jugador panel)  
**Páginas:** `PlayerProfile` (Ambos paneles)

---

### 5. Entrenamientos
- Programación de sesiones de entrenamiento
- Asignación a equipos
- Fecha, hora y ubicación
- Filtros por equipo y rango de fechas

**Entidades:** `Entrenamiento`  
**Recursos Filament:** `EntrenamientoResource`  
**Grupo de navegación:** "Entrenamientos"

---

### 6. Asistencia a Entrenamientos
- Registro de presencia/ausencia por jugador
- Toggle inline en tablas (activar/desactivar)
- Filtros por jugador, fecha, estado
- Filtro "solo presentes"

**Entidades:** `AsistenciaEntrenamiento`  
**Recursos Filament:** `AsistenciaEntrenamientoResource`

---

### 7. Torneos
- Creación y gestión de torneos
- Estados: `próximo`, `activo`, `completado`
- Asociación de equipos a torneos (tabla pivote `equipo_torneo`)
- Filtros por estado y categoría

**Entidades:** `Torneo`  
**Recursos Filament:** `TorneoResource`  
**Grupo de navegación:** "Competencias"

---

### 8. Partidos
- Programación de partidos (local vs visitante)
- Resultados/marcadores
- Vinculación a torneos
- Fecha, hora y lugar

**Entidades:** `Partido`  
**Recursos Filament:** `PartidoResource`  
**Grupo de navegación:** "Competencias"

---

### 9. Rendimiento y Estadísticas
- Minutos jugados por partido
- Goles, asistencias
- Tarjetas amarillas y rojas
- Evaluación numérica (0-10)
- Exportación individual (PDF) y masiva (XLSX)
- Filtros por jugador, rango de fechas y calificación

**Entidades:** `Rendimiento`  
**Recursos Filament:** `RendimientoResource`

---

### 10. Historial Médico
- Registro de lesiones y enfermedades
- Gravedad: `leve`, `media`, `grave`
- Tipo: `lesión`, `enfermedad`, `control`
- Estado de aptitud deportiva (booleano)
- Fechas de inicio y fin
- Badges codificados por colores

**Entidades:** `HistorialMedico`  
**Recursos Filament:** `HistorialMedicoResource`

---

### 11. Gestión de Pagos
- Registro de pagos por usuario
- Estados: `pendiente`, `pagado`, `rechazado`
- Monto en pesos colombianos (COP)
- Filtros por usuario, estado, rango de fechas y montos
- Widget de pagos del mes en dashboard

**Entidades:** `Pago`  
**Recursos Filament:** `PagoResource`  
**Grupo de navegación:** "Finanzas"  
**Widgets:** `PagosDelMes`

---

### 12. Instalaciones
- Gestión de venues (cancha, gimnasio, piscina, estadio)
- Capacidad y estado (`disponible`, `ocupada`, `mantenimiento`)
- Ubicación física
- Badges codificados por colores

**Entidades:** `Instalacion`  
**Recursos Filament:** `InstalacionResource`

---

### 13. Notificaciones Internas
- Sistema de notificaciones in-app
- Estado leído/no leído
- Título y cuerpo del mensaje
- Filtros por estado de lectura y fecha

**Entidades:** `Notificacion`  
**Recursos Filament:** `NotificacionResource`

---

### 14. Calendario Unificado
- Vista de calendario con entrenamientos y partidos
- Codificación por colores:
  - 🔵 Azul (`#2563eb`) = Entrenamientos
  - 🟢 Verde (`#16a34a`) = Partidos

**Página:** `Calendario` (`/admin/calendario`)

---

### 15. Dashboard y Analíticas

#### Panel Admin (`/admin`)
| Widget | Tipo | Descripción |
|--------|------|-------------|
| `TotalUsuarios` | ApexChart Donut | Usuarios por rol |
| `TotalEquipos` | ApexChart Donut | Equipos por categoría |
| `TorneosActivos` | ApexChart Pie | Torneos por estado |
| `AsistenciaPorMes` | ApexChart Barras | Asistencia últimos 12 meses |
| `EntrenamientosPorMes` | Estadístico | Entrenamientos mensuales |
| `JugadoresNoAptos` | ApexChart Donut | Jugadores aptos vs no aptos |
| `PagosDelMes` | StatsOverview | Total pagado en el mes |

#### Panel Jugador (`/jugador`)
| Widget | Tipo |
|--------|------|
| `AsistenciaPorMes` | ApexChart Barras |
| `EntrenamientosPorMes` | Estadístico |
| `LogoutWidget` | Atajo |

---

### 16. Sistema de Email
- Envío de correos de aprobación/rechazo de tenants
- Página `SendEmails` para envíos masivos/individuales
- Integración con servicio externo Spring mail
- Correos encolados (trait `Queueable`)

**Mailables:** `TenantApprovedMail`, `TenantRejectedMail`  
**Página:** `SendEmails`

---

### 17. Configuración de Cuenta
- Perfil: nombre, email, avatar
- Cambio de contraseña (requiere contraseña actual)
- Autenticación 2FA (TOTP con QR y códigos de recuperación)
- Eliminación de cuenta (con confirmación)
- Preferencias de apariencia

**Rutas:** `/settings/profile`, `/settings/password`, `/settings/two-factor`, `/settings/appearance`  
**Componentes Livewire:** `Profile`, `Password`, `TwoFactor`, `Appearance`, `DeleteUserForm`

---

### 18. Landing Page y Páginas Públicas
- Landing page con Inertia.js + React
- Formulario público de solicitud de acceso (`/solicitar-acceso`)
- Onboarding completo con carga de documentos (`/onboarding`)
- Dashboard post-auth (`/dashboard`)

**Controladores:** `TenantRequestController`, `OnboardingController`, `AdminRegisterController`

---

## 📊 Resumen Técnico

| Categoría | Cantidad |
|-----------|----------|
| Modelos Eloquent | 16 |
| Tablas de Base de Datos | ~20 |
| Filament Resources | 15 |
| Filament Pages | 5 |
| Filament Widgets | 11 |
| Laravel Policies | 16 |
| Roles | 4 |
| Paneles Filament | 2 |
| Controladores | 4 |
| Mailables | 2 |
| Componentes Livewire | 7 |
| Notificaciones | 1 |

---

## 🏗️ Arquitectura de Paneles

| Panel | Ruta | Tema | Audiencia |
|-------|------|------|-----------|
| **Admin** | `/admin` | Azul (`Color::Blue`) | Administradores, Staff |
| **Jugador** | `/jugador` | Rojo (`Color::Red`) | Jugadores |

---

## 🔐 Roles del Sistema

| Rol | Panel | Permisos |
|-----|-------|----------|
| `super_admin` | Admin | Acceso total, gestión de tenants |
| `Administrador` | Admin | Gestión completa del tenant |
| `Entrenador` | Admin | Entrenamientos, asistencia, partidos, rendimiento |
| `Jugador` | Jugador | Perfil propio, estadísticas personales |

---

## 🗄️ Modelo de Datos

| Entidad | Relación Principal | Tenant-Scoped |
|---------|-------------------|---------------|
| `Tenant` | HasMany User, HasMany Equipo | N/A |
| `User` | BelongsTo Tenant, HasRoles | ✅ |
| `Equipo` | BelongsTo Tenant, BelongsToMany Torneo | ✅ |
| `EquipoUser` | BelongsTo Equipo, BelongsTo User | ✅ |
| `JugadorPerfil` | BelongsTo User | ✅ |
| `Entrenamiento` | BelongsTo Equipo, HasMany Asistencia | ✅ |
| `AsistenciaEntrenamiento` | BelongsTo Entrenamiento, BelongsTo User | ✅ |
| `Torneo` | HasMany Partido, BelongsToMany Equipo | ✅ |
| `Partido` | BelongsTo Equipo (local/visitante), BelongsTo Torneo | ✅ |
| `Rendimiento` | BelongsTo User, BelongsTo Partido | ❌ |
| `HistorialMedico` | BelongsTo User | ✅ |
| `Pago` | BelongsTo User | ✅ |
| `Instalacion` | — | ✅ |
| `Notificacion` | BelongsTo User | ✅ |

---

*Documento generado el 7 de abril de 2026*  
**FitControl** — Plataforma SaaS Multi-Tenant para Gestión de Clubes Deportivos
