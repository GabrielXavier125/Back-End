<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Notificação de Saída - SAFE</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f3f4f6; margin: 0; padding: 20px; }
        .container { max-width: 560px; margin: 0 auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,.1); }
        .header { background: #1e3a8a; padding: 28px 32px; text-align: center; }
        .header h1 { color: #fbbf24; font-size: 22px; margin: 0; letter-spacing: 2px; }
        .header p { color: #bfdbfe; font-size: 13px; margin: 4px 0 0; }
        .body { padding: 32px; }
        .alert { background: #ecfdf5; border: 1px solid #bbf7d0; border-radius: 8px; padding: 16px; margin-bottom: 24px; }
        .alert-icon { font-size: 32px; margin-bottom: 8px; }
        .alert h2 { color: #065f46; font-size: 18px; margin: 0 0 4px; }
        .alert p { color: #047857; font-size: 13px; margin: 0; }
        .info-grid { display: grid; gap: 12px; }
        .info-item { background: #f9fafb; border-radius: 8px; padding: 12px 16px; }
        .info-label { font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #9ca3af; margin-bottom: 2px; }
        .info-value { font-size: 14px; font-weight: 600; color: #111827; }
        .footer { background: #f9fafb; border-top: 1px solid #e5e7eb; padding: 20px 32px; text-align: center; }
        .footer p { font-size: 12px; color: #9ca3af; margin: 0; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>SAFE</h1>
        <p>Sistema de Autorização e Fluxo Escolar</p>
    </div>
    <div class="body">
        <div class="alert">
            <div class="alert-icon">✅</div>
            <h2>Saída Confirmada</h2>
            <p>Seu(sua) filho(a) saiu da escola com autorização.</p>
        </div>

        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Aluno(a)</div>
                <div class="info-value">{{ $release->student->name }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Turma</div>
                <div class="info-value">{{ $release->student->classroom->name ?? 'Não informado' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Motivo da Saída</div>
                <div class="info-value">{{ $release->reason }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Autorizado por</div>
                <div class="info-value">Prof. {{ $release->teacher->name }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Horário de Saída</div>
                <div class="info-value">{{ $release->released_at?->format('d/m/Y \à\s H:i') }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Confirmado pela Portaria</div>
                <div class="info-value">{{ $release->gatekeeper?->name ?? '—' }}</div>
            </div>
            @if($release->observation)
            <div class="info-item">
                <div class="info-label">Observações</div>
                <div class="info-value" style="font-weight: 400; color: #374151;">{{ $release->observation }}</div>
            </div>
            @endif
        </div>
    </div>
    <div class="footer">
        <p>Este é um e-mail automático gerado pelo sistema SAFE. Não responda este e-mail.</p>
        <p style="margin-top: 4px;">{{ config('app.name') }} — {{ config('app.url') }}</p>
    </div>
</div>
</body>
</html>
