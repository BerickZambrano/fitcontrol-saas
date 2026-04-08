package com.fitcontrol.reportesms.service;

import com.fitcontrol.reportesms.model.ReportRequest;
import com.fitcontrol.reportesms.model.ReportResponse;
import com.lowagie.text.Chunk;
import com.lowagie.text.Document;
import com.lowagie.text.Element;
import com.lowagie.text.Font;
import com.lowagie.text.PageSize;
import com.lowagie.text.Paragraph;
import com.lowagie.text.Phrase;
import com.lowagie.text.pdf.PdfPCell;
import com.lowagie.text.pdf.PdfPTable;
import com.lowagie.text.pdf.PdfWriter;
import lombok.RequiredArgsConstructor;
import org.apache.poi.ss.usermodel.*;
import org.apache.poi.xssf.usermodel.XSSFWorkbook;
import org.springframework.beans.factory.annotation.Value;
import org.springframework.jdbc.core.JdbcTemplate;
import org.springframework.stereotype.Service;

import java.awt.Color;
import java.io.*;
import java.nio.file.*;
import java.time.LocalDate;
import java.time.format.DateTimeFormatter;
import java.util.*;

@Service
@RequiredArgsConstructor
public class ReportService {

    private final JdbcTemplate jdbcTemplate;

    @Value("${reportes.storage-path}")
    private String storagePath;

    public ReportResponse generateReport(ReportRequest request) {
        try {
            Files.createDirectories(Paths.get(storagePath));
        } catch (IOException e) {
            throw new RuntimeException("No se pudo crear directorio de storage", e);
        }

        String reportId = UUID.randomUUID().toString().substring(0, 12);
        String filename;
        String extension = "pdf".equalsIgnoreCase(request.getFormat()) ? ".pdf" : ".xlsx";

        switch (request.getReport_type()) {
            case "performance" -> filename = generatePerformanceReport(request, reportId, extension);
            case "attendance" -> filename = generateAttendanceReport(request, reportId, extension);
            case "financial" -> filename = generateFinancialReport(request, reportId, extension);
            case "medical" -> filename = generateMedicalReport(request, reportId, extension);
            default -> throw new IllegalArgumentException("Tipo de reporte desconocido: " + request.getReport_type());
        }

        File file = new File(storagePath + "/" + filename);
        long size = file.length();

        return ReportResponse.builder()
                .report_id(reportId)
                .filename(filename)
                .size(size)
                .download_url("/api/reports/" + reportId + "/download")
                .message("Reporte generado exitosamente")
                .build();
    }

    public File getReportFile(String reportId) {
        File dir = new File(storagePath);
        File[] files = dir.listFiles((d, name) -> name.contains(reportId));
        if (files != null && files.length > 0) {
            return files[0];
        }
        return new File("");
    }

    // ================================================================
    // REPORTE 1: RENDIMIENTO DE JUGADORES
    // ================================================================
    private String generatePerformanceReport(ReportRequest req, String reportId, String ext) {
        String sql = """
            SELECT
                u.name as jugador,
                jp.posicion,
                jp.dorsal,
                COUNT(r.id) as partidos_jugados,
                COALESCE(SUM(r.minutos_jugados), 0) as minutos,
                COALESCE(SUM(r.goles), 0) as goles,
                COALESCE(SUM(r.asistencias), 0) as asistencias,
                COALESCE(SUM(r.tarjetas_amarillas), 0) as tarjetas_amarillas,
                COALESCE(SUM(r.tarjetas_rojas), 0) as tarjetas_rojas
            FROM rendimientos r
            JOIN users u ON r.user_id = u.id
            LEFT JOIN jugador_perfiles jp ON jp.user_id = u.id
            JOIN partidos p ON r.partido_id = p.id
            WHERE (p.equipo_local_id = ? OR p.equipo_visitante_id = ?)
              AND r.tenant_id = ?
              AND p.fecha BETWEEN ?::date AND ?::date
            GROUP BY u.id, u.name, jp.posicion, jp.dorsal
            ORDER BY goles DESC, asistencias DESC
            """;

        List<Map<String, Object>> rows = jdbcTemplate.queryForList(sql,
                req.getEquipo_id(), req.getEquipo_id(),
                req.getTenant_id(),
                req.getFecha_desde(), req.getFecha_hasta());

        String statsSql = """
            SELECT
                COUNT(DISTINCT r.user_id) as total_jugadores,
                COALESCE(SUM(r.goles), 0) as total_goles,
                COALESCE(SUM(r.asistencias), 0) as total_asistencias,
                COALESCE(SUM(r.minutos_jugados), 0) as total_minutos,
                COUNT(DISTINCT p.id) as total_partidos
            FROM rendimientos r
            JOIN partidos p ON r.partido_id = p.id
            WHERE (p.equipo_local_id = ? OR p.equipo_visitante_id = ?)
              AND r.tenant_id = ?
              AND p.fecha BETWEEN ?::date AND ?::date
            """;

        Map<String, Object> stats = jdbcTemplate.queryForMap(statsSql,
                req.getEquipo_id(), req.getEquipo_id(),
                req.getTenant_id(),
                req.getFecha_desde(), req.getFecha_hasta());

        String equipoSql = "SELECT nombre FROM equipos WHERE id = ?";
        String equipoNombre = jdbcTemplate.queryForObject(equipoSql, String.class, req.getEquipo_id());

        if (".pdf".equals(ext)) {
            return generatePerformancePdf(rows, stats, equipoNombre, req, reportId);
        } else {
            return generatePerformanceExcel(rows, stats, equipoNombre, req, reportId);
        }
    }

