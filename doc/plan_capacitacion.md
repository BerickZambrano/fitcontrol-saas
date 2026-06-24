# 🎓 Plan de Capacitación — FitControl SaaS
> **Plataforma SaaS Multi-Tenant para la Gestión de Clubes y Academias Deportivas**  
> **Versión:** 1.0.0  
> **Fecha:** 22 de Junio de 2026  
> **Metodología de Adopción:** Basada en el Modelo de Aceptación Tecnológica (TAM)

---

## 📖 Tabla de Contenidos
1. [Introducción y Objetivos del Plan](#1-introducción-y-objetivos-del-plan)
2. [Estrategia y Modalidad de Capacitación](#2-estrategia-y-modalidad-de-capacitación)
3. [Perfiles de Usuarios (Audiencia Objetivo)](#3-perfiles-de-usuarios-audiencia-objetivo)
4. [Programa de Capacitación (Módulos por Rol)](#4-programa-de-capacitación-módulos-por-rol)
5. [Cronograma y Duración de Sesiones](#5-cronograma-y-duración-de-sesiones)
6. [Evaluación del Aprendizaje y Adopción](#6-evaluación-del-aprendizaje-y-adopción)
7. [Referencias](#7-referencias)

---

## 1. Introducción y Objetivos del Plan

La implementación exitosa de la plataforma **FitControl SaaS** en un club deportivo depende de la adopción efectiva por parte de todos los miembros del ecosistema. El presente **Plan de Capacitación** está diseñado para instruir de manera progresiva a los distintos perfiles de usuarios del sistema, asegurando que adquieran las competencias técnicas necesarias para operar los módulos administrativos, deportivos, médicos y financieros.

El objetivo general es minimizar la resistencia al cambio, acelerar la curva de aprendizaje y asegurar que la entrada en operación productiva de la plataforma se realice de manera fluida y sin interrupciones en el flujo de trabajo cotidiano de la organización.

---

## 2. Estrategia y Modalidad de Capacitación

La capacitación se implementará bajo un enfoque híbrido (**Blended Learning**), combinando herramientas de aprendizaje sincrónicas y asincrónicas:

* **Sesiones Sincrónicas (Talleres Virtuales/Presenciales):** Sesiones prácticas guiadas por el equipo de implantación, enfocadas en los administradores del club y el staff deportivo.
* **Recursos Asincrónicos (Manuales y Videotutoriales):** Guías visuales y grabaciones cortas de pantalla que los usuarios (especialmente jugadores y árbitros) pueden consultar de forma autónoma.
* **Ambiente de Práctica (Sandboxing):** Habilitación de un entorno de pruebas con datos ficticios para que los usuarios interactúen con el sistema antes del despliegue productivo.

---

## 3. Perfiles de Usuarios (Audiencia Objetivo)

Para estructurar la capacitación, se agrupa a los usuarios en cinco perfiles según su interacción con la interfaz de Filament y la aplicación pública:

1. **Super Administrador de FitControl (SaaS Global):** Personal a cargo de la gestión de la infraestructura global del SaaS.
2. **Administrador del Club (Dueño de Tenant):** Personal directivo responsable del control financiero, altas de personal y configuración general de la academia.
3. **Cuerpo Técnico (Entrenadores):** Profesionales encargados del control de entrenamientos, partidos y evaluaciones de jugadores.
4. **Departamento de Salud (Médicos):** Fisioterapeutas y médicos deportivos que registran lesiones e incapacidades.
5. **Jugadores (Deportistas):** Usuarios finales del club que visualizan sus estadísticas y realizan el control de mensualidades.
6. **Árbitros:** Personal encargado de reportar los marcadores e incidencias de los partidos de torneos organizados.

---

## 4. Programa de Capacitación (Módulos por Rol)

### Módulo I: Administración del SaaS (Solo Super Administradores)
* **Objetivo:** Capacitar en la administración global de la plataforma FitControl.
* **Temario:**
  * Flujo de revisión, aprobación y rechazo de solicitudes de acceso de nuevos clubes (`TenantRequests`).
  * Gestión global de inquilinos (`Tenants`) y control del estado general de pagos.
  * Configuración de roles y permisos generales mediante el módulo de seguridad Spatie/Shield.
  * Análisis de widgets estadísticos globales del sistema.

### Módulo II: Gestión Administrativa del Club (Para Administradores del Club)
* **Objetivo:** Habilitar al administrador del club para configurar su entorno multi-tenant de forma autónoma.
* **Temario:**
  * Completar el flujo de Onboarding inicial del club (siglas, escudo, RUT, Cámara de Comercio y selección de plan).
  * Gestión del Paywall (Checkout seguro Wompi e interpretación del overlay bloqueador por pago pendiente).
  * CRUD de usuarios del club (crear perfiles de entrenadores, médicos y jugadores) y asignación de roles.
  * Creación y administración de equipos, categorías (Formativas/Amateur/Profesionales) e instalaciones físicas.
  * Registro de cobros y facturación de mensualidades a jugadores.
  * Descarga e interpretación de reportes generales en formatos Excel/PDF.

### Módulo III: Planificación Deportiva (Para Entrenadores)
* **Objetivo:** Capacitar al cuerpo técnico en la gestión táctica y física diaria.
* **Temario:**
  * Navegación y uso del panel exclusivo de entrenador (`/entrenador`).
  * Programación de entrenamientos y consulta del calendario unificado interactivo (código de color azul).
  * Control de asistencia móvil rápido utilizando toques rápidos (**inline toggles** de asistencia).
  * Convocatorias de partidos oficiales y registro de evaluaciones numéricas (0-10) del rendimiento deportivo de los deportistas.

### Módulo IV: Control Médico y Lesiones (Para Médicos)
* **Objetivo:** Instruir al departamento médico en el seguimiento físico y de lesiones.
* **Temario:**
  * Uso del panel de salud (`/medico`).
  * Registro de afecciones físicas indicando tipo (lesión, enfermedad, control) y nivel de gravedad (leve, medio, grave).
  * Gestión del indicador de aptitud médica deportiva (Apto / No Apto) y su impacto en el listado de jugadores elegibles en el panel de entrenador.

### Módulo V: Reporte de Partidos (Para Árbitros)
* **Objetivo:** Habilitar a los árbitros en el registro de incidencias del torneo.
* **Temario:**
  * Uso del panel de arbitraje (`/arbitro`).
  * Programación y control de partidos asignados en el torneo.
  * Registro de actas de incidencias del juego (goles, tarjetas amarillas/rojas, lesiones de partido).

### Módulo VI: Autogestión Deportiva (Para Jugadores)
* **Objetivo:** Guiar a los jugadores en la interacción con su consola personal.
* **Temario:**
  * Navegación básica en el portal especializado (`/jugador`).
  * Gestión de datos personales, datos físicos (posición, dorsal, pierna hábil) y avatar.
  * Consulta de su historial individualizado de asistencia a entrenamientos e historial de rendimiento físico.
  * Visualización y control de pagos y mensualidades pendientes de liquidar.

---

## 5. Cronograma y Duración de Sesiones

A continuación se detalla la planificación temporal recomendada para ejecutar las sesiones de capacitación previas al lanzamiento del sistema:

| Código | Sesión / Tema de Capacitación | Población Objetivo | Duración (Horas) | Modalidad |
|:---|:---|:---|:---:|:---|
| **CAP-01** | Inducción General e Onboarding del SaaS | Administradores del Club | 2.0 | Sincrónica (Virtual) |
| **CAP-02** | Configuración de Usuarios, Roles e Instalaciones | Administradores del Club | 3.0 | Sincrónica (Virtual) |
| **CAP-03** | Módulo de Finanzas y Control de Pagos (Wompi) | Administradores del Club | 2.0 | Sincrónica (Virtual) |
| **CAP-04** | Programación Deportiva y Asistencia (Toggles) | Entrenadores / Directores Técnicos | 3.0 | Sincrónica (Taller Práctico) |
| **CAP-05** | Evaluación de Rendimiento y Estadísticas | Entrenadores / Directores Técnicos | 2.0 | Sincrónica (Virtual) |
| **CAP-06** | Registro Médico, Historial y Aptitud Deportiva | Personal Médico / Fisioterapeutas | 2.0 | Sincrónica (Virtual) |
| **CAP-07** | Actas de Partido y Reporte de Incidencias | Árbitros de Torneo | 2.0 | Sincrónica (Virtual) |
| **CAP-08** | Navegación del Portal Personal y Estado de Pagos | Jugadores / Deportistas | 1.0 | Asincrónica (Videotutorial) |

---

## 6. Evaluación del Aprendizaje y Adopción

Para validar la efectividad de la capacitación y el nivel de comprensión del software, se implementarán tres mecanismos de evaluación:

1. **Prueba de Desempeño Práctico (Hands-On):** El evaluador pedirá a los administradores y entrenadores realizar acciones clave en el ambiente de pruebas (ej: "crear un equipo y marcar asistencia en un entrenamiento"). Se medirá el tiempo de ejecución y la tasa de error.
2. **Encuesta de Facilidad de Uso Percibida (SUS - System Usability Scale):** Instrumento estandarizado de 10 preguntas administrado al finalizar el taller para medir la usabilidad subjetiva del sistema por parte del staff.
3. **Métricas de Adopción Temprana (Telemetría de Uso):** Monitoreo de logs en producción durante las primeras dos semanas para rastrear el porcentaje de usuarios activos que acceden a sus paneles correspondientes.

---

## 7. Referencias

* **Davis, F. D. (1989).** *A technology acceptance model for empirically testing new end-user information systems: Theory and results*. MIT.
* **ISO/IEC 25062:2019.** *Systems and software engineering — Software product Quality Requirements and Evaluation (SQuaRE) — Common Industry Format (CIF) for usability test reports*. ISO.
* **Filament v4.** *Elegant TALL stack components for Laravel developers*. https://filamentphp.com
* **Laravel Framework 12.x.** *Official Documentation*. https://laravel.com
