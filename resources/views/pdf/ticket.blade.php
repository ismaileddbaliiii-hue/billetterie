{{-- resources/views/pdf/ticket.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ticket - {{ $billet->evenement->titre }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            background: #fff;
            padding: 20px;
        }
        .ticket {
            max-width: 400px;
            margin: 0 auto;
            border: 2px solid #3b82f6;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .ticket-header {
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            color: white;
            padding: 20px;
            text-align: center;
        }
        .event-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .event-date {
            font-size: 12px;
            opacity: 0.9;
        }
        .ticket-body {
            padding: 20px;
            background: white;
        }
        .info-row {
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e5e7eb;
        }
        .label {
            font-weight: bold;
            color: #374151;
            display: inline-block;
            width: 100px;
            font-size: 12px;
        }
        .value {
            color: #111827;
            font-size: 14px;
        }
        .qr-code {
            text-align: center;
            margin: 20px 0;
            padding: 15px;
            background: #f9fafb;
            border-radius: 8px;
            border: 1px dashed #3b82f6;
        }
        .qr-code svg, .qr-code img {
            max-width: 150px;
            height: auto;
            margin: 0 auto;
            display: block;
        }
        .code-box {
            text-align: center;
            margin: 15px 0;
            padding: 10px;
            background: #f3f4f6;
            border-radius: 6px;
        }
        .code {
            font-family: monospace;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 1px;
            word-break: break-all;
            color: #1f2937;
        }
        .ticket-footer {
            background: #f9fafb;
            padding: 12px;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
        }
        .badge {
            display: inline-block;
            background: #10b981;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
        }
        @media print {
            body {
                padding: 0;
                margin: 0;
            }
            .ticket {
                box-shadow: none;
                border: 1px solid #ccc;
            }
        }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="ticket-header">
            <div class="event-title">{{ $billet->evenement->titre }}</div>
            <div class="event-date">{{ $billet->evenement->date->format('d/m/Y à H:i') }}</div>
        </div>
        
        <div class="ticket-body">
            <div class="info-row">
                <span class="label">Spectateur :</span>
                <span class="value">{{ $billet->nom_spectateur }}</span>
            </div>
            <div class="info-row">
                <span class="label">Email :</span>
                <span class="value">{{ $billet->email_spectateur }}</span>
            </div>
            <div class="info-row">
                <span class="label">Type de billet :</span>
                <span class="value">{{ $billet->typeBillet->nom ?? 'Standard' }}</span>
            </div>
            <div class="info-row">
                <span class="label">Prix :</span>
                <span class="value">{{ number_format($billet->prix, 2, ',', ' ') }} DH</span>
            </div>
            <div class="info-row">
                <span class="label">Lieu :</span>
                <span class="value">{{ $billet->evenement->lieu->nom ?? 'Non défini' }}, {{ $billet->evenement->lieu->ville ?? '' }}</span>
            </div>
            <div class="info-row">
                <span class="label">Statut :</span>
                <span class="value">
                    @if($billet->statut == 'Valide')
                        <span style="color: #10b981; font-weight: bold;">Valide - À présenter à l'entrée</span>
                    @elseif($billet->statut == 'Utilisé')
                        <span style="color: #3b82f6; font-weight: bold;">Utilisé - Entrée validée</span>
                    @elseif($billet->statut == 'Annulé')
                        <span style="color: #ef4444; font-weight: bold;">Annulé - Non valide</span>
                    @endif
                </span>
            </div>
            
            <!-- QR Code -->
            <div class="qr-code">
                <div style="font-size: 10px; color: #6b7280; margin-bottom: 8px;">SCANNEZ POUR VALIDER</div>
                <img src="{{ $qrCode }}" alt="QR Code" style="max-width: 150px; height: auto; margin: 0 auto; display: block;">
            </div>
            
            <!-- Code en texte -->
            <div class="code-box">
                <div style="font-size: 9px; color: #6b7280; margin-bottom: 5px;">CODE D'ACCÈS</div>
                <div class="code">{{ $billet->code_unique }}</div>
            </div>
            
            <div style="text-align: center; margin-top: 15px;">
                @if($billet->statut == 'Valide')
                    <span class="badge" style="background: #10b981;">VALIDE</span>
                @elseif($billet->statut == 'Utilisé')
                    <span class="badge" style="background: #3b82f6;">UTILISÉ</span>
                @elseif($billet->statut == 'Annulé')
                    <span class="badge" style="background: #ef4444;">ANNULÉ</span>
                @else
                    <span class="badge" style="background: #6b7280;">{{ strtoupper($billet->statut) }}</span>
                @endif
            </div>
        </div>
        
        <div class="ticket-footer">
            Ce billet est strictement personnel et non transférable.<br>
            Présentez ce billet (imprimé ou sur mobile) à l'entrée.
        </div>
    </div>
</body>
</html>