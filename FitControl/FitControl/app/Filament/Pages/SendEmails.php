<?php
namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use BackedEnum;

class SendEmails extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationLabel = 'Enviar Correos';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-envelope';
    protected  string $view = 'filament.pages.send-emails';
    protected static ?string $title = 'Enviar Correos';
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'mode' => 'masivo',
            'recipients' => [],
        ]);
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                ToggleButtons::make('mode')
                    ->label('Modo de envío')
                    ->options([
                        'masivo'     => 'Envío masivo (CSV)',
                        'individual' => 'Correo individual',
                    ])
                    ->default('masivo')
                    ->inline()
                    ->live(),

                FileUpload::make('file')
                    ->label('Archivo CSV')
                    ->disk('local')
                    ->directory('csv-uploads')
                    ->visibility('private')
                    ->acceptedFileTypes(['text/csv', 'text/plain'])
                    ->visible(fn (Get $get) => $get('mode') === 'masivo')
                    ->required(fn (Get $get) => $get('mode') === 'masivo'),

                Repeater::make('recipients')
                    ->label('Destinatarios')
                    ->schema([
                        TextInput::make('email')
                            ->label('Correo')
                            ->email()
                            ->required(),
                        TextInput::make('nombre')
                            ->label('Nombre')
                            ->required(),
                    ])
                    ->columns(2)
                    ->visible(fn (Get $get) => $get('mode') === 'individual')
                    ->default(fn () => [['email' => '', 'nombre' => '']])
                    ->addActionLabel('Agregar otro destinatario')
                    ->visible(fn (Get $get) => $get('mode') === 'individual')
                    ->required(fn (Get $get) => $get('mode') === 'individual'),

                TextInput::make('subject')
                    ->label('Asunto del correo')
                    ->required(),

                Textarea::make('body')
                    ->label('Cuerpo del correo')
                    ->required()
                    ->rows(5)
                    ->placeholder('Escribe tu mensaje aquí...'),
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $formData = $this->form->getState();
        $mode = $formData['mode'] ?? 'masivo';

        try {
            $client = new Client();

            if ($mode === 'individual') {
                $recipients = $formData['recipients'] ?? [];

                if (empty($recipients)) {
                    Notification::make()
                        ->title('Agrega al menos un destinatario')
                        ->danger()
                        ->send();
                    return;
                }

                $client->post(env('SPRING_MAIL_SERVICE_URL') . '/api/mail/send-multiple', [
                    'json' => [
                        'recipients' => array_values($recipients),
                        'subject'    => $formData['subject'],
                        'body'       => $formData['body'],
                    ],
                ]);
            } else {
                $filePath = Storage::disk('local')->path($formData['file']);

                if (!file_exists($filePath)) {
                    Notification::make()
                        ->title('Archivo no encontrado')
                        ->danger()
                        ->send();
                    return;
                }

                $client->post(env('SPRING_MAIL_SERVICE_URL') . '/api/mail/import-csv', [
                    'multipart' => [
                        [
                            'name'     => 'file',
                            'contents' => fopen($filePath, 'r'),
                            'filename' => basename($filePath),
                        ],
                        [
                            'name'     => 'subject',
                            'contents' => $formData['subject'],
                        ],
                        [
                            'name'     => 'body',
                            'contents' => $formData['body'],
                        ],
                    ],
                ]);

                Storage::disk('local')->delete($formData['file']);
            }

            Notification::make()
                ->title('Correo(s) enviado(s) correctamente')
                ->success()
                ->send();

            $this->form->fill(['mode' => 'masivo']);

        } catch (\Exception $e) {
            Notification::make()
                ->title('Error al enviar: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }
}