    private String generatePerformanceExcel(List<Map<String, Object>> rows, Map<String, Object> stats,
                                            String equipoNombre, ReportRequest req, String reportId) {
        try (Workbook workbook = new XSSFWorkbook()) {
            Sheet sheet = workbook.createSheet("Rendimiento");

            CellStyle headerStyle = workbook.createCellStyle();
            org.apache.poi.ss.usermodel.Font headerFont = workbook.createFont();
            headerFont.setBold(true);
            headerFont.setFontHeightInPoints((short) 12);
            headerFont.setColor(IndexedColors.WHITE.getIndex());
            headerStyle.setFont(headerFont);
            headerStyle.setFillForegroundColor(IndexedColors.BLUE.getIndex());
            headerStyle.setFillPattern(FillPatternType.SOLID_FOREGROUND);
            headerStyle.setAlignment(HorizontalAlignment.CENTER);

            CellStyle titleStyle = workbook.createCellStyle();
            org.apache.poi.ss.usermodel.Font titleFont = workbook.createFont();
            titleFont.setBold(true);
            titleFont.setFontHeightInPoints((short) 14);
            titleStyle.setFont(titleFont);

            Row titleRow = sheet.createRow(0);
            Cell titleCell = titleRow.createCell(0);
            titleCell.setCellValue("REPORTE DE RENDIMIENTO DE JUGADORES");
            titleCell.setCellStyle(titleStyle);
            sheet.addMergedRegion(new org.apache.poi.ss.util.CellRangeAddress(0, 0, 0, 9));

            Row infoRow = sheet.createRow(1);
            infoRow.createCell(0).setCellValue("Equipo: " + equipoNombre);
            infoRow.createCell(3).setCellValue("Período: " + req.getFecha_desde() + " al " + req.getFecha_hasta());
            infoRow.createCell(6).setCellValue("Generado: " + LocalDate.now().format(DateTimeFormatter.ofPattern("dd/MM/yyyy")));

            Row headerRow = sheet.createRow(3);
            String[] headers = {"#", "Jugador", "Posición", "Dorsal", "PJ", "Minutos", "Goles", "Asistencias", "T. Amarillas", "T. Rojas"};
            for (int i = 0; i < headers.length; i++) {
                Cell cell = headerRow.createCell(i);
                cell.setCellValue(headers[i]);
                cell.setCellStyle(headerStyle);
            }

            int rowNum = 4;
            int counter = 1;
            for (Map<String, Object> row : rows) {
                Row dataRow = sheet.createRow(rowNum++);
                dataRow.createCell(0).setCellValue(counter++);
                dataRow.createCell(1).setCellValue(String.valueOf(row.get("jugador")));
                dataRow.createCell(2).setCellValue(String.valueOf(row.getOrDefault("posicion", "N/A")));
                dataRow.createCell(3).setCellValue(row.get("dorsal") != null ? ((Number) row.get("dorsal")).intValue() : 0);
                dataRow.createCell(4).setCellValue(((Number) row.get("partidos_jugados")).intValue());
                dataRow.createCell(5).setCellValue(((Number) row.get("minutos")).intValue());
                dataRow.createCell(6).setCellValue(((Number) row.get("goles")).intValue());
                dataRow.createCell(7).setCellValue(((Number) row.get("asistencias")).intValue());
                dataRow.createCell(8).setCellValue(((Number) row.get("tarjetas_amarillas")).intValue());
                dataRow.createCell(9).setCellValue(((Number) row.get("tarjetas_rojas")).intValue());
            }

            int summaryRow = rowNum + 2;
            Row summaryTitle = sheet.createRow(summaryRow);
            summaryTitle.createCell(0).setCellValue("RESUMEN DEL EQUIPO");
            summaryTitle.getCell(0).setCellStyle(titleStyle);

            sheet.createRow(summaryRow + 1).createCell(0).setCellValue("Total jugadores: " + stats.get("total_jugadores"));
            sheet.createRow(summaryRow + 2).createCell(0).setCellValue("Total goles: " + stats.get("total_goles"));
            sheet.createRow(summaryRow + 3).createCell(0).setCellValue("Total asistencias: " + stats.get("total_asistencias"));
            sheet.createRow(summaryRow + 4).createCell(0).setCellValue("Total partidos: " + stats.get("total_partidos"));

            for (int i = 0; i < headers.length; i++) {
                sheet.autoSizeColumn(i);
            }

            String filename = "rendimiento_" + equipoNombre.replaceAll("\\s+", "_") + "_" + reportId + ".xlsx";
            String filePath = storagePath + "/" + filename;
            try (FileOutputStream fos = new FileOutputStream(filePath)) {
                workbook.write(fos);
            }

            return filename;

        } catch (IOException e) {
            throw new RuntimeException("Error generando Excel de rendimiento", e);
        }
    }

