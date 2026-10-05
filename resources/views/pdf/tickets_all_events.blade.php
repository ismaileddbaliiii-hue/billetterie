<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tous les billets</title>
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
            color: #1f2937;
        }
        h1 {
            font-size: 22px;
            margin-bottom: 10px;
        }
        .section {
            margin-bottom: 24px;
        }
        .section-title {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 10px;
            color: #111827;
        }
        .event-header {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 4px;
        }
        .event-meta {
            font-size: 11px;
            color: #4b5563;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }
        th, td {
            border: 1px solid #d1d5db;
            padding: 8px 10px;
            text-align: left;
            font-size: 10px;
        }
        th {
            background: #f9fafb;
            color: #374151;
            font-weight: 700;
            text-transform: uppercase;
        }
        tbody tr:nth-child(even) {
            background: #f8fafc;
        }
        .status {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 999px;
            font-size: 9px;
            font-weight: 700;
        }
        .status-valide {
            background: #d1fae5;
            color: #047857;
        }
        .status-utilise {
            background: #dbeafe;
            color: #1d4ed8;
        }
        .status-annule {
            background: #fee2e2;
            color: #b91c1c;
        }
        .footer {
            font-size: 10px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 12px;
        }
        @media print {
            body {
                padding: 10px;
            }
            table, th, td {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <h1>Tous mes billets</h1>
    <p style="font-size: 11px; color: #4b5563; margin-bottom: 14px;">Liste des billets valides regroupés par événement.</p>

    @foreach($events as $eventId => $billetsGroup)
        @php $firstBillet = $billetsGroup->first(); @endphp
        <div class="section">
            <div class="event-header">{{ $firstBillet->evenement->titre }}</div>
            <div class="event-meta">
                Date : {{ \Carbon\Carbon::parse($firstBillet->evenement->date)->format('d/m/Y H:i') }}
                @if($firstBillet->evenement->lieu)
                    • Lieu : {{ $firstBillet->evenement->lieu->nom }}{{ isset($firstBillet->evenement->lieu->ville) ? ' - ' . $firstBillet->evenement->lieu->ville : '' }}
                @endif
            </div>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Type</th>
                        <th>Prix</th>
                        <th>Code unique</th>
                        <th>Statut</th>
                        <th>Spectateur</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($billetsGroup as $index => $billet)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $billet->typeBillet->nom ?? $billet->type ?? 'Standard' }}</td>
                            <td>{{ number_format($billet->prix, 2, ',', ' ') }} DH</td>
                            <td style="font-family: monospace;">{{ $billet->code_unique }}</td>
                            <td>
                                @if($billet->statut == 'Valide')
                                    <span class="status status-valide">Valide</span>
                                @elseif($billet->statut == 'Utilisé')
                                    <span class="status status-utilise">Utilisé</span>
                                @else
                                    <span class="status status-annule">Annulé</span>
                                @endif
                            </td>
                            <td>{{ $billet->nom_spectateur ?? ($billet->commande->client->prenom . ' ' . $billet->commande->client->nom) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach

    <div class="footer">
        Ce document contient tous vos billets valides classés par événement.
    </div>
</body>
</html>
