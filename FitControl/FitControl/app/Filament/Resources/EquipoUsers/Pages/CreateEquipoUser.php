<?php

namespace App\Filament\Resources\EquipoUsers\Pages;

use App\Filament\Resources\EquipoUsers\EquipoUserResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;

class CreateEquipoUser extends CreateRecord
{
    protected static string $resource = EquipoUserResource::class;

    protected static ?string $title = 'Asignar equipo';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Asegurar que tenant_id coincida con el equipo seleccionado
        if (!empty($data['equipo_id'])) {
            $tenantId = DB::table('equipos')->where('id', $data['equipo_id'])->value('tenant_id');
            
            if ($tenantId) {
                $data['tenant_id'] = $tenantId;
            } else {
                // Fallback: tenant del usuario autenticado
                $data['tenant_id'] = auth()->user()->tenant_id;
            }
        }
        
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