    private String generatePerformancePdf(List<Map<String, Object>> rows, Map<String, Object> stats,
                                          String equipoNombre, ReportRequest req, String reportId) {
        try {
            Document document = new Document(PageSize.A4.rotate());
            String filename = "rendimiento_" + equipoNombre.replaceAll("\\s+", "_") + "_" + reportId + ".pdf";
            PdfWriter.getInstance(document, new FileOutputStream(storagePath + "/" + filename));
            document.open();

            // Colores
            Color headerColor = new Color(0, 51, 102);
            Color lightBlue = new Color(230, 240, 250);
            Color accentColor = new Color(255, 140, 0);

            Font titleFont = new Font(Font.HELVETICA, 20, Font.BOLD);
            Font subtitleFont = new Font(Font.HELVETICA, 11, Font.NORMAL, new Color(100, 100, 100));
            Font headerFont = new Font(Font.HELVETICA, 9, Font.BOLD, Color.WHITE);
            Font dataFont = new Font(Font.HELVETICA, 8, Font.NORMAL);
            Font summaryTitleFont = new Font(Font.HELVETICA, 14, Font.BOLD, headerColor);
            Font summaryDataFont = new Font(Font.HELVETICA, 10, Font.NORMAL);

            // Header con línea decorativa
            Paragraph title = new Paragraph("⚽ REPORTE DE RENDIMIENTO DE JUGADORES", titleFont);
            title.setAlignment(Element.ALIGN_CENTER);
            title.setSpacingAfter(5f);
            document.add(title);

            // Línea decorativa
            
            

            // Info del reporte
            Font boldFont = new Font(Font.HELVETICA, 11, Font.BOLD);
            Paragraph info = new Paragraph("\n", subtitleFont);
            info.add(new Chunk("Equipo: ", boldFont));
            info.add(equipoNombre + "\n");
            info.add(new Chunk("Período: ", boldFont));
            info.add(req.getFecha_desde() + " al " + req.getFecha_hasta() + "\n");
            info.add(new Chunk("Generado: ", boldFont));
            info.add(LocalDate.now().format(DateTimeFormatter.ofPattern("dd/MM/yyyy")) + "\n");
            info.setSpacingAfter(10f);
            document.add(info);

            // Tabla principal
            PdfPTable table = new PdfPTable(10);
            table.setWidthPercentage(100);
            table.setSpacingBefore(5f);
            table.setSpacingAfter(10f);
            table.setWidths(new float[]{0.5f, 2, 1.5f, 0.8f, 0.8f, 1f, 0.8f, 1f, 1f, 0.8f});

            // Headers
            String[] headers = {"#", "Jugador", "Posición", "Dorsal", "PJ", "Minutos", "Goles", "Asist", "T. Amar", "T. Roja"};
            for (String h : headers) {
                PdfPCell cell = new PdfPCell(new Phrase(h, headerFont));
                cell.setBackgroundColor(headerColor);
                cell.setHorizontalAlignment(Element.ALIGN_CENTER);
                cell.setVerticalAlignment(Element.ALIGN_MIDDLE);
                cell.setPadding(6);
                cell.setBorderWidth(0);
                table.addCell(cell);
            }

            // Datos con colores alternos
            int counter = 1;
            boolean alternate = false;
            for (Map<String, Object> row : rows) {
                PdfPCell cell;
                
                // Número
                cell = new PdfPCell(new Phrase(String.valueOf(counter++), dataFont));
                cell.setHorizontalAlignment(Element.ALIGN_CENTER);
                cell.setBackgroundColor(alternate ? lightBlue : Color.WHITE);
                table.addCell(cell);

                // Jugador (manejar null)
                String jugador = row.get("jugador") != null ? row.get("jugador").toString() : "Sin nombre";
                cell = new PdfPCell(new Phrase(jugador, dataFont));
                cell.setPadding(4);
                cell.setBackgroundColor(alternate ? lightBlue : Color.WHITE);
                table.addCell(cell);

                // Posición
                String posicion = row.get("posicion") != null ? row.get("posicion").toString() : "N/A";
                cell = new PdfPCell(new Phrase(posicion, dataFont));
                cell.setHorizontalAlignment(Element.ALIGN_CENTER);
                cell.setBackgroundColor(alternate ? lightBlue : Color.WHITE);
                table.addCell(cell);

                // Dorsal
                int dorsal = row.get("dorsal") != null ? ((Number) row.get("dorsal")).intValue() : 0;
                cell = new PdfPCell(new Phrase(String.valueOf(dorsal), dataFont));
                cell.setHorizontalAlignment(Element.ALIGN_CENTER);
                cell.setBackgroundColor(alternate ? lightBlue : Color.WHITE);
                table.addCell(cell);

                // Partidos jugados
                int pj = ((Number) row.get("partidos_jugados")).intValue();
                cell = new PdfPCell(new Phrase(String.valueOf(pj), dataFont));
                cell.setHorizontalAlignment(Element.ALIGN_CENTER);
                cell.setBackgroundColor(alternate ? lightBlue : Color.WHITE);
                table.addCell(cell);

                // Minutos
                int minutos = ((Number) row.get("minutos")).intValue();
                cell = new PdfPCell(new Phrase(String.valueOf(minutos), dataFont));
                cell.setHorizontalAlignment(Element.ALIGN_CENTER);
                cell.setBackgroundColor(alternate ? lightBlue : Color.WHITE);
                table.addCell(cell);

                // Goles (destacar si > 0)
                int goles = ((Number) row.get("goles")).intValue();
                Font golesFont = goles > 0 ? new Font(Font.HELVETICA, 8, Font.BOLD, new Color(0, 150, 0)) : dataFont;
                cell = new PdfPCell(new Phrase(String.valueOf(goles), golesFont));
                cell.setHorizontalAlignment(Element.ALIGN_CENTER);
                cell.setBackgroundColor(goles > 0 ? new Color(220, 255, 220) : (alternate ? lightBlue : Color.WHITE));
                table.addCell(cell);

                // Asistencias
                int asist = ((Number) row.get("asistencias")).intValue();
                Font asistFont = asist > 0 ? new Font(Font.HELVETICA, 8, Font.BOLD, new Color(0, 100, 200)) : dataFont;
                cell = new PdfPCell(new Phrase(String.valueOf(asist), asistFont));
                cell.setHorizontalAlignment(Element.ALIGN_CENTER);
                cell.setBackgroundColor(asist > 0 ? new Color(220, 240, 255) : (alternate ? lightBlue : Color.WHITE));
                table.addCell(cell);

                // Tarjetas
                int tAmar = ((Number) row.get("tarjetas_amarillas")).intValue();
                int tRoj = ((Number) row.get("tarjetas_rojas")).intValue();
                
                cell = new PdfPCell(new Phrase(String.valueOf(tAmar), tAmar > 0 ? new Font(Font.HELVETICA, 8, Font.BOLD, new Color(200, 150, 0)) : dataFont));
                cell.setHorizontalAlignment(Element.ALIGN_CENTER);
                cell.setBackgroundColor(alternate ? lightBlue : Color.WHITE);
                table.addCell(cell);

                cell = new PdfPCell(new Phrase(String.valueOf(tRoj), tRoj > 0 ? new Font(Font.HELVETICA, 8, Font.BOLD, Color.RED) : dataFont));
                cell.setHorizontalAlignment(Element.ALIGN_CENTER);
                cell.setBackgroundColor(alternate ? lightBlue : Color.WHITE);
                table.addCell(cell);

                alternate = !alternate;
            }

            document.add(table);

            // Resumen estadístico
            document.add(new Paragraph("\n"));
            document.add(new Paragraph("📊 RESUMEN DEL EQUIPO", summaryTitleFont));
            
            
            PdfPTable summaryTable = new PdfPTable(2);
            summaryTable.setWidthPercentage(50);
            summaryTable.setHorizontalAlignment(Element.ALIGN_LEFT);
            summaryTable.setSpacingBefore(5f);

            Font summaryLabelFont = new Font(Font.HELVETICA, 10, Font.BOLD);
            Font summaryValueFont = new Font(Font.HELVETICA, 10, Font.NORMAL, new Color(50, 50, 50));

            String[][] summaryData = {
                {"Total Jugadores", stats.get("total_jugadores").toString()},
                {"Total Goles", stats.get("total_goles").toString()},
                {"Total Asistencias", stats.get("total_asistencias").toString()},
                {"Total Minutos", stats.get("total_minutos").toString()},
                {"Total Partidos", stats.get("total_partidos").toString()}
            };

            for (String[] data : summaryData) {
                PdfPCell labelCell = new PdfPCell(new Phrase(data[0], summaryLabelFont));
                labelCell.setBackgroundColor(lightBlue);
                labelCell.setBorderWidth(0);
                labelCell.setPadding(5);
                summaryTable.addCell(labelCell);

                PdfPCell valueCell = new PdfPCell(new Phrase(data[1], summaryValueFont));
                valueCell.setBackgroundColor(Color.WHITE);
                valueCell.setBorderWidth(0);
                valueCell.setPadding(5);
                summaryTable.addCell(valueCell);
            }

            document.add(summaryTable);

            // Footer
            document.add(new Paragraph("\n"));
            Paragraph footer = new Paragraph("FitControl - Sistema de Gestión Deportiva", new Font(Font.HELVETICA, 8, Font.ITALIC, new Color(150, 150, 150)));
            footer.setAlignment(Element.ALIGN_CENTER);
            document.add(footer);

            document.close();
            return filename;

        } catch (Exception e) {
            throw new RuntimeException("Error generando PDF de rendimiento", e);
        }
    }

    // ================================================================
    // REPORTE 2: ASISTENCIA
    // ================================================================
    private String generateAttendanceReport(ReportRequest req, String reportId, String ext) {
        String entrenamientosSql = """
            SELECT id, nombre, fecha
            FROM entrenamientos
            WHERE equipo_id = ?
              AND tenant_id = ?
              AND fecha BETWEEN ?::date AND ?::date
            ORDER BY fecha
            """;

        List<Map<String, Object>> entrenamientos = jdbcTemplate.queryForList(
                entrenamientosSql, req.getEquipo_id(), req.getTenant_id(),
                req.getFecha_desde(), req.getFecha_hasta());

        String jugadoresSql = """
            SELECT u.id, u.name, e.user_id
            FROM equipo_user e
            JOIN users u ON u.id = e.user_id
            WHERE e.equipo_id = ?
              AND e.tenant_id = ?
              AND e.fecha_fin IS NULL
            ORDER BY u.name
            """;

        List<Map<String, Object>> jugadores = jdbcTemplate.queryForList(
                jugadoresSql, req.getEquipo_id(), req.getTenant_id());

        String asistenciaSql = """
            SELECT user_id, entrenamiento_id, presente
            FROM asistencia_entrenamiento
            WHERE tenant_id = ?
              AND entrenamiento_id IN (
                  SELECT id FROM entrenamientos WHERE equipo_id = ? AND fecha BETWEEN ?::date AND ?::date
              )
            """;

        List<Map<String, Object>> asistencias = jdbcTemplate.queryForList(
                asistenciaSql, req.getTenant_id(), req.getEquipo_id(),
                req.getFecha_desde(), req.getFecha_hasta());

        String equipoSql = "SELECT nombre FROM equipos WHERE id = ?";
        String equipoNombre = jdbcTemplate.queryForObject(equipoSql, String.class, req.getEquipo_id());

        if (".pdf".equals(ext)) {
            return generateAttendancePdf(jugadores, entrenamientos, asistencias, equipoNombre, req, reportId);
        } else {
            return generateAttendanceExcel(jugadores, entrenamientos, asistencias, equipoNombre, req, reportId);
        }
    }

