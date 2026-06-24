# FitControl — Documento de Especificación de Requisitos de Software (SRS)

Este documento detalla los **Requisitos Funcionales (RF)** y **Requisitos No Funcionales (RNF)** del proyecto **FitControl**, estructurados por módulos, con su respectivo identificador, descripción, prioridad y actores involucrados.

---

## 👥 Actores del Sistema y Roles

| Actor / Rol | Descripción | Nivel de Acceso / Panel |
| :--- | :--- | :--- |
| **Visitante** | Usuario no autenticado que ingresa al sitio web. | Público / Landing Page |
| **Super Administrador (`super_admin`)** | Administrador de la infraestructura SaaS global de FitControl. | Panel Global (`/admin`) |
| **Administrador del Club (`Administrador`)** | Gestor o dueño de una academia o club deportivo específico (Inquilino). | Panel del Club (`/admin`) |
| **Entrenador (`Entrenador`)** | Personal deportivo encargado de la preparación técnica y táctica. | Panel de Entrenador (`/entrenador`) |
| **Médico (`Medico`)** | Personal de salud o departamento médico encargado de la integridad física de los deportistas. | Panel de Médico (`/medico`) |
| **Jugador (`Jugador`)** | Deportista afiliado al club que consume su información deportiva y de rendimiento. | Panel de Jugador (`/jugador`) |
| **Árbitro (`Arbitro`)** | Personal encargado de dirigir los partidos y reportar las incidencias de los torneos. | Panel de Árbitro (`/arbitro`) |

---

## 📋 1. Requisitos Funcionales (RF)

### Módulo 1: Gestión Multi-Tenant (SaaS)
| ID | Módulo | Nombre del Requisito | Descripción | Prioridad | Actor |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **RF-1.1** | Gestión Multi-Tenant | Envío de Solicitud de Acceso | Permitir a cualquier visitante enviar un formulario con datos del club (nombre, NIT, tipo, ubicación, encargado) y documentos (RUT, Cámara de Comercio) para solicitar acceso a la plataforma. | Alta | Visitante |
| **RF-1.2** | Gestión Multi-Tenant | Estado Pendiente de Solicitud | Almacenar temporalmente las nuevas solicitudes en estado `pendiente` antes de su evaluación. | Alta | Sistema |
| **RF-1.3** | Gestión Multi-Tenant | Notificación de Nueva Solicitud | Enviar una notificación por correo electrónico a los super administradores cada vez que se registre una solicitud de club. | Media | Sistema |
| **RF-1.4** | Gestión Multi-Tenant | Evaluación de Solicitudes | Permitir al Super Administrador ver la lista de solicitudes pendientes y aprobarlas o rechazarlas directamente desde su panel. | Alta | Super Administrador |
| **RF-1.5** | Gestión Multi-Tenant | Aprobación de Club e Invitación | Al aprobar un club, el sistema debe cambiar el estado del Tenant a `activo`, generar un token único (`register_token`) y enviar un enlace de registro al encargado. | Alta | Super Administrador |
| **RF-1.6** | Gestión Multi-Tenant | Rechazo de Solicitud | Al rechazar un club, se debe cambiar el estado a `suspendido`, registrar un motivo de rechazo y enviar un correo informativo al solicitante. | Media | Super Administrador |
| **RF-1.7** | Gestión Multi-Tenant | Autoregistro de Administrador | Permitir al encargado del club registrar su cuenta a través del enlace tokenizado, asignándole automáticamente el rol de `Administrador`. | Alta | Administrador del Club |
| **RF-1.8** | Gestión Multi-Tenant | Consumo de Token de Registro | Invalidad el `register_token` inmediatamente después de que el primer administrador sea registrado exitosamente para evitar duplicidad. | Alta | Sistema |
| **RF-1.9** | Gestión Multi-Tenant | Onboarding de Configuración | Forzar al Administrador del Club a realizar un proceso guiado de configuración inicial (onboarding) para completar datos, subir escudo oficial y seleccionar el plan de pago. | Alta | Administrador del Club |
| **RF-1.10** | Gestión Multi-Tenant | Integración con Pasarela Wompi | Integrar el widget de checkout de Wompi en el paywall para permitir a los administradores pagar la suscripción de su club de forma segura. | Alta | Administrador del Club |
| **RF-1.11** | Gestión Multi-Tenant | Procesamiento de Webhooks de Pago | Escuchar y procesar de manera asíncrona las confirmaciones de pago enviadas por el webhook de Wompi para activar o desbloquear el acceso del inquilino automáticamente. | Alta | Sistema |

---

