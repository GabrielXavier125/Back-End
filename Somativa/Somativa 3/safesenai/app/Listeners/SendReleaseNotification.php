<?php

namespace App\Listeners;

use App\Events\StudentReleased;
use App\Mail\StudentReleasedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendReleaseNotification implements ShouldQueue
{
    public function handle(StudentReleased $event): void
    {
        $release = $event->release;
        $student = $release->student;
        $gatekeeper = $release->gatekeeper;

        Log::info('[SAFE] Saída confirmada', [
            'aluno'     => $student->name,
            'turma'     => $student->classroom->name ?? 'N/A',
            'professor' => $release->teacher->name,
            'porteiro'  => $gatekeeper->name,
            'motivo'    => $release->reason,
            'horario'   => $release->released_at->format('d/m/Y H:i:s'),
        ]);

        Log::channel('single')->info(
            "[WHATSAPP SIMULADO] Mensagem enviada ao responsável de {$student->name}: " .
            "Seu filho(a) saiu da escola às {$release->released_at->format('H:i')} " .
            "com autorização do professor {$release->teacher->name}. " .
            "Motivo: {$release->reason}"
        );

        if ($student->guardian_email) {
            Mail::to($student->guardian_email)
                ->send(new StudentReleasedMail($release));
        }
    }
}