    private String generateAttendanceExcel(List<Map<String, Object>> jugadores,
                                           List<Map<String, Object>> entrenamientos,
                                           List<Map<String, Object>> asistencias,
                                           String equipoNombre, ReportRequest req, String reportId) {
        try (Workbook workbook = new XSSFWorkbook()) {
            Sheet sheet = workbook.createSheet("Asistencia");

            CellStyle headerStyle = workbook.createCellStyle();
            org.apache.poi.ss.usermodel.Font headerFont = workbook.createFont();
            headerFont.setBold(true);
            headerFont.setFontHeightInPoints((short) 11);
            headerFont.setColor(IndexedColors.WHITE.getIndex());
            headerStyle.setFont(headerFont);
            headerStyle.setFillForegroundColor(IndexedColors.GREEN.getIndex());
            headerStyle.setFillPattern(FillPatternType.SOLID_FOREGROUND);
            headerStyle.setAlignment(HorizontalAlignment.CENTER);

            CellStyle titleStyle = workbook.createCellStyle();
            org.apache.poi.ss.usermodel.Font titleFont = workbook.createFont();
            titleFont.setBold(true);
            titleFont.setFontHeightInPoints((short) 14);
            titleStyle.setFont(titleFont);

            CellStyle presentStyle = workbook.createCellStyle();
            presentStyle.setAlignment(HorizontalAlignment.CENTER);
            presentStyle.setFillForegroundColor(IndexedColors.BRIGHT_GREEN.getIndex());
            presentStyle.setFillPattern(FillPatternType.SOLID_FOREGROUND);

            CellStyle absentStyle = workbook.createCellStyle();
            absentStyle.setAlignment(HorizontalAlignment.CENTER);
            absentStyle.setFillForegroundColor(IndexedColors.RED.getIndex());
            absentStyle.setFillPattern(FillPatternType.SOLID_FOREGROUND);
            org.apache.poi.ss.usermodel.Font absentFont = workbook.createFont();
            absentFont.setColor(IndexedColors.WHITE.getIndex());
            absentStyle.setFont(absentFont);

            Row titleRow = sheet.createRow(0);
            Cell titleCell = titleRow.createCell(0);
            titleCell.setCellValue("REPORTE DE ASISTENCIA A ENTRENAMIENTOS");
            titleCell.setCellStyle(titleStyle);
            int totalCols = 2 + entrenamientos.size();
            sheet.addMergedRegion(new org.apache.poi.ss.util.CellRangeAddress(0, 0, 0, totalCols));

            Row infoRow = sheet.createRow(1);
            infoRow.createCell(0).setCellValue("Equipo: " + equipoNombre);
            infoRow.createCell(3).setCellValue("Período: " + req.getFecha_desde() + " al " + req.getFecha_hasta());

            Row headerRow = sheet.createRow(3);
            headerRow.createCell(0).setCellValue("#");
            headerRow.getCell(0).setCellStyle(headerStyle);
            headerRow.createCell(1).setCellValue("Jugador");
            headerRow.getCell(1).setCellStyle(headerStyle);

            for (int i = 0; i < entrenamientos.size(); i++) {
                Map<String, Object> ent = entrenamientos.get(i);
                Cell cell = headerRow.createCell(i + 2);
                String fecha = ent.get("fecha").toString();
                if (fecha.length() > 10) fecha = fecha.substring(0, 10);
                cell.setCellValue(fecha);
                cell.setCellStyle(headerStyle);
            }

            Cell pctCell = headerRow.createCell(entrenamientos.size() + 2);
            pctCell.setCellValue("% Asist.");
            pctCell.setCellStyle(headerStyle);

            int rowNum = 4;
            int counter = 1;

            Map<String, Boolean> asistenciaMap = new HashMap<>();
            for (Map<String, Object> a : asistencias) {
                String key = a.get("user_id") + "_" + a.get("entrenamiento_id");
                Object presenteVal = a.get("presente");
                Boolean presente = false;
                if (presenteVal instanceof Boolean) {
                    presente = (Boolean) presenteVal;
                } else if (presenteVal instanceof String) {
                    presente = "true".equalsIgnoreCase((String) presenteVal);
                }
                asistenciaMap.put(key, presente);
            }

            for (Map<String, Object> jugador : jugadores) {
                Row dataRow = sheet.createRow(rowNum++);
                Long userId = ((Number) jugador.get("id")).longValue();

                dataRow.createCell(0).setCellValue(counter++);
                dataRow.createCell(1).setCellValue(String.valueOf(jugador.get("name")));

                int presentes = 0;
                int totalEnt = entrenamientos.size();

                for (int i = 0; i < entrenamientos.size(); i++) {
                    Long entId = ((Number) entrenamientos.get(i).get("id")).longValue();
                    String key = userId + "_" + entId;
                    Boolean presente = asistenciaMap.getOrDefault(key, false);

                    Cell cell = dataRow.createCell(i + 2);
                    if (presente) {
                        cell.setCellValue("✅");
                        cell.setCellStyle(presentStyle);
                        presentes++;
                    } else {
                        cell.setCellValue("❌");
                        cell.setCellStyle(absentStyle);
                    }
                }

                double pct = totalEnt > 0 ? (presentes * 100.0 / totalEnt) : 0;
                Cell pctCell2 = dataRow.createCell(entrenamientos.size() + 2);
                pctCell2.setCellValue(String.format("%.0f%%", pct));
                pctCell2.setCellStyle(headerStyle);
            }

            for (int i = 0; i < totalCols + 1; i++) {
                sheet.autoSizeColumn(i);
            }

            String filename = "asistencia_" + equipoNombre.replaceAll("\\s+", "_") + "_" + reportId + ".xlsx";
            try (FileOutputStream fos = new FileOutputStream(storagePath + "/" + filename)) {
                workbook.write(fos);
            }

            return filename;

        } catch (IOException e) {
            throw new RuntimeException("Error generando Excel de asistencia", e);
        }
    }

