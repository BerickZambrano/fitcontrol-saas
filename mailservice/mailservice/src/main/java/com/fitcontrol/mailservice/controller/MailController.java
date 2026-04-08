package com.fitcontrol.mailservice.controller;

import com.fitcontrol.mailservice.service.MailService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.multipart.MultipartFile;

import java.util.Map;
import java.util.List;
import java.util.ArrayList;

@RestController
@RequestMapping("/api/mail")
public class MailController {

    @Autowired
    private MailService mailService;

    @PostMapping("/import-csv")
    public ResponseEntity<String> importCsv(
            @RequestParam("file") MultipartFile file,
            @RequestParam("subject") String subject,
            @RequestParam("body") String body
    ) {
        try {
            mailService.sendEmailsFromCsv(file.getInputStream(), subject, body);
            return ResponseEntity.ok("Correos enviados correctamente");
        } catch (Exception e) {
            return ResponseEntity.internalServerError().body("Error: " + e.getMessage());
        }
    }

    @PostMapping("/send-single")
    public ResponseEntity<String> sendSingle(@RequestBody Map<String, String> request) {
        try {
            String email   = request.get("email");
            String nombre  = request.get("nombre");
            String subject = request.get("subject");
            String body    = request.get("body");

            mailService.sendSingleEmail(email, nombre, subject, body);
            return ResponseEntity.ok("Correo enviado correctamente");
        } catch (Exception e) {
            return ResponseEntity.internalServerError().body("Error: " + e.getMessage());
        }
    }

    @PostMapping("/send-multiple")
    public ResponseEntity<String> sendMultiple(@RequestBody Map<String, Object> request) {
        try {
            String subject = (String) request.get("subject");
            String body = (String) request.get("body");
            List<Map<String, String>> recipientsList = (List<Map<String, String>>) request.get("recipients");

            if (recipientsList == null || recipientsList.isEmpty()) {
                return ResponseEntity.badRequest().body("No se proporcionaron destinatarios");
            }

            int successCount = 0;
            int failCount = 0;
            List<String> errors = new ArrayList<>();

            for (Map<String, String> recipient : recipientsList) {
                try {
                    String email = recipient.get("email");
                    String nombre = recipient.get("nombre");
                    mailService.sendSingleEmail(email, nombre, subject, body);
                    successCount++;
                } catch (Exception e) {
                    failCount++;
                    errors.add(recipient.get("email") + ": " + e.getMessage());
                }
            }

            String message = String.format("Enviados: %d | Fallidos: %d", successCount, failCount);
            if (!errors.isEmpty()) {
                message += " | Errores: " + String.join("; ", errors);
            }

            return ResponseEntity.ok(message);
        } catch (Exception e) {
            return ResponseEntity.internalServerError().body("Error general: " + e.getMessage());
        }
    }
}