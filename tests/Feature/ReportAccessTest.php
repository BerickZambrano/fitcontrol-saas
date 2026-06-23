<?php

namespace Tests\Feature;

use App\Models\GeneratedReport;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_download_other_tenant_report()
    {
        $this->withoutVite();

        $tenantA = Tenant::factory()->create();
        $tenantB = Tenant::factory()->create();

        $userA = User::factory()->create(['tenant_id' => $tenantA->id]);
        $userB = User::factory()->create(['tenant_id' => $tenantB->id]);
        $reportB = GeneratedReport::create([
            'tenant_id' => $tenantB->id,
            'user_id' => $userB->id,
            'report_type' => 'performance',
            'title' => 'Reporte Secreto',
            'filename' => 'secret.pdf',
            'file_format' => 'pdf',
            'status' => 'completed'
        ]);

        $this->actingAs($userA);

        $response = $this->get("/reportes/descargar/{$reportB->id}");

        $response->assertStatus(404);
    }
}