    private String generateAttendancePdf(List<Map<String, Object>> jugadores,
                                          List<Map<String, Object>> entrenamientos,
                                          List<Map<String, Object>> asistencias,
                                          String equipoNombre, ReportRequest req, String reportId) {
        try {
            Document document = new Document(PageSize.A4.rotate());
            String filename = "asistencia_" + equipoNombre.replaceAll("\\s+", "_") + "_" + reportId + ".pdf";
            PdfWriter.getInstance(document, new FileOutputStream(storagePath + "/" + filename));
            document.open();

            Font titleFont = new Font(Font.HELVETICA, 18, Font.BOLD);
            Font subtitleFont = new Font(Font.HELVETICA, 11, Font.NORMAL);
            Font headerFont = new Font(Font.HELVETICA, 8, Font.BOLD, Color.WHITE);
            Font dataFont = new Font(Font.HELVETICA, 7, Font.NORMAL);

            Paragraph title = new Paragraph("REPORTE DE ASISTENCIA A ENTRENAMIENTOS", titleFont);
            title.setAlignment(Element.ALIGN_CENTER);
            document.add(title);

            Paragraph info = new Paragraph("\nEquipo: " + equipoNombre + "  |  Período: " + req.getFecha_desde() + " al " + req.getFecha_hasta(), subtitleFont);
            document.add(info);
            document.add(new Paragraph("\n"));

            int totalCols = 2 + entrenamientos.size() + 1;
            PdfPTable table = new PdfPTable(totalCols);
            table.setWidthPercentage(100);
            table.setSpacingBefore(10f);

            table.addCell(new PdfPCell(new Phrase("#", headerFont)));
            table.addCell(new PdfPCell(new Phrase("Jugador", headerFont)));

            for (Map<String, Object> ent : entrenamientos) {
                String fecha = ent.get("fecha").toString();
                if (fecha.length() > 10) fecha = fecha.substring(0, 10);
                table.addCell(new PdfPCell(new Phrase(fecha, headerFont)));
            }
            table.addCell(new PdfPCell(new Phrase("%", headerFont)));

            Map<String, Boolean> asistenciaMap = new HashMap<>();
            for (Map<String, Object> a : asistencias) {
                String key = a.get("user_id") + "_" + a.get("entrenamiento_id");
                Boolean presente = false;
                if (a.get("presente") instanceof Boolean) presente = (Boolean) a.get("presente");
                asistenciaMap.put(key, presente);
            }

            int counter = 1;
            for (Map<String, Object> jugador : jugadores) {
                Long userId = ((Number) jugador.get("id")).longValue();
                table.addCell(new Phrase(String.valueOf(counter++), dataFont));
                table.addCell(new Phrase(String.valueOf(jugador.get("name")), dataFont));

                int presentes = 0;
                for (Map<String, Object> ent : entrenamientos) {
                    Long entId = ((Number) ent.get("id")).longValue();
                    Boolean presente = asistenciaMap.getOrDefault(userId + "_" + entId, false);
                    if (presente) presentes++;
                    table.addCell(new Phrase(presente ? "✓" : "✗", dataFont));
                }
                table.addCell(new Phrase(String.format("%.0f%%", entrenamientos.isEmpty() ? 0 : (presentes * 100.0 / entrenamientos.size())), dataFont));
            }

            document.add(table);
            document.close();
            return filename;

        } catch (Exception e) {
            throw new RuntimeException("Error generando PDF de asistencia", e);
        }
    }

    // ================================================================
    // REPORTE 3: FINANCIERO
    // ================================================================
    private String generateFinancialReport(ReportRequest req, String reportId, String ext) {
        String summarySql = """
            SELECT
                estado,
                COUNT(*) as cantidad,
                COALESCE(SUM(monto), 0) as total
            FROM pagos
            WHERE tenant_id = ?
              AND fecha BETWEEN ?::date AND ?::date
            GROUP BY estado
            """;

        List<Map<String, Object>> summary = jdbcTemplate.queryForList(
                summarySql, req.getTenant_id(), req.getFecha_desde(), req.getFecha_hasta());

        String detailSql = """
            SELECT
                u.name as jugador,
                p.monto,
                p.estado,
                p.fecha,
                p.created_at
            FROM pagos p
            JOIN users u ON u.id = p.user_id
            WHERE p.tenant_id = ?
              AND p.fecha BETWEEN ?::date AND ?::date
            ORDER BY p.fecha DESC
            """;

        List<Map<String, Object>> detail = jdbcTemplate.queryForList(
                detailSql, req.getTenant_id(), req.getFecha_desde(), req.getFecha_hasta());

        String tenantSql = "SELECT nombre FROM tenants WHERE id = ?";
        String tenantNombre = jdbcTemplate.queryForObject(tenantSql, String.class, req.getTenant_id());

        if (".pdf".equals(ext)) {
            return generateFinancialPdf(summary, detail, tenantNombre, req, reportId);
        } else {
            return generateFinancialExcel(summary, detail, tenantNombre, req, reportId);
        }
    }

    private String generateFinancialExcel(List<Map<String, Object>> summary,
                                          List<Map<String, Object>> detail,
                                          String tenantNombre, ReportRequest req, String reportId) {
        try (Workbook workbook = new XSSFWorkbook()) {
            Sheet summarySheet = workbook.createSheet("Resumen Financiero");

            CellStyle headerStyle = workbook.createCellStyle();
            org.apache.poi.ss.usermodel.Font headerFont = workbook.createFont();
            headerFont.setBold(true);
            headerFont.setFontHeightInPoints((short) 12);
            headerFont.setColor(IndexedColors.WHITE.getIndex());
            headerStyle.setFont(headerFont);
            headerStyle.setFillForegroundColor(IndexedColors.DARK_BLUE.getIndex());
            headerStyle.setFillPattern(FillPatternType.SOLID_FOREGROUND);

            CellStyle titleStyle = workbook.createCellStyle();
            org.apache.poi.ss.usermodel.Font titleFont = workbook.createFont();
            titleFont.setBold(true);
            titleFont.setFontHeightInPoints((short) 14);
            titleStyle.setFont(titleFont);

            Row titleRow = summarySheet.createRow(0);
            Cell titleCell = titleRow.createCell(0);
            titleCell.setCellValue("REPORTE FINANCIERO");
            titleCell.setCellStyle(titleStyle);
            summarySheet.addMergedRegion(new org.apache.poi.ss.util.CellRangeAddress(0, 0, 0, 3));

            summarySheet.createRow(1).createCell(0).setCellValue("Club: " + tenantNombre);
            summarySheet.createRow(2).createCell(0).setCellValue("Período: " + req.getFecha_desde() + " al " + req.getFecha_hasta());

            Row headerRow = summarySheet.createRow(4);
            String[] sumHeaders = {"Estado", "Cantidad", "Total (COP)"};
            for (int i = 0; i < sumHeaders.length; i++) {
                Cell cell = headerRow.createCell(i);
                cell.setCellValue(sumHeaders[i]);
                cell.setCellStyle(headerStyle);
            }

            int rowNum = 5;
            long totalGeneral = 0;
            for (Map<String, Object> row : summary) {
                Row dataRow = summarySheet.createRow(rowNum++);
                dataRow.createCell(0).setCellValue(String.valueOf(row.get("estado")));
                dataRow.createCell(1).setCellValue(((Number) row.get("cantidad")).intValue());
                long monto = ((Number) row.get("total")).longValue();
                dataRow.createCell(2).setCellValue(monto);
                totalGeneral += monto;
            }

            Row totalRow = summarySheet.createRow(rowNum + 1);
            totalRow.createCell(0).setCellValue("TOTAL GENERAL");
            totalRow.getCell(0).setCellStyle(titleStyle);
            totalRow.createCell(2).setCellValue(totalGeneral);
            totalRow.getCell(2).setCellStyle(headerStyle);

            Sheet detailSheet = workbook.createSheet("Detalle de Pagos");

            Row detTitleRow = detailSheet.createRow(0);
            Cell detTitleCell = detTitleRow.createCell(0);
            detTitleCell.setCellValue("DETALLE DE PAGOS");
            detTitleCell.setCellStyle(titleStyle);

            Row detHeaderRow = detailSheet.createRow(2);
            String[] detHeaders = {"#", "Jugador", "Monto (COP)", "Estado", "Fecha"};
            for (int i = 0; i < detHeaders.length; i++) {
                Cell cell = detHeaderRow.createCell(i);
                cell.setCellValue(detHeaders[i]);
                cell.setCellStyle(headerStyle);
            }

            int detRowNum = 3;
            int counter = 1;
            for (Map<String, Object> row : detail) {
                Row dataRow = detailSheet.createRow(detRowNum++);
                dataRow.createCell(0).setCellValue(counter++);
                dataRow.createCell(1).setCellValue(String.valueOf(row.get("jugador")));
                dataRow.createCell(2).setCellValue(((Number) row.get("monto")).longValue());
                dataRow.createCell(3).setCellValue(String.valueOf(row.get("estado")));
                dataRow.createCell(4).setCellValue(row.get("fecha").toString());
            }

            for (int i = 0; i < 5; i++) {
                summarySheet.autoSizeColumn(i);
                detailSheet.autoSizeColumn(i);
            }

            String filename = "financiero_" + tenantNombre.replaceAll("\\s+", "_") + "_" + reportId + ".xlsx";
            try (FileOutputStream fos = new FileOutputStream(storagePath + "/" + filename)) {
                workbook.write(fos);
            }

            return filename;

        } catch (IOException e) {
            throw new RuntimeException("Error generando Excel financiero", e);
        }
    }