### Módulo 2: Administración de Usuarios y Roles (RBAC)
| ID | Módulo | Nombre del Requisito | Descripción | Prioridad | Actor |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **RF-2.1** | Usuarios y RBAC | Aislamiento de Usuarios | Asegurar que cada usuario creado pertenezca exclusivamente a un club (`tenant_id`) y no pueda ver información de otros inquilinos. | Alta | Sistema |
| **RF-2.2** | Usuarios y RBAC | CRUD de Usuarios | Permitir al Administrador crear, listar, actualizar y eliminar (Soft Delete) usuarios (deportistas, entrenadores, etc.) en su club. | Alta | Administrador del Club |
| **RF-2.3** | Usuarios y RBAC | Asignación de Roles | Permitir asignar múltiples roles del sistema a un usuario utilizando la integración con Spatie Permission. | Alta | Administrador del Club |
| **RF-2.4** | Usuarios y RBAC | Filtrado de Usuarios | Facilitar la búsqueda y filtrado de usuarios por rol asignado y por rango de fecha de creación. | Media | Administrador del Club |
| **RF-2.5** | Usuarios y RBAC | Control de Acceso por Políticas | Restringir las acciones sobre cada recurso del sistema mediante Laravel Policies específicas para cada rol y permiso. | Alta | Sistema |
| **RF-2.6** | Usuarios y RBAC | CRUD de Roles y Permisos | Permitir a los administradores crear roles personalizados y asignarles permisos específicos dentro de su entorno tenant. | Alta | Administrador del Club |

---

### Módulo 3: Gestión de Equipos y Membresías
| ID | Módulo | Nombre del Requisito | Descripción | Prioridad | Actor |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **RF-3.1** | Equipos y Membresías | CRUD de Equipos | Permitir la creación, edición, visualización y eliminación de los equipos del club, registrando nombre, categoría, logo, ubicación y contacto. | Alta | Administrador del Club |
| **RF-3.2** | Equipos y Membresías | Clasificación de Categorías | Soportar categorías deportivas (Profesional, Amateur, Formativo) y subcategorías específicas por edades (Sub-10 a Sub-18). | Alta | Administrador del Club |
| **RF-3.3** | Equipos y Membresías | Asignación de Miembros | Permitir la vinculación de jugadores y entrenadores a equipos específicos (`EquipoUser`) definiendo fecha de inicio y fin. | Alta | Administrador del Club |
| **RF-3.4** | Equipos y Membresías | Historial de Equipos | Mantener un registro histórico de todos los equipos por los que ha pasado un jugador a lo largo del tiempo. | Media | Administrador, Entrenador |
| **RF-3.5** | Equipos y Membresías | Traspaso de Jugadores | Registrar y gestionar la transferencia de jugadores de un equipo de origen a uno de destino dentro de la misma institución. | Alta | Administrador, Entrenador |

---

### Módulo 4: Perfiles de Jugadores
| ID | Módulo | Nombre del Requisito | Descripción | Prioridad | Actor |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **RF-4.1** | Perfiles de Jugadores | Ficha Técnica de Jugador | Permitir la creación de un perfil técnico/físico para los deportistas, registrando posición en el campo, dorsal, altura, peso y pierna hábil. | Alta | Administrador, Entrenador |
| **RF-4.2** | Perfiles de Jugadores | Visualización del Perfil | El jugador debe poder visualizar su propia ficha técnica y estadísticas desde su panel especializado. | Media | Jugador |
| **RF-4.3** | Perfiles de Jugadores | Autogestión de Datos Personales | Permitir al jugador actualizar su nombre, correo, teléfono y avatar de perfil de forma autónoma. | Alta | Jugador |

---

### Módulo 5: Entrenamientos y Asistencias
| ID | Módulo | Nombre del Requisito | Descripción | Prioridad | Actor |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **RF-5.1** | Entrenamientos y Asistencias | CRUD de Sesiones | Permitir la planificación y registro de entrenamientos, detallando fecha, hora, instalación/ubicación y equipo asignado. | Alta | Administrador, Entrenador |
| **RF-5.2** | Entrenamientos y Asistencias | Control de Asistencia | Permitir el registro individual de asistencia de los jugadores convocados a un entrenamiento específico. | Alta | Entrenador |
| **RF-5.3** | Entrenamientos y Asistencias | Toggle Rápido de Asistencia | Permitir al entrenador marcar la asistencia (Presente/Ausente) directamente en la tabla usando un selector interactivo (Toggle). | Alta | Entrenador |
| **RF-5.4** | Entrenamientos y Asistencias | Consulta de Asistencia | El jugador debe poder consultar sus registros históricos de asistencia y sus porcentajes de participación. | Media | Jugador |

---

