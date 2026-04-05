<?php

namespace App\Filament\Resources\Tenants\Pages;

use App\Filament\Resources\Tenants\TenantResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListTenants extends ListRecords
{
    protected static string $resource = TenantResource::class;

    public function getTitle(): string
    {
        return 'Clubs - Solicitudes';
    }

    public function getTabs(): array
    {
        return [
            'todos' => Tab::make('Todos'),
            'pendientes' => Tab::make('Pendientes')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('estado', 'pendiente'))
                ->icon('heroicon-m-clock')
                ->badge(fn () => \App\Models\Tenant::where('estado', 'pendiente')->count()),
            'aprobados' => Tab::make('Aprobados')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('estado', 'activo'))
                ->icon('heroicon-m-check-circle'),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [
            TenantResource::getUrl('index') => 'Tenants',
            'Listado',
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
} 