    private String generateFinancialPdf(List<Map<String, Object>> summary,
                                          List<Map<String, Object>> detail,
                                          String tenantNombre, ReportRequest req, String reportId) {
        try {
            Document document = new Document(PageSize.A4);
            String filename = "financiero_" + tenantNombre.replaceAll("\\s+", "_") + "_" + reportId + ".pdf";
            PdfWriter.getInstance(document, new FileOutputStream(storagePath + "/" + filename));
            document.open();

            Font titleFont = new Font(Font.HELVETICA, 18, Font.BOLD);
            Font subtitleFont = new Font(Font.HELVETICA, 11, Font.NORMAL);
            Font headerFont = new Font(Font.HELVETICA, 10, Font.BOLD, Color.WHITE);
            Font dataFont = new Font(Font.HELVETICA, 9, Font.NORMAL);

            Paragraph title = new Paragraph("REPORTE FINANCIERO", titleFont);
            title.setAlignment(Element.ALIGN_CENTER);
            document.add(title);

            Paragraph info = new Paragraph("\nClub: " + tenantNombre + "  |  Período: " + req.getFecha_desde() + " al " + req.getFecha_hasta(), subtitleFont);
            document.add(info);
            document.add(new Paragraph("\n"));

            PdfPTable summaryTable = new PdfPTable(3);
            summaryTable.setWidthPercentage(80);
            summaryTable.setHorizontalAlignment(Element.ALIGN_CENTER);

            summaryTable.addCell(new PdfPCell(new Phrase("Estado", headerFont)));
            summaryTable.addCell(new PdfPCell(new Phrase("Cantidad", headerFont)));
            summaryTable.addCell(new PdfPCell(new Phrase("Total (COP)", headerFont)));

            long totalGeneral = 0;
            for (Map<String, Object> row : summary) {
                summaryTable.addCell(new Phrase(String.valueOf(row.get("estado")), dataFont));
                summaryTable.addCell(new Phrase(String.valueOf(((Number) row.get("cantidad")).intValue()), dataFont));
                long monto = ((Number) row.get("total")).longValue();
                summaryTable.addCell(new Phrase(String.valueOf(monto), dataFont));
                totalGeneral += monto;
            }

            document.add(summaryTable);
            document.add(new Paragraph("\nTOTAL GENERAL: " + totalGeneral + " COP", titleFont));
            document.add(new Paragraph("\n"));

            PdfPTable detailTable = new PdfPTable(5);
            detailTable.setWidthPercentage(100);

            detailTable.addCell(new PdfPCell(new Phrase("#", headerFont)));
            detailTable.addCell(new PdfPCell(new Phrase("Jugador", headerFont)));
            detailTable.addCell(new PdfPCell(new Phrase("Monto", headerFont)));
            detailTable.addCell(new PdfPCell(new Phrase("Estado", headerFont)));
            detailTable.addCell(new PdfPCell(new Phrase("Fecha", headerFont)));

            int counter = 1;
            for (Map<String, Object> row : detail) {
                detailTable.addCell(new Phrase(String.valueOf(counter++), dataFont));
                detailTable.addCell(new Phrase(String.valueOf(row.get("jugador")), dataFont));
                detailTable.addCell(new Phrase(String.valueOf(((Number) row.get("monto")).longValue()), dataFont));
                detailTable.addCell(new Phrase(String.valueOf(row.get("estado")), dataFont));
                detailTable.addCell(new Phrase(row.get("fecha").toString(), dataFont));
            }

            document.add(detailTable);
            document.close();
            return filename;

        } catch (Exception e) {
            throw new RuntimeException("Error generando PDF financiero", e);
        }
    }