### Módulo 6: Competencias y Partidos
| ID | Módulo | Nombre del Requisito | Descripción | Prioridad | Actor |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **RF-6.1** | Competencias y Partidos | CRUD de Torneos | Permitir registrar las competencias en las que participa el club, gestionando fechas y asociando múltiples equipos. | Alta | Administrador del Club |
| **RF-6.2** | Competencias y Partidos | Programación de Partidos | Programar partidos del club vinculando el torneo, fecha, hora, rivales (local/visitante) e instalación deportiva. | Alta | Administrador, Entrenador |
| **RF-6.3** | Competencias y Partidos | Asignación de Árbitros | Permitir asignar un árbitro registrado en el sistema para dirigir un partido oficial. | Media | Administrador del Club |
| **RF-6.4** | Competencias y Partidos | Convocatorias de Partido | Permitir al entrenador seleccionar y listar los jugadores que integrarán la convocatoria para un compromiso. | Alta | Entrenador |
| **RF-6.5** | Competencias y Partidos | Registro de Incidencias | Permitir registrar eventos ocurridos durante el partido (goles, tarjetas amarillas/rojas, lesiones). | Alta | Árbitro, Entrenador |
| **RF-6.6** | Competencias y Partidos | Control de Sanciones | Registrar suspensiones por partidos y acumulaciones de tarjetas aplicadas a los jugadores a raíz de incidencias en partidos. | Media | Administrador, Árbitro |

---

### Módulo 7: Rendimiento y Estadísticas
| ID | Módulo | Nombre del Requisito | Descripción | Prioridad | Actor |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **RF-7.1** | Rendimiento y Estadísticas | Ficha de Rendimiento | Registrar el desempeño individual de un jugador en un partido (minutos jugados, goles, asistencias, tarjetas y calificación 0-10). | Alta | Entrenador |
| **RF-7.2** | Rendimiento y Estadísticas | Historial de Rendimiento | Mostrar al jugador una consola con sus métricas promedio y evolución de calificaciones a lo largo de la temporada. | Media | Jugador |

---

### Módulo 8: Historial Médico
| ID | Módulo | Nombre del Requisito | Descripción | Prioridad | Actor |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **RF-8.1** | Historial Médico | Registro de Lesiones | Permitir el registro detallado de las afecciones físicas de los jugadores (tipo de lesión, gravedad, diagnóstico y fechas de baja). | Alta | Administrador, Médico |
| **RF-8.2** | Historial Médico | Control de Aptitud Médica | Establecer de manera explícita si un jugador se encuentra médicamente apto para competir o si está en periodo de recuperación. | Alta | Administrador, Médico |
| **RF-8.3** | Historial Médico | Consulta de Historial | Permitir al jugador visualizar su propio expediente médico y recomendaciones de recuperación dadas por el club. | Media | Jugador, Médico |

---

### Módulo 9: Pagos y Suscripción del Club
| ID | Módulo | Nombre del Requisito | Descripción | Prioridad | Actor |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **RF-9.1** | Pagos y Suscripción | Registro de Pagos de Suscripción | Registrar los pagos de las suscripciones y planes del club a la plataforma FitControl en COP. | Alta | Administrador del Club |
| **RF-9.2** | Pagos y Suscripción | Control del Estado de Suscripción | Clasificar y vigilar el estado del pago del plan del club (`pendiente`, `pagado`, `rechazado`) para habilitar o restringir el uso de la plataforma. | Alta | Administrador del Club |

---

### Módulo 10: Gestión de Instalaciones
| ID | Módulo | Nombre del Requisito | Descripción | Prioridad | Actor |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **RF-10.1** | Gestión de Instalaciones | CRUD de Instalaciones | Permitir el registro y administración de las canchas, gimnasios, oficinas o estadios del club, indicando capacidad y ubicación. | Media | Administrador del Club |
| **RF-10.2** | Gestión de Instalaciones | Estados de Disponibilidad | Permitir actualizar el estado de las áreas físicas (`disponible`, `ocupada`, `mantenimiento`) para evitar conflictos de programación. | Media | Administrador del Club |

---

### Módulo 11: Notificaciones y Comunicaciones
| ID | Módulo | Nombre del Requisito | Descripción | Prioridad | Actor |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **RF-11.1** | Notificaciones | Emisión de Alertas Internas | Permitir al administrador redactar notificaciones de lectura obligatoria destinadas a los usuarios de su club. | Media | Administrador del Club |
| **RF-11.2** | Notificaciones | Bandeja de Mensajes | Permitir a los jugadores recibir y marcar como leídas las notificaciones enviadas por la administración del club. | Media | Jugador |

---

### Módulo 12: Calendario y Dashboard
| ID | Módulo | Nombre del Requisito | Descripción | Prioridad | Actor |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **RF-12.1** | Calendario y Dashboard | Calendario Centralizado | Ofrecer una vista interactiva de calendario mensual con la codificación por colores (Azul para entrenamientos, Verde para partidos). | Alta | Administrador, Entrenador |
| **RF-12.2** | Calendario y Dashboard | Widgets de Estadísticas | Presentar en el Dashboard analíticas consolidadas de asistencia, distribución de jugadores por rol, balances de pagos del mes y listado de no aptos. | Alta | Administrador, Jugador (según panel) |

