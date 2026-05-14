<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Actions\CreateAction;
use BezhanSalleh\FilamentShield\Facades\FilamentShield;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(
            \Filament\Auth\Http\Responses\Contracts\LoginResponse::class,
            \App\Http\Responses\LoginResponse::class
        );

        $this->app->singleton(
            \Filament\Http\Responses\Auth\Contracts\LogoutResponse::class,
            \App\Http\Responses\LogoutResponse::class
        );
    }

    public function boot(): void
    {
        CreateAction::configureUsing(function (CreateAction $action) {
            $action->label('Nuevo');
        });

        FilamentShield::buildPermissionKeyUsing(function (string $entity, string $affix, string $subject, string $case, string $separator) {
            if ($entity === \App\Filament\Jugador\Resources\JugadorPerfils\JugadorPerfilResource::class) {
                $subject = 'MiPerfilJugador';
            }
            
            $format = function (string $c, string $value) {
                return match ($c) {
                    'kebab' => Str::of($value)->kebab()->toString(),
                    'pascal' => Str::of($value)->studly()->toString(),
                    'upper_snake' => Str::of($value)->snake()->upper()->toString(),
                    'lower_snake' => Str::of($value)->snake()->lower()->toString(),
                    'camel' => Str::of($value)->camel()->toString(),
                    default => Str::of($value)->snake()->toString(),
                };
            };
            
            return $format($case, $affix) . $separator . $format($case, $subject);
        });
    }
}