    // ================================================================
    // REPORTE 4: MÉDICO
    // ================================================================
    private String generateMedicalReport(ReportRequest req, String reportId, String ext) {
        String detailSql = """
            SELECT
                u.name as jugador,
                hm.tipo_lesion,
                hm.gravedad,
                hm.descripcion,
                hm.fecha_inicio,
                hm.fecha_fin,
                hm.apto
            FROM historial_medico hm
            JOIN users u ON u.id = hm.user_id
            WHERE hm.tenant_id = ?
              AND hm.fecha_inicio BETWEEN ?::date AND ?::date
            ORDER BY hm.fecha_inicio DESC
            """;

        List<Map<String, Object>> detail = jdbcTemplate.queryForList(
                detailSql, req.getTenant_id(), req.getFecha_desde(), req.getFecha_hasta());

        String tipoSql = """
            SELECT tipo_lesion, COUNT(*) as cantidad
            FROM historial_medico
            WHERE tenant_id = ? AND fecha_inicio BETWEEN ?::date AND ?::date
            GROUP BY tipo_lesion
            """;

        List<Map<String, Object>> porTipo = jdbcTemplate.queryForList(
                tipoSql, req.getTenant_id(), req.getFecha_desde(), req.getFecha_hasta());

        String gravedadSql = """
            SELECT gravedad, COUNT(*) as cantidad
            FROM historial_medico
            WHERE tenant_id = ? AND fecha_inicio BETWEEN ?::date AND ?::date
            GROUP BY gravedad
            """;

        List<Map<String, Object>> porGravedad = jdbcTemplate.queryForList(
                gravedadSql, req.getTenant_id(), req.getFecha_desde(), req.getFecha_hasta());

        String aptoSql = """
            SELECT apto, COUNT(*) as cantidad
            FROM historial_medico
            WHERE tenant_id = ? AND fecha_inicio BETWEEN ?::date AND ?::date
            GROUP BY apto
            """;

        List<Map<String, Object>> porApto = jdbcTemplate.queryForList(
                aptoSql, req.getTenant_id(), req.getFecha_desde(), req.getFecha_hasta());

        String noAptosSql = """
            SELECT DISTINCT u.name, hm.tipo_lesion, hm.gravedad, hm.fecha_inicio, hm.fecha_fin
            FROM historial_medico hm
            JOIN users u ON u.id = hm.user_id
            WHERE hm.tenant_id = ? AND hm.apto = false
            ORDER BY hm.fecha_inicio DESC
            """;

        List<Map<String, Object>> noAptos = jdbcTemplate.queryForList(noAptosSql, req.getTenant_id());

        String tenantSql = "SELECT nombre FROM tenants WHERE id = ?";
        String tenantNombre = jdbcTemplate.queryForObject(tenantSql, String.class, req.getTenant_id());

        if (".pdf".equals(ext)) {
            return generateMedicalPdf(detail, porTipo, porGravedad, porApto, noAptos, tenantNombre, req, reportId);
        } else {
            return generateMedicalExcel(detail, porTipo, porGravedad, porApto, noAptos, tenantNombre, req, reportId);
        }
    }

    private String generateMedicalExcel(List<Map<String, Object>> detail,
                                        List<Map<String, Object>> porTipo,
                                        List<Map<String, Object>> porGravedad,
                                        List<Map<String, Object>> porApto,
                                        List<Map<String, Object>> noAptos,
                                        String tenantNombre, ReportRequest req, String reportId) {
        try (Workbook workbook = new XSSFWorkbook()) {
            CellStyle headerStyle = workbook.createCellStyle();
            org.apache.poi.ss.usermodel.Font headerFont = workbook.createFont();
            headerFont.setBold(true);
            headerFont.setFontHeightInPoints((short) 11);
            headerFont.setColor(IndexedColors.WHITE.getIndex());
            headerStyle.setFont(headerFont);
            headerStyle.setFillForegroundColor(IndexedColors.RED.getIndex());
            headerStyle.setFillPattern(FillPatternType.SOLID_FOREGROUND);

            CellStyle titleStyle = workbook.createCellStyle();
            org.apache.poi.ss.usermodel.Font titleFont = workbook.createFont();
            titleFont.setBold(true);
            titleFont.setFontHeightInPoints((short) 14);
            titleStyle.setFont(titleFont);

            Sheet summarySheet = workbook.createSheet("Resumen Médico");

            Row titleRow = summarySheet.createRow(0);
            Cell titleCell = titleRow.createCell(0);
            titleCell.setCellValue("REPORTE MÉDICO");
            titleCell.setCellStyle(titleStyle);
            summarySheet.addMergedRegion(new org.apache.poi.ss.util.CellRangeAddress(0, 0, 0, 3));

            summarySheet.createRow(1).createCell(0).setCellValue("Club: " + tenantNombre);
            summarySheet.createRow(2).createCell(0).setCellValue("Período: " + req.getFecha_desde() + " al " + req.getFecha_hasta());

            int row = 4;
            Row tipoTitle = summarySheet.createRow(row++);
            tipoTitle.createCell(0).setCellValue("POR TIPO DE LESIÓN");
            tipoTitle.getCell(0).setCellStyle(titleStyle);

            Row tipoHeader = summarySheet.createRow(row++);
            tipoHeader.createCell(0).setCellValue("Tipo");
            tipoHeader.getCell(0).setCellStyle(headerStyle);
            tipoHeader.createCell(1).setCellValue("Cantidad");
            tipoHeader.getCell(1).setCellStyle(headerStyle);

            for (Map<String, Object> r : porTipo) {
                Row dataRow = summarySheet.createRow(row++);
                dataRow.createCell(0).setCellValue(String.valueOf(r.get("tipo_lesion")));
                dataRow.createCell(1).setCellValue(((Number) r.get("cantidad")).intValue());
            }

            row += 2;
            Row gravTitle = summarySheet.createRow(row++);
            gravTitle.createCell(0).setCellValue("POR GRAVEDAD");
            gravTitle.getCell(0).setCellStyle(titleStyle);

            Row gravHeader = summarySheet.createRow(row++);
            gravHeader.createCell(0).setCellValue("Gravedad");
            gravHeader.getCell(0).setCellStyle(headerStyle);
            gravHeader.createCell(1).setCellValue("Cantidad");
            gravHeader.getCell(1).setCellStyle(headerStyle);

            for (Map<String, Object> r : porGravedad) {
                Row dataRow = summarySheet.createRow(row++);
                dataRow.createCell(0).setCellValue(String.valueOf(r.get("gravedad")));
                dataRow.createCell(1).setCellValue(((Number) r.get("cantidad")).intValue());
            }

            row += 2;
            Row noAptoTitle = summarySheet.createRow(row++);
            noAptoTitle.createCell(0).setCellValue("⚠️ JUGADORES NO APTOS ACTUALMENTE");
            noAptoTitle.getCell(0).setCellStyle(titleStyle);

            if (!noAptos.isEmpty()) {
                Row noAptoHeader = summarySheet.createRow(row++);
                String[] noAptoHeaders = {"Jugador", "Tipo Lesión", "Gravedad", "Fecha Inicio", "Fecha Fin"};
                for (int i = 0; i < noAptoHeaders.length; i++) {
                    Cell cell = noAptoHeader.createCell(i);
                    cell.setCellValue(noAptoHeaders[i]);
                    cell.setCellStyle(headerStyle);
                }

                for (Map<String, Object> r : noAptos) {
                    Row dataRow = summarySheet.createRow(row++);
                    dataRow.createCell(0).setCellValue(String.valueOf(r.get("jugador")));
                    dataRow.createCell(1).setCellValue(String.valueOf(r.get("tipo_lesion")));
                    dataRow.createCell(2).setCellValue(String.valueOf(r.get("gravedad")));
                    dataRow.createCell(3).setCellValue(r.get("fecha_inicio").toString());
                    dataRow.createCell(4).setCellValue(r.get("fecha_fin") != null ? r.get("fecha_fin").toString() : "N/A");
                }
            } else {
                summarySheet.createRow(row++).createCell(0).setCellValue("Todos los jugadores están aptos ✅");
            }

            Sheet detailSheet = workbook.createSheet("Detalle Médico");

            Row detTitleRow = detailSheet.createRow(0);
            Cell detTitleCell = detTitleRow.createCell(0);
            detTitleCell.setCellValue("DETALLE DE REGISTROS MÉDICOS");
            detTitleCell.setCellStyle(titleStyle);

            Row detHeaderRow = detailSheet.createRow(2);
            String[] detHeaders = {"#", "Jugador", "Tipo Lesión", "Gravedad", "Descripción", "Fecha Inicio", "Fecha Fin", "Apto"};
            for (int i = 0; i < detHeaders.length; i++) {
                Cell cell = detHeaderRow.createCell(i);
                cell.setCellValue(detHeaders[i]);
                cell.setCellStyle(headerStyle);
            }

            int detRowNum = 3;
            int counter = 1;
            for (Map<String, Object> r : detail) {
                Row dataRow = detailSheet.createRow(detRowNum++);
                dataRow.createCell(0).setCellValue(counter++);
                dataRow.createCell(1).setCellValue(String.valueOf(r.get("jugador")));
                dataRow.createCell(2).setCellValue(String.valueOf(r.get("tipo_lesion")));
                dataRow.createCell(3).setCellValue(String.valueOf(r.get("gravedad")));
                dataRow.createCell(4).setCellValue(r.get("descripcion") != null ? String.valueOf(r.get("descripcion")) : "");
                dataRow.createCell(5).setCellValue(r.get("fecha_inicio").toString());
                dataRow.createCell(6).setCellValue(r.get("fecha_fin") != null ? r.get("fecha_fin").toString() : "N/A");
                Object aptoVal = r.get("apto");
                boolean apto = true;
                if (aptoVal instanceof Boolean) {
                    apto = (Boolean) aptoVal;
                } else if (aptoVal instanceof String) {
                    apto = "true".equalsIgnoreCase((String) aptoVal);
                }
                dataRow.createCell(7).setCellValue(apto ? "Sí" : "No");
            }

            for (int i = 0; i < 8; i++) {
                summarySheet.autoSizeColumn(i);
                detailSheet.autoSizeColumn(i);
            }

            String filename = "medico_" + tenantNombre.replaceAll("\\s+", "_") + "_" + reportId + ".xlsx";
            try (FileOutputStream fos = new FileOutputStream(storagePath + "/" + filename)) {
                workbook.write(fos);
            }

            return filename;

        } catch (IOException e) {
            throw new RuntimeException("Error generando Excel médico", e);
        }
    }

