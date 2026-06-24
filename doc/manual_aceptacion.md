# 📋 Manual de Pruebas de Aceptación — FitControl SaaS
> **Plataforma SaaS Multi-Tenant para la Gestión de Clubes y Academias Deportivas**  
> **Versión:** 1.0.0  
> **Fecha:** 22 de Junio de 2026  
> **Estándar de Calidad:** Basado en la Norma ISO/IEC/IEEE 29119 (Pruebas de Software)

---

## 📖 Tabla de Contenidos
1. [Introducción y Objetivos](#1-introducción-y-objetivos)
2. [Roles y Responsabilidades en la Aceptación](#2-roles-y-responsabilidades-en-la-aceptación)
3. [Criterios de Entrada y Salida de Pruebas](#3-criterios-de-entrada-y-salida-de-pruebas)
4. [Especificación de Casos de Prueba de Aceptación (CPA)](#4-especificación-de-casos-de-prueba-de-aceptación-cpa)
5. [Criterios de Conformidad y Clasificación de Fallas](#5-criterios-de-conformidad-y-clasificación-de-fallas)
6. [Acta de Conformidad y Cierre de Proyecto (Plantilla)](#6-acta-de-conformidad-y-cierre-de-proyecto-plantilla)
7. [Referencias](#7-referencias)

---

## 1. Introducción y Objetivos

Este **Manual de Pruebas de Aceptación** establece el marco formal para validar que la plataforma **FitControl SaaS** satisface íntegramente los requerimientos de negocio, técnicos y operativos detallados en el Documento de Requisitos (SRS) y las Historias de Usuario (HU).

El objetivo primordial es estructurar el conjunto de escenarios críticos que el cliente y el equipo de aseguramiento de calidad (QA) deben ejecutar en el entorno de preproducción para formalizar la entrega y el despliegue del sistema en el entorno productivo.

---

## 2. Roles y Responsabilidades en la Aceptación

El proceso de aceptación involucra a los siguientes actores principales:

* **Cliente / Product Owner (Representante de los Clubes):** Responsable final de la ejecución de las pruebas, validando que el flujo de experiencia de usuario y las reglas de negocio sean conformes a lo contratado.
* **Super Administrador del Sistema:** Responsable de verificar los flujos transversales, cobros financieros de suscripción (SaaS), y el aislamiento general del multi-tenant.
* **Staff Técnico de QA / Desarrollo:** Encargados de dar soporte técnico a las pruebas de aceptación, levantar el ambiente de staging y resolver con prontitud los defectos reportados.

---

## 3. Criterios de Entrada y Salida de Pruebas

### Criterios de Entrada (Comienzo del Proceso)
1. **Pruebas Unitarias Exitosas:** El 100% de la suite de pruebas unitarias (`php artisan test`) debe ejecutarse de forma exitosa sin fallas.
2. **Ambiente Habilitado:** El entorno de pruebas (Staging) debe estar activo, con la base de datos migrada y los seeders de inicio cargados.
3. **Caché y Colas en Funcionamiento:** El servidor Redis debe estar activo gestionando el procesamiento asíncrono y la sesión del usuario.

### Criterios de Salida (Cierre y Aprobación)
1. **Ejecución Completa:** El 100% de los Casos de Prueba de Aceptación (CPA) detallados en este manual deben haber sido ejecutados.
2. **Sin Defectos Críticos:** No deben existir fallas abiertas catalogadas como Críticas (Severidad Alta).
3. **Firmas de Conformidad:** Formalización escrita del Acta de Conformidad entre el cliente y el líder del proyecto de software.

---

## 4. Especificación de Casos de Prueba de Aceptación (CPA)

A continuación se detallan los escenarios obligatorios para la firma del acta de aceptación.

### CPA-01: Solicitud, Aprobación e Invitación de Inquilinos (Tenant Onboarding)
* **Historia de Usuario Relacionada:** `HU-01`, `HU-02`, `HU-04`, `HU-54`
* **Precondiciones:** El visitante no se encuentra autenticado en el sistema. El correo electrónico del encargado no está registrado previamente.

| Paso | Acción del Evaluador | Datos de Entrada / Ejemplo | Resultado Esperado | Estado (P/F) |
|:---|:---|:---|:---|:---:|
| **1** | Acceder a la landing page y navegar a `/solicitar-acceso`. | Ruta URL: `/solicitar-acceso` | Despliegue correcto del formulario público en React/Inertia. | |
| **2** | Completar datos del club y del encargado, y enviar formulario. | NIT, nombre del club, email y documentos RUT/Cámara de Comercio. | Redirección a mensaje de éxito. Creación del Tenant en base de datos con estado `pendiente`. | |
| **3** | Iniciar sesión como `super_admin` en `/admin` y navegar al recurso Tenants. | Credenciales de Super Administrador. | Visualización de la solicitud pendiente con los documentos cargados. | |
| **4** | Hacer clic en el botón de acción "Aprobar". | Acción del botón "Aprobar". | El Tenant cambia su estado a `activo`, se genera un `register_token` y se despacha un email de invitación. | |
| **5** | Abrir el email recibido, hacer clic en el link tokenizado y crear el usuario administrador. | Ruta `/register-admin/{token}`, ingresar contraseña del admin. | Creación exitosa del usuario con rol `Administrador`, redirección al panel y el token es marcado como consumido. | |

---

### CPA-02: Control de Acceso y Aislamiento Multi-Tenant
* **Historia de Usuario Relacionada:** `HU-28`, `HU-52`
* **Precondiciones:** Existen al menos dos Tenants activos en la base de datos (ej: "Club A" con ID 1 y "Club B" con ID 2) con usuarios administradores creados en cada uno.

| Paso | Acción del Evaluador | Datos de Entrada / Ejemplo | Resultado Esperado | Estado (P/F) |
|:---|:---|:---|:---|:---:|
| **1** | Iniciar sesión como Administrador del "Club A" y crear un equipo deportivo. | Credenciales Admin Club A. Nombre del equipo: "Categoría Sub-15". | Creación exitosa del equipo y guardado en la base de datos con `tenant_id = 1`. | |
| **2** | Cerrar sesión e iniciar sesión como Administrador del "Club B" en su respectivo subdominio. | Credenciales Admin Club B. | El panel de administración del "Club B" carga correctamente sin visualizar datos del "Club A". | |
| **3** | Consultar el listado de Equipos desde el panel del "Club B". | Acceso a `/admin/equipos`. | El equipo "Categoría Sub-15" creado por el "Club A" **no se muestra** en la lista (Aislamiento exitoso). | |
| **4** | Intentar acceder mediante fuerza bruta URL al ID del equipo del Club A desde la sesión del Club B. | Ruta URL: `/admin/equipos/1/edit`. | El sistema intercepta la petición y responde con un error de exclusión global o redirección segura (404/403). | |

---

### CPA-03: Intercepción de Acceso por Suscripción Pendiente (Paywall)
* **Historia de Usuario Relacionada:** `HU-02`, `HU-53`
* **Precondiciones:** El Tenant se encuentra activo pero su estado de suscripción (`estado_pago`) está en `pendiente`.

| Paso | Acción del Evaluador | Datos de Entrada / Ejemplo | Resultado Esperado | Estado (P/F) |
|:---|:---|:---|:---|:---:|
| **1** | Iniciar sesión como Administrador del club en estado pendiente. | Credenciales de Administrador. | Inicio de sesión exitoso y redirección al panel. | |
| **2** | Visualizar la pantalla de inicio del panel o cualquier recurso interno. | Ruta URL: `/admin/equipos` u otra ruta. | Se renderiza la interfaz, pero se inyecta de forma invasiva el modal con blur `paywall_overlay` bloqueando la pantalla. | |
| **3** | Intentar hacer clic en elementos del menú de navegación. | Interacción visual. | El overlay impide hacer clic en cualquier sección operativa del club. | |
| **4** | Hacer clic en el botón de pago del Paywall para desplegar el widget de Wompi. | Selección de plan mensual. | Despliegue correcto del widget de checkout de Wompi con el valor y firma SHA-256 correctos. | |
| **5** | Simular un pago exitoso en la pasarela. | Tarjeta de simulación Wompi. | Redirección a la ruta de callback, actualización del estado a `pagado` y desaparición del overlay bloqueador. | |

---

### CPA-04: Autenticación de Doble Factor (2FA OTP)
* **Historia de Usuario Relacionada:** `HU-10`
* **Precondiciones:** El usuario ha activado el segundo factor de autenticación OTP desde `/settings/two-factor`.

| Paso | Acción del Evaluador | Datos de Entrada / Ejemplo | Resultado Esperado | Estado (P/F) |
|:---|:---|:---|:---|:---:|
| **1** | Cerrar sesión e intentar iniciar sesión en el panel correspondiente. | Email y Contraseña del usuario. | El sistema valida las credenciales y, en lugar de dar acceso, redirige a la pantalla `/2fa/verify`. | |
| **2** | Revisar la bandeja del correo electrónico registrado del usuario. | Cuenta de correo. | Recepción del email con el asunto de código OTP conteniendo 6 dígitos numéricos. | |
| **3** | Ingresar un código de verificación incorrecto en el formulario. | Código: `111111` | El sistema muestra un mensaje de error indicando código inválido y deniega el acceso. | |
| **4** | Esperar 16 minutos e ingresar el código recibido. | Código recibido vencido. | El sistema deniega el acceso indicando que el código ha expirado. | |
| **5** | Solicitar reenvío e ingresar el nuevo código válido dentro del tiempo límite. | Código numérico vigente. | Acceso concedido al panel respectivo según el rol del usuario de forma inmediata. | |

---

### CPA-05: Planificación de Entrenamientos y Control de Asistencia (Inline Toggle)
* **Historia de Usuario Relacionada:** `HU-31`, `HU-32`, `HU-48`
* **Precondiciones:** El entrenador se encuentra autenticado, con categorías y jugadores asignados a su equipo.

| Paso | Acción del Evaluador | Datos de Entrada / Ejemplo | Resultado Esperado | Estado (P/F) |
|:---|:---|:---|:---|:---:|
| **1** | Acceder al recurso Entrenamientos y programar una sesión deportiva. | Equipo: "Sub-17", Fecha, Hora e Instalación. | Registro exitoso de la sesión en el calendario del equipo y del club. | |
| **2** | Ir a la sección de Asistencia de Entrenamientos. | Ruta: `/entrenador/asistencia-entrenamientos` | Se lista la planilla con los jugadores correspondientes al equipo "Sub-17". | |
| **3** | Hacer clic en el toggle inline de asistencia para un jugador específico. | Toggle "Presente/Ausente" del jugador. | El valor cambia de forma reactiva sin recargar la página. Se despacha petición AJAX y se guarda el estado en base de datos. | |
| **4** | Filtrar la tabla de asistencia seleccionando la opción "Presente". | Filtro de tabla. | La tabla se reduce mostrando únicamente los deportistas marcados como presentes de forma dinámica. | |

---

## 5. Criterios de Conformidad y Clasificación de Fallas

Durante la ejecución de las pruebas, los hallazgos o defectos encontrados se clasificarán de acuerdo con su impacto operativo en las siguientes tres categorías:

1. **Severidad Alta (Bloqueante):** Cualquier falla que impida completar un flujo de negocio crítico, comprometa la seguridad multi-tenant (ej: ver datos de otro club) o cause una caída total del servidor (Errores 500 continuos). **No se permite el pase a producción con fallas de esta categoría.**
2. **Severidad Media (No Bloqueante con Workaround):** El flujo puede completarse pero con una desviación molesta para el usuario, o fallas en la respuesta visual no crítica. Se documentará y se programará su corrección en un parche inmediato (Hotfix).
3. **Severidad Baja (Cosmética):** Fallas menores de alineación CSS, textos ortográficos o traducciones faltantes. No detienen la aceptación y se tratarán como mejoras de usabilidad.

---

## 6. Acta de Conformidad y Cierre de Proyecto (Plantilla)

```text
                                 ACTA DE ACEPTACIÓN Y CIERRE
                                       FITCONTROL SAAS

En la ciudad de ______________________, a los ____ días del mes de _______________ de 2026, se 
reúnen por una parte los representantes del equipo de desarrollo de FitControl y por la otra el 
Cliente / Product Owner, con el fin de formalizar el cierre del proceso de pruebas de aceptación 
del sistema FitControl SaaS.

DECLARACIONES:
1. El equipo de desarrollo ha puesto a disposición del Cliente el ambiente de pruebas con la última 
   versión estable del software.
2. Se ejecutaron de manera conjunta los Casos de Prueba de Aceptación (CPA) detallados en el manual.
3. Los resultados obtenidos han sido validados y clasificados según el impacto en la operación.

RESULTADOS DE LAS PRUEBAS:
[  ] APROBADO SIN OBSERVACIONES: El sistema cumple con la totalidad de los criterios y se autoriza 
     su despliegue en producción de forma inmediata.
[  ] APROBADO CON OBSERVACIONES (PENDIENTES MENORES): El sistema cumple sustancialmente con los 
     criterios. Se autoriza el despliegue a producción condicionado a la resolución de las 
     observaciones detalladas en el anexo de este documento en un plazo de _____ días.
[  ] RECHAZADO: Se identificaron fallas críticas de Severidad Alta. El sistema no se autoriza para 
     producción hasta que se corrijan las desviaciones y se repita el ciclo de pruebas.

OBSERVACIONES PENDIENTES (En caso de aplicar):
--------------------------------------------------------------------------------------------------
--------------------------------------------------------------------------------------------------

CONFORMIDAD:

________________________________________            ________________________________________
Por: Equipo de Desarrollo                           Por: Cliente / Product Owner
Nombre:                                             Nombre:
Cargo:                                              Cargo:
```

---

## 7. Referencias

* **ISO/IEC/IEEE 29119-1:2022.** *Software and systems engineering — Software testing — Part 1: General concepts*. IEEE.
* **ISO/IEC 25010:2011.** *Systems and software engineering — Systems and software Quality Requirements and Evaluation (Square) — System and software quality models*. ISO.
* **Laravel Framework 12.x.** *Documentation - Testing*. https://laravel.com/docs/12.x/testing
* **Filament v4.** *Testing Resources and Admin Panels*. https://filamentphp.com/docs/4.x/admin/testing
