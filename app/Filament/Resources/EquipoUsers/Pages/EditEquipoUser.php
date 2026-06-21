<?php

namespace App\Filament\Resources\EquipoUsers\Pages;

use App\Filament\Resources\EquipoUsers\EquipoUserResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;

class EditEquipoUser extends EditRecord
{
    protected static string $resource = EquipoUserResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (!empty($data['equipo_id'])) {
            $tenantId = DB::table('equipos')->where('id', $data['equipo_id'])->value('tenant_id');
            
            if ($tenantId) {
                $data['tenant_id'] = $tenantId;
            } else {
                $data['tenant_id'] = auth()->user()->tenant_id;
            }
        }
        
        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
