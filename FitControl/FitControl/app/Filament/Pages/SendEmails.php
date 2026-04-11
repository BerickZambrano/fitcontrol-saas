<?php
namespace App\Filament\Pages;

use App\Mail\FitControlMail;
use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use BackedEnum;
use UnitEnum;

class SendEmails extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationLabel = 'Enviar Correos';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-envelope';
    protected static string|UnitEnum|null $navigationGroup = 'Comunicación';
    protected string $view = 'filament.pages.send-emails';
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
            if ($mode === 'individual') {
                $recipients = $formData['recipients'] ?? [];

                if (empty($recipients)) {
                    Notification::make()
                        ->title('Agrega al menos un destinatario')
                        ->danger()
                        ->send();
                    return;
                }

                $successCount = 0;
                $failCount = 0;
                foreach ($recipients as $recipient) {
                    try {
                        Mail::to($recipient['email'])
                            ->send((new FitControlMail($recipient['nombre'], $formData['body']))->subject($formData['subject']));
                        $successCount++;
                    } catch (\Exception $e) {
                        $failCount++;
                    }
                }

                if ($failCount > 0) {
                    Notification::make()
                        ->title("Enviados: {$successCount} | Fallidos: {$failCount}")
                        ->warning()
                        ->send();
                } else {
                    Notification::make()
                        ->title("Correo(s) enviado(s) correctamente ({$successCount})")
                        ->success()
                        ->send();
                }
            } else {
                $filePath = Storage::disk('local')->path($formData['file']);

                if (!file_exists($filePath)) {
                    Notification::make()
                        ->title('Archivo no encontrado')
                        ->danger()
                        ->send();
                    return;
                }

                $handle = fopen($filePath, 'r');
                $firstLine = true;
                $sent = 0;
                $errors = [];

                while (($line = fgetcsv($handle)) !== false) {
                    if ($firstLine) {
                        $firstLine = false;
                        continue;
                    }

                    if (count($line) < 2) continue;

                    $nombre = trim(str_replace("\xEF\xBB\xBF", '', $line[0]));
                    $email  = trim($line[1]);

                    if (empty($email) || !str_contains($email, '@')) continue;

                    try {
                        Mail::to($email)
                            ->send((new FitControlMail($nombre, $formData['body']))->subject($formData['subject']));
                        $sent++;
                    } catch (\Exception $e) {
                        $errors[] = "{$email}: {$e->getMessage()}";
                    }
                }

                fclose($handle);
                Storage::disk('local')->delete($formData['file']);

                if (!empty($errors)) {
                    Notification::make()
                        ->title("Enviados: {$sent} | Fallidos: " . count($errors))
                        ->body(implode('; ', array_slice($errors, 0, 5)))
                        ->warning()
                        ->send();
                } else {
                    Notification::make()
                        ->title("Correos enviados correctamente ({$sent})")
                        ->success()
                        ->send();
                }
            }

            $this->form->fill(['mode' => 'masivo']);

        } catch (\Exception $e) {
            Notification::make()
                ->title('Error al enviar: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }
}
