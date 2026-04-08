package com.fitcontrol.reportesms.model;

import lombok.Builder;
import lombok.Data;

@Data
@Builder
public class ReportResponse {
    private String report_id;
    private String filename;
    private Long size;
    private String download_url;
    private String message;
}
