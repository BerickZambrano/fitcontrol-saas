package com.fitcontrol.reportesms.model;

import lombok.Data;
import java.util.Map;

@Data
public class ReportRequest {
    private Long tenant_id;
    private Long user_id;
    private String report_type;    // performance, attendance, financial, medical
    private Long equipo_id;
    private Long torneo_id;
    private String fecha_desde;
    private String fecha_hasta;
    private String format;         // pdf, xlsx
    private Map<String, Object> extra_params;
}
