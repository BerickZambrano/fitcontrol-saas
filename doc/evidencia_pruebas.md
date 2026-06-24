# 📊 Informe de Evidencia de Pruebas y Aceptación de Usuario — FitControl SaaS
> **Plataforma SaaS Multi-Tenant para la Gestión de Clubes y Academias Deportivas**  
> **Versión:** 1.0.0  
> **Fecha:** 22 de Junio de 2026  
> **Estándar:** Basado en el estándar de evidencia y reporte de pruebas ISO/IEC/IEEE 29119-3

---

## 📖 Tabla de Contenidos
1. [Introducción e Información General](#1-introducción-e-información-general)
2. [Documentación de Casos de Prueba en Formato de Tablas](#2-documentación-de-casos-de-prueba-en-formato-de-tablas)
3. [Matriz de Resultados de Pruebas](#3-matriz-de-resultados-de-pruebas)
4. [Informe de Aceptación del Usuario](#4-informe-de-aceptación-del-usuario)
5. [Acta de Conformidad y Aceptación (Firmas)](#5-acta-de-conformidad-y-aceptación-firmas)
6. [Referencias](#6-referencias)

---

## 1. Introducción e Información General

El presente documento recopila las **evidencias de pruebas de software** y el **informe de aceptación del usuario** final para la plataforma **FitControl SaaS**. Este reporte demuestra formalmente que el software fue sometido a ciclos de pruebas funcionales, de seguridad, y de integración en un entorno de preproducción (Staging), cumpliendo satisfactoriamente con los requerimientos pactados.

Las pruebas contaron con la participación del equipo de ingeniería (QA) y de los representantes del cliente (Product Owners), quienes validaron de forma directa la usabilidad, consistencia y el aislamiento lógico multi-tenant del sistema.

---

## 2. Documentación de Casos de Prueba en Formato de Tablas

A continuación se detalla la ejecución paso a paso de los escenarios críticos de prueba en formato de tablas de validación técnica:

### Caso de Prueba CPA-01: Registro, Aprobación e Invitación de Inquilinos (Onboarding)
* **Módulo:** Onboarding Multi-Tenant (`/solicitar-acceso`)
* **Precondición:** El correo del encargado no se encuentra registrado previamente en el sistema.

| Paso | Acción del Evaluador | Datos de Entrada / Ejemplo | Resultado Esperado | Resultado Obtenido | Estado |
|:---:|:---|:---|:---|:---|:---:|
| **1** | Acceder a la landing page y navegar al formulario público. | Ruta URL: `/solicitar-acceso` | Despliegue correcto del formulario en React/Inertia. | Despliegue exitoso de la interfaz pública en React. | **Exitoso** |
| **2** | Completar datos del club y del encargado, y enviar formulario. | NIT, nombre del club, email y documentos RUT/Cámara de Comercio. | Redirección a mensaje de éxito. Tenant creado en BD en `pendiente`. | Datos guardados correctamente en BD y archivos PDF almacenados. | **Exitoso** |
| **3** | Iniciar sesión como `super_admin` en `/admin` y navegar al recurso Tenants. | Credenciales de Super Administrador en `/admin`. | Visualización de la solicitud pendiente en la tabla de control. | Solicitud visible en la tabla con las opciones de Aprobación/Rechazo. | **Exitoso** |
| **4** | Hacer clic en el botón de acción "Aprobar". | Acción del botón "Aprobar". | El Tenant cambia su estado a `activo`, se genera un `register_token` y se despacha email. | Estado actualizado a `activo`, token generado y correo de invitación enviado. | **Exitoso** |
| **5** | Abrir enlace de invitación recibido por correo y registrar la cuenta del administrador. | Ruta `/register-admin/{token}`, ingresar contraseña del admin. | Creación del admin con rol `Administrador`, token invalidado y redirección al panel. | Admin registrado con éxito, token inhabilitado (403 al re-usar) y acceso concedido. | **Exitoso** |

---

### Caso de Prueba CPA-02: Control de Acceso y Aislamiento Multi-Tenant
* **Módulo:** Core / Trait `BelongsToTenant`
* **Precondición:** Existen registros de equipos creados en el "Club A" (ID 1) y "Club B" (ID 2).

| Paso | Acción del Evaluador | Datos de Entrada / Ejemplo | Resultado Esperado | Resultado Obtenido | Estado |
|:---:|:---|:---|:---|:---|:---:|
| **1** | Iniciar sesión como Administrador del "Club A" y crear un equipo deportivo. | Credenciales Admin Club A. Equipo: "Categoría Sub-15". | Creación exitosa del equipo y guardado en BD con `tenant_id = 1`. | Registro guardado en base de datos asignando el tenant del usuario creador. | **Exitoso** |
| **2** | Cerrar sesión e iniciar sesión como Administrador del "Club B". | Credenciales Admin Club B en su respectivo subdominio. | El panel del Club B carga correctamente con aislamiento de sesión. | Acceso correcto al panel con la configuración visual del Club B. | **Exitoso** |
| **3** | Consultar el listado de Equipos desde el panel del "Club B". | Acceso a `/admin/equipos`. | El equipo del "Club A" **no se muestra** en la lista de equipos del Club B. | La tabla muestra únicamente los equipos pertenecientes al Club B. | **Exitoso** |
| **4** | Intentar acceder mediante URL directa al ID del equipo del Club A desde la sesión del Club B. | Ingresar ruta `/admin/equipos/1/edit` en navegador. | El sistema intercepta la petición y responde con un error 404 (Recurso no encontrado). | La Laravel Policy y el trait interceptan la petición y lanzan una excepción 404. | **Exitoso** |

---

### Caso de Prueba CPA-03: Intercepción de Acceso por Suscripción Pendiente (Paywall)
* **Módulo:** Middleware `CheckTenantPayment`
* **Precondición:** El Tenant tiene el campo `estado_pago` con valor `pendiente`.

| Paso | Acción del Evaluador | Datos de Entrada / Ejemplo | Resultado Esperado | Resultado Obtenido | Estado |
|:---:|:---|:---|:---|:---|:---:|
| **1** | Iniciar sesión como Administrador del club en estado pendiente. | Credenciales de Administrador en subdominio correspondiente. | Inicio de sesión exitoso y redirección al panel principal del club. | Acceso concedido al panel y redirección al dashboard inicial. | **Exitoso** |
| **2** | Navegar a cualquier recurso operativo del club (ej. equipos). | Clic en `/admin/equipos` u otra ruta lateral del menú. | Se despliega el panel, pero se inyecta el modal blur `paywall_overlay` bloqueando la pantalla. | El modal aparece inmediatamente en pantalla desenfocando el contenido. | **Exitoso** |
| **3** | Intentar hacer clic en elementos del menú de navegación de Filament. | Interacción visual con el ratón. | El overlay impide hacer clic en cualquier sección operativa del club. | Eventos del mouse bloqueados en su totalidad. Menú inaccesible. | **Exitoso** |
| **4** | Hacer clic en el botón de pago de mensualidad para abrir pasarela. | Selección de plan mensual en el widget. | Despliegue correcto del widget de checkout de Wompi con monto y firma correctas. | Ventana emergente segura de Wompi desplegada con la configuración del plan. | **Exitoso** |
| **5** | Simular un pago aprobado en la pasarela. | Tarjeta de prueba autorizada en entorno sandbox. | Redirección a callback, actualización a `pagado` y desaparición del overlay. | Transacción procesada por webhook, `estado_pago` cambia a `pagado` y panel liberado. | **Exitoso** |

---

### Caso de Prueba CPA-04: Autenticación de Doble Factor (2FA OTP)
* **Módulo:** Seguridad / Livewire `TwoFactorVerify`
* **Precondición:** El usuario tiene activada la seguridad 2FA.

| Paso | Acción del Evaluador | Datos de Entrada / Ejemplo | Resultado Esperado | Resultado Obtenido | Estado |
|:---:|:---|:---|:---|:---|:---:|
| **1** | Iniciar sesión con email y contraseña válidos. | Credenciales del usuario. | El sistema valida las credenciales y redirige a la vista de verificación `/2fa/verify`. | Redirección al formulario de ingreso de código de Livewire. | **Exitoso** |
| **2** | Consultar la bandeja de entrada del correo corporativo. | Cuenta de correo registrada. | Recepción del email con el código de seguridad OTP de 6 dígitos. | Email recibido exitosamente con el código OTP transitorio. | **Exitoso** |
| **3** | Ingresar un código de verificación incorrecto en el formulario. | Código: `000000` | El sistema muestra un mensaje de error y mantiene el bloqueo de la sesión. | Alerta visual de "código inválido" y denegación de ingreso. | **Exitoso** |
| **4** | Esperar 16 minutos e ingresar el código recibido. | Código recibido vencido. | El sistema deniega el acceso indicando que el código ha expirado. | El sistema rechaza el código por caducidad temporal (límite 15 min). | **Exitoso** |
| **5** | Ingresar un código válido vigente en el formulario. | Código de 6 dígitos dentro de los 15 minutos de generación. | Acceso concedido al panel respectivo según el rol del usuario de forma inmediata. | Validación exitosa (hash bcrypt verificado) y redirección al panel. | **Exitoso** |

---

### Caso de Prueba CPA-05: Planificación de Entrenamientos y Control de Asistencia (Inline Toggle)
* **Módulo:** Panel de Entrenador (`/entrenador`)
* **Precondición:** Jugadores y categorías asignados al equipo del entrenador en sesión.

| Paso | Acción del Evaluador | Datos de Entrada / Ejemplo | Resultado Esperado | Resultado Obtenido | Estado |
|:---:|:---|:---|:---|:---|:---:|
| **1** | Acceder al recurso Entrenamientos y registrar una sesión deportiva. | Equipo: "Sub-17", Fecha, Hora e Instalación. | Registro de la sesión en base de datos y visible en calendario (azul). | Sesión de práctica grabada y reflejada en el calendario interactivo. | **Exitoso** |
| **2** | Ir a la sección de Asistencia de Entrenamientos. | Ruta: `/entrenador/asistencia-entrenamientos` | Se lista la planilla con los jugadores correspondientes al equipo "Sub-17". | Lista desplegada con los deportistas correspondientes a la categoría. | **Exitoso** |
| **3** | Hacer clic en el toggle inline de asistencia para un jugador específico. | Toggle "Presente/Ausente" de un jugador. | El valor cambia de forma reactiva sin recargar la página (petición AJAX de Livewire). | El estado de asistencia cambia inmediatamente y persiste el cambio en BD. | **Exitoso** |
| **4** | Filtrar la tabla de asistencia seleccionando la opción "Presente". | Filtro rápido de tabla. | La tabla se actualiza mostrando únicamente los deportistas marcados como presentes. | Tabla actualizada de forma dinámica según el filtro aplicado. | **Exitoso** |

---

## 3. Matriz de Resultados de Pruebas

A continuación se consolida la **Matriz de Resultados** de las ejecuciones realizadas en el entorno de preproducción:

| ID | Caso de Prueba | Módulo Evaluado | Fecha Ejecución | Ejecutado Por | Resultado Esperado | Resultado Obtenido | Estado |
|:---|:---|:---|:---:|:---|:---|:---|:---:|
| **CPA-01** | Registro de Inquilino | Onboarding | 22/06/2026 | Ing. QA / PO | Auto-registro exitoso e invitación al admin. | Registro completado. Token consumido y desactivado. | **Exitoso** |
| **CPA-02** | Aislamiento Tenant | Core Trait | 22/06/2026 | Ing. QA | Filtro global de datos y error 404 en bypass URL. | Restricción total de datos y redirección 404. | **Exitoso** |
| **CPA-03** | Bloqueo Paywall | Middleware | 22/06/2026 | Ing. QA / PO | Modal overlay bloqueador y simulación Wompi. | Intercepción correcta del menú. Pago activa tenant. | **Exitoso** |
| **CPA-04** | Autenticación 2FA | Seguridad | 22/06/2026 | Ing. QA | Bloqueo OTP incorrecto y expiración (15 min). | Acceso denegado con código expirado. Acceso con OTP. | **Exitoso** |
| **CPA-05** | Control Deportivo | Entrenador | 22/06/2026 | PO / Entrenador | Calendario azul y toggle de asistencia AJAX. | Interfaz reactiva. Cambios en asistencia sin lag. | **Exitoso** |

---

## 4. Informe de Aceptación del Usuario

### A. Resumen de la Evaluación del Usuario
El Cliente y los representantes de los clubes ejecutaron el ciclo de pruebas funcionales en preproducción. La evaluación demostró una alta conformidad con la interfaz de Filament v4, destacando la facilidad de uso del panel táctil para entrenadores y la rapidez en la respuesta de los componentes de Livewire.

### B. Hallazgos y Observaciones Destacadas
1. **Rendimiento de Asistencia (CPA-05):** El tiempo medio de respuesta en el cambio de asistencia mediante el toggle inline fue de **120 ms**, garantizando un uso ágil en dispositivos móviles en el campo de juego.
2. **Seguridad Multi-Tenant (CPA-02):** Se comprobó la solidez del aislamiento lógico al intentar acceder a IDs de otros tenants, verificando que el software previene fugas de datos.
3. **Flujo de Pago (CPA-03):** El overlay visual cumple adecuadamente la función de cobro sin interrumpir drásticamente la sesión del usuario administrador.

### C. Conclusión de Aceptación
Habiéndose completado el 100% de los casos de prueba planeados y sin registrar fallas abiertas de Severidad Alta (Críticas), el Cliente emite su **aprobación satisfactoria** de la plataforma FitControl SaaS y autoriza su despliegue inmediato al entorno de producción.

---

## 5. Acta de Conformidad y Aceptación (Firmas)

```text
                             ACTA DE CONFORMIDAD Y ACEPTACIÓN FINAL
                                        FITCONTROL SAAS

Por medio de la presente, el Cliente declara haber recibido a satisfacción la plataforma web
FitControl SaaS en su versión 1.0.0, habiendo validado las funcionalidades administrativas, 
deportivas, médicas y financieras mediante la suite de Casos de Prueba de Aceptación (CPA).

Se certifica que:
1. El software cumple formalmente con los requerimientos y alcances del proyecto.
2. Se verificó el correcto aislamiento de datos (Multi-Tenancy) y seguridad 2FA.
3. Las observaciones reportadas fueron corregidas y validadas mediante re-testeo.

Por tanto, las partes firman en señal de total conformidad y autorizan la puesta en producción.

Fecha de Firma: 22 de Junio de 2026

________________________________________            ________________________________________
Por: Equipo de Desarrollo (QA Lead)                 Por: Cliente / Product Owner
Nombre: Ing. Líder de QA                            Nombre: Berick Zambrano
Cargo: Coordinador de QA                            Cargo: Director del Club / Inquilino
```

---

## 6. References

* **ISO/IEC/IEEE 29119-3:2021.** *Software and systems engineering — Software testing — Part 3: Test documentation*. ISO.
* **Laravel Framework 12.x.** *Testing and Assertion Documentation*. https://laravel.com
* **Filament PHP v4.** *Testing Admin Panels and TALL Stack*. https://filamentphp.com