    private String generateMedicalPdf(List<Map<String, Object>> detail,
                                       List<Map<String, Object>> porTipo,
                                       List<Map<String, Object>> porGravedad,
                                       List<Map<String, Object>> porApto,
                                       List<Map<String, Object>> noAptos,
                                       String tenantNombre, ReportRequest req, String reportId) {
        try {
            Document document = new Document(PageSize.A4.rotate());
            String filename = "medico_" + tenantNombre.replaceAll("\\s+", "_") + "_" + reportId + ".pdf";
            PdfWriter.getInstance(document, new FileOutputStream(storagePath + "/" + filename));
            document.open();

            Font titleFont = new Font(Font.HELVETICA, 18, Font.BOLD);
            Font subtitleFont = new Font(Font.HELVETICA, 11, Font.NORMAL);
            Font headerFont = new Font(Font.HELVETICA, 9, Font.BOLD, Color.WHITE);
            Font dataFont = new Font(Font.HELVETICA, 8, Font.NORMAL);

            Paragraph title = new Paragraph("REPORTE MÉDICO", titleFont);
            title.setAlignment(Element.ALIGN_CENTER);
            document.add(title);

            Paragraph info = new Paragraph("\nClub: " + tenantNombre + "  |  Período: " + req.getFecha_desde() + " al " + req.getFecha_hasta(), subtitleFont);
            document.add(info);
            document.add(new Paragraph("\n"));

            Paragraph tipoTitle = new Paragraph("POR TIPO DE LESIÓN", titleFont);
            document.add(tipoTitle);
            PdfPTable tipoTable = new PdfPTable(2);
            tipoTable.setWidthPercentage(50);
            tipoTable.addCell(new PdfPCell(new Phrase("Tipo", headerFont)));
            tipoTable.addCell(new PdfPCell(new Phrase("Cantidad", headerFont)));
            for (Map<String, Object> r : porTipo) {
                tipoTable.addCell(new Phrase(String.valueOf(r.get("tipo_lesion")), dataFont));
                tipoTable.addCell(new Phrase(String.valueOf(((Number) r.get("cantidad")).intValue()), dataFont));
            }
            document.add(tipoTable);
            document.add(new Paragraph("\n"));

            Paragraph gravTitle = new Paragraph("POR GRAVEDAD", titleFont);
            document.add(gravTitle);
            PdfPTable gravTable = new PdfPTable(2);
            gravTable.setWidthPercentage(50);
            gravTable.addCell(new PdfPCell(new Phrase("Gravedad", headerFont)));
            gravTable.addCell(new PdfPCell(new Phrase("Cantidad", headerFont)));
            for (Map<String, Object> r : porGravedad) {
                gravTable.addCell(new Phrase(String.valueOf(r.get("gravedad")), dataFont));
                gravTable.addCell(new Phrase(String.valueOf(((Number) r.get("cantidad")).intValue()), dataFont));
            }
            document.add(gravTable);
            document.add(new Paragraph("\n"));

            if (!noAptos.isEmpty()) {
                Paragraph noAptoTitle = new Paragraph("JUGADORES NO APTOS", new Font(Font.HELVETICA, 14, Font.BOLD, Color.RED));
                document.add(noAptoTitle);
                PdfPTable noAptoTable = new PdfPTable(5);
                noAptoTable.setWidthPercentage(100);
                noAptoTable.addCell(new PdfPCell(new Phrase("Jugador", headerFont)));
                noAptoTable.addCell(new PdfPCell(new Phrase("Tipo", headerFont)));
                noAptoTable.addCell(new PdfPCell(new Phrase("Gravedad", headerFont)));
                noAptoTable.addCell(new PdfPCell(new Phrase("Inicio", headerFont)));
                noAptoTable.addCell(new PdfPCell(new Phrase("Fin", headerFont)));
                for (Map<String, Object> r : noAptos) {
                    noAptoTable.addCell(new Phrase(String.valueOf(r.get("jugador")), dataFont));
                    noAptoTable.addCell(new Phrase(String.valueOf(r.get("tipo_lesion")), dataFont));
                    noAptoTable.addCell(new Phrase(String.valueOf(r.get("gravedad")), dataFont));
                    noAptoTable.addCell(new Phrase(r.get("fecha_inicio").toString(), dataFont));
                    noAptoTable.addCell(new Phrase(r.get("fecha_fin") != null ? r.get("fecha_fin").toString() : "N/A", dataFont));
                }
                document.add(noAptoTable);
            } else {
                document.add(new Paragraph("Todos los jugadores están aptos", subtitleFont));
            }

            document.close();
            return filename;

        } catch (Exception e) {
            throw new RuntimeException("Error generando PDF médico", e);
        }
    }
}
