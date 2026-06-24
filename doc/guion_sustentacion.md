# Guion de Sustentación - FitControl SaaS (Monetización al Cierre)
**Duración Total**: 15 Minutos (3 minutos por persona)  
**Tecnologías Clave**: Laravel 12 + Filament v4 + React/Inertia + PostgreSQL + Redis + Wompi + Docker

---

## Estructura de Tiempos y Presentadores

| Presentador | Tema Principal | Rango de Tiempo | Pantallas a Mostrar |
| :--- | :--- | :--- | :--- |
| **Persona 1** | Introducción, Arquitectura SaaS y Onboarding | `00:00 - 03:00` | Landing Page, Registro de Tenant, Login |
| **Persona 2** | Panel Administrador del Club (Tenant Admin) | `03:00 - 06:00` | Gestión de Equipos, Categorías y Árbitros |
| **Persona 3** | Operaciones Técnicas: Panel de Entrenador | `06:00 - 09:00` | Calendario, Convocatorias y Widget de Lesionados |
| **Persona 4** | Cuidado Médico y Portal del Jugador | `09:00 - 12:00` | Historial Clínico, Estado de Aptitud, Perfil Móvil y 2FA |
| **Persona 5** | Suscripción, Pasarela de Pagos (Wompi) y Cierre | `12:00 - 15:00` | Vista del Paywall, Simulación de Pago y Desbloqueo de Panel |

---

## Guion Detallado (Paso a Paso)

### 🎙️ Persona 1: Introducción, Arquitectura SaaS y Onboarding (0:00 - 3:00)

*   **Objetivo**: Contextualizar el problema y demostrar el registro inicial de un nuevo club deportivo.
*   **Guion de Habla**:
    > *"Buenos días. Hoy presentamos **FitControl**, una plataforma SaaS diseñada para centralizar la gestión deportiva, médica y administrativa de clubes de fútbol. Tradicionalmente, los clubes sufren por tener información fragmentada en hojas de cálculo y reportes físicos. Nuestra solución consolida toda la operación en una arquitectura multi-inquilino (Multi-tenant) segura e independiente para cada club."*
*   **Acciones en Pantalla (Demostración)**:
    1. Mostrar la **Landing Page** principal. Explicar el valor de la plataforma.
    2. Hacer clic en **"Solicita acceso"** para abrir el formulario de **Onboarding/Registro de Tenant**.
    3. Rellenar datos rápidos de prueba (Nombre del Club, Correo Corporativo, Subdominio solicitado como `club-alianza`).
    4. Explicar cómo el sistema aísla los datos mediante subdominios, permitiendo que cada club funcione independientemente en su propio espacio.
    5. Ceder la palabra a la Persona 2.

---

### 🎙️ Persona 2: Consola de Administración del Club (3:00 - 6:00)

*   **Objetivo**: Mostrar el núcleo de gestión interna del club.
*   **Guion de Habla**:
    > *"Una vez que el club tiene acceso a la plataforma, el Administrador del Club toma el control del espacio del Tenant. Desde este panel, creamos las categorías, definimos las sedes de entrenamiento e incorporamos al personal técnico, médico y arbitral del club."*
*   **Acciones en Pantalla (Demostración)**:
    1. Iniciar sesión como **Administrador** en el panel principal.
    2. Ir a **Equipos** y mostrar la lista de categorías del club (ej. Sub-20, Primer Equipo).
    3. Mostrar la gestión del personal e invitaciones de usuarios.
    4. Mostrar el panel de **Árbitros** asignados a los partidos del club y cómo se vinculan las jornadas de liga interna.
    5. Ceder la palabra a la Persona 3.

---

### 🎙️ Persona 3: Operaciones Técnicas: Panel de Entrenador (6:00 - 9:00)

*   **Objetivo**: Demostrar cómo el cuerpo técnico gestiona los entrenamientos y el control diario del equipo deportivo.
*   **Guion de Habla**:
    > *"Una vez configurado el club, el Cuerpo Técnico puede gestionar la actividad física. El Entrenador cuenta con un portal dedicado para programar entrenamientos y definir convocatorias de partidos, estando siempre en comunicación directa con el departamento médico."*
*   **Acciones en Pantalla (Demostración)**:
    1. Cambiar al **Panel del Entrenador**. Mostrar el Dashboard interactivo.
    2. Destacar el **Widget de Jugadores No Aptos** en el inicio (actualmente vacío o con pocos jugadores).
    3. Crear una nueva **Convocatoria** para el próximo partido de liga seleccionando a los jugadores activos del club.
    4. Explicar cómo el entrenador depende de la información médica para realizar las convocatorias seguras.

---

### 🎙️ Persona 4: Cuidado Médico y Portal del Jugador (9:00 - 12:00)

*   **Objetivo**: Demostrar la gestión de la salud del deportista y el acceso del jugador a su información.
*   **Guion de Habla**:
    > *"La salud del atleta es primordial. Los médicos del club gestionan el historial clínico de los jugadores de forma digital y segura, inhabilitándolos automáticamente en caso de lesión. El jugador, por su parte, puede ver toda su información de forma transparente."*
*   **Acciones en Pantalla (Demostración)**:
    1. Cambiar al **Panel Médico**.
    2. Entrar al expediente de un jugador convocado en el registro de **Historiales Médicos**.
    3. Crear un reporte de lesión (ej. Distensión muscular) y cambiar su estado de aptitud a **"No Apto"**.
    4. Volver al **Panel del Entrenador** para mostrar cómo el jugador ahora aparece automáticamente en la lista de inactivos/lesionados en el widget, bloqueando su participación en el partido.
    5. Mostrar rápidamente la vista responsiva (celular) del **Panel del Jugador**, donde el deportista ve su historial clínico y activa la **Autenticación de Dos Factores (2FA)** para proteger sus datos de salud.

---

### 🎙️ Persona 5: Suscripción, Pasarela de Pagos (Wompi) y Cierre (12:00 - 15:00)

*   **Objetivo**: Demostrar el modelo de monetización SaaS de la plataforma integrando la pasarela de pagos y el Paywall.
*   **Guion de Habla**:
    > *"Para finalizar, presentaremos la viabilidad comercial de nuestra plataforma SaaS. FitControl incluye un sistema de cobro automático por suscripción. Si un club tiene su factura vencida, el sistema bloquea su panel con un Paywall dinámico hasta que complete el pago a través de la pasarela integrada Wompi, demostrando un modelo de negocio listo para producción."*
*   **Acciones en Pantalla (Demostración)**:
    1. Iniciar sesión con un club cuya suscripción haya expirado.
    2. Mostrar la pantalla de **Paywall Overlay** (`paywall_overlay.blade.php`), que impide navegar y usar el sistema, mostrando solo opciones de pago.
    3. Hacer clic en "Pagar Suscripción" y simular la redirección segura a Wompi.
    4. Simular la respuesta de pago aprobado de Wompi. Mostrar en pantalla cómo el sistema redirige al usuario y desbloquea el panel en tiempo real (`paywall_status.blade.php`).
    5. **Conclusión**: *"FitControl ofrece un entorno unificado, altamente seguro mediante aislamiento multi-inquilino y comercialmente viable. Muchas gracias por su atención."*