---

### Módulo 13: Exportación e Informes
| ID | Módulo | Nombre del Requisito | Descripción | Prioridad | Actor |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **RF-13.1** | Exportación e Informes | Exportar listados en PDF | Permitir la exportación directa a formato PDF de la información individualizada desde las vistas del panel Filament. | Media | Administrador, Entrenador |
| **RF-13.2** | Exportación e Informes | Exportación Masiva a Excel | Habilitar la descarga de reportes generales y filtrados en formato `.xlsx` para análisis contable o deportivo. | Media | Administrador, Entrenador |

---

## ⚡ 2. Requisitos No Funcionales (RNF)

Los Requisitos No Funcionales determinan las cualidades globales y restricciones técnicas de la plataforma FitControl.

### Módulo: Seguridad y Privacidad
| ID | Módulo | Nombre del Requisito | Descripción | Prioridad | Actor |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **RNF-1.1** | Seguridad | Encriptación de Contraseñas | Las contraseñas en base de datos deben estar encriptadas utilizando algoritmos de hash seguros (`bcrypt` con 12 rondas de complejidad). | Alta | Sistema |
| **RNF-1.2** | Seguridad | Autenticación de Doble Factor | Permitir a los usuarios activar el segundo factor de autenticación (2FA) basado en contraseñas OTP generadas por la aplicación. | Alta | Todos los Roles |
| **RNF-1.3** | Seguridad | Aislamiento Lógico (Multi-Tenant) | Forzar la segmentación de la base de datos de manera que ningún club pueda acceder a información operativa o financiera de otro club. | Alta | Sistema |
| **RNF-1.4** | Seguridad | Limpieza Automática de Caché | Limpiar la caché de permisos de un usuario inmediatamente después de cualquier cambio en Spatie Permission para evitar accesos obsoletos. | Alta | Sistema |
| **RNF-1.5** | Seguridad | Tokens de Un Solo Uso | Los enlaces enviados para el registro de nuevos administradores de club deben expirar después de su primer uso. | Alta | Sistema |

### Módulo: Rendimiento y Escalabilidad
| ID | Módulo | Nombre del Requisito | Descripción | Prioridad | Actor |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **RNF-2.1** | Rendimiento | Indexación de Base de Datos | La base de datos debe contar con índices específicos en columnas de consulta recurrente (como `tenant_id`, `user_id`, etc.) para agilizar búsquedas. | Alta | Sistema |
| **RNF-2.2** | Rendimiento | Encolamiento de Procesos | Los envíos de correos de confirmación, notificaciones masivas y generación de reportes complejos deben encolarse para procesamiento asíncrono (vía colas Laravel con Redis). | Alta | Sistema |
| **RNF-2.3** | Rendimiento | Búsqueda Full-Text | Implementar indexación inteligente y búsqueda rápida utilizando Algolia o Meilisearch a través de Laravel Scout. | Media | Sistema |

### Módulo: Diseño y Usabilidad (UX/UI)
| ID | Módulo | Nombre del Requisito | Descripción | Prioridad | Actor |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **RNF-3.1** | UI / UX | Temas de Interfaz | Utilizar un diseño de colores primarios e identidades diferenciadas por paneles (Azul para Administradores, Rojo para Jugadores). | Media | Sistema |
| **RNF-3.2** | UI / UX | Diseño Responsivo | La interfaz web debe adaptarse automáticamente a pantallas de celulares, tablets y ordenadores personales. | Alta | Sistema |
| **RNF-3.3** | UI / UX | Badges e Indicadores Visuales | Los estados críticos (gravedad médica, disponibilidad de canchas, estados de pago) deben representarse con insignias con colores coherentes. | Media | Sistema |

### Módulo: Mantenibilidad y Estándares
| ID | Módulo | Nombre del Requisito | Descripción | Prioridad | Actor |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **RNF-4.1** | Estándares | Arquitectura Modular | El backend debe estructurarse utilizando el Framework Laravel 12 bajo patrones de inyección de dependencias, políticas y encapsulado de esquemas Filament. | Alta | Desarrolladores |
| **RNF-4.2** | Estándares | Soporte Multimoneda / Idioma | La interfaz de administración debe estar parametrizada en idioma español y con transacciones configuradas para pesos colombianos (COP). | Alta | Sistema |
| **RNF-4.3** | Estándares | Pruebas Automatizadas | Contar con una suite de pruebas estructurada bajo PHPUnit que garantice la integridad de los módulos principales ante cambios de código. | Media | Desarrolladores |
