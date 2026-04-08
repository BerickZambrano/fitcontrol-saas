# Microservicio de Reportes - FitControl

Microservicio Spring Boot para generación de reportes en formato Excel/PDF.

## Configuración

### 1. Configurar base de datos

Editar `src/main/resources/application.properties`:

```properties
# PostgreSQL
spring.datasource.url=jdbc:postgresql://localhost:5432/fitcontrol
spring.datasource.username=postgres
spring.datasource.password=postgres

# O MySQL
# spring.datasource.url=jdbc:mysql://localhost:3306/fitcontrol
# spring.datasource.username=root
# spring.datasource.password=root
```

### 2. Ejecutar

```bash
cd /home/berick/Escritorio/FitControl/FitControl/reportes-ms
mvn spring-boot:run
```

El servicio corre en el puerto **8082**.

## Endpoints

### Generar Reporte
```
POST /api/reports/generate
Content-Type: application/json

{
  "tenant_id": 1,
  "user_id": 42,
  "report_type": "performance",
  "equipo_id": 5,
  "fecha_desde": "2026-01-01",
  "fecha_hasta": "2026-03-31",
  "format": "xlsx"
}
```

**Tipos de reporte:**
- `performance` — Rendimiento de jugadores
- `attendance` — Asistencia a entrenamientos
- `financial` — Financiero / Pagos
- `medical` — Médico / Lesiones

**Formatos:**
- `xlsx` — Excel
- `pdf` — PDF (actualmente genera Excel, se puede extender con JasperReports)

**Response:**
```json
{
  "report_id": "abc123def",
  "filename": "rendimiento_Sub-15_abc123def.xlsx",
  "size": 45678,
  "download_url": "/api/reports/abc123def/download",
  "message": "Reporte generado exitosamente"
}
```

### Descargar Reporte
```
GET /api/reports/{report_id}/download
```

## Tipos de Reporte

### 1. Rendimiento (`performance`)
- Goles, asistencias, tarjetas por jugador
- Minutos jugados, partidos jugados
- Resumen del equipo (totales)

### 2. Asistencia (`attendance`)
- Matriz de asistencia por entrenamiento
- ✅ Presente / ❌ Ausente
- Porcentaje de asistencia por jugador

### 3. Financiero (`financial`)
- Resumen por estado (pendiente, pagado, rechazado)
- Detalle de todos los pagos
- Total general

### 4. Médico (`medical`)
- Resumen por tipo de lesión
- Resumen por gravedad
- Jugadores no aptos actualmente
- Detalle completo de registros médicos

## Estructura del Proyecto

```
reportes-ms/
├── pom.xml
├── src/main/java/com/fitcontrol/reportesms/
│   ├── ReportesMsApplication.java
│   ├── config/
│   │   └── JdbcConfig.java
│   ├── controller/
│   │   └── ReportController.java
│   ├── model/
│   │   ├── ReportRequest.java
│   │   └── ReportResponse.java
│   └── service/
│       └── ReportService.java
└── src/main/resources/
    └── application.properties
```

## Integración con Laravel

En el `.env` de Laravel:
```
REPORT_SERVICE_URL=http://localhost:8082
```

Laravel llama al microservicio vía GuzzleHttp cuando el admin genera un reporte desde `/admin/reportes/generar`.
