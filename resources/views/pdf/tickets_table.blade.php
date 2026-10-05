<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Billets - {{ $evenement->titre }}</title>
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
        .header {
            margin-bottom: 24px;
        }
        .header h1 {
            font-size: 22px;
            margin-bottom: 6px;
        }
        .header p {
            font-size: 12px;
            color: #4b5563;
            margin-bottom: 4px;
        }
        .summary {
            display: flex;
            justify-content: space-between;
            margin-bottom: 18px;
            flex-wrap: wrap;
            gap: 10px;
        }
        .summary .box {
            background: #f3f4f6;
            padding: 12px 14px;
            border-radius: 10px;
            min-width: 170px;
        }
        .summary .box strong {
            display: block;
            font-size: 14px;
            margin-bottom: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        th, td {
            border: 1px solid #d1d5db;
            padding: 10px 12px;
            text-align: left;
            font-size: 11px;
        }
        th {
            background: #f9fafb;
            color: #374151;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }
        tbody tr:nth-child(even) {
            background: #f8fafc;
        }
        .status {
            padding: 4px 8px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 700;
            display: inline-block;
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
    <div class="header">
        <h1>Billets pour {{ $evenement->titre }}</h1>
        <p>Date : {{ \Carbon\Carbon::parse($evenement->date)->format('d/m/Y H:i') }}</p>
        <p>Lieu : {{ $evenement->lieu->nom ?? 'Non défini' }}{{ isset($evenement->lieu->ville) ? ' - ' . $evenement->lieu->ville : '' }}</p>
    </div>

    <div class="summary">
        <div class="box">
            <strong>Nombre de billets</strong>
            {{ $billets->count() }}
        </div>
        <div class="box">
            <strong>Prix total</strong>
            {{ number_format($billets->sum('prix'), 2, ',', ' ') }} DH
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Type</th>
                <th>Prix</th>
                <th>Statut</th>
                <th>Code unique</th>
                <th>Date</th>
                <th>Spectateur</th>
            </tr>
        </thead>
        <tbody>
            @foreach($billets as $index => $billet)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $billet->typeBillet->nom ?? $billet->type ?? 'Standard' }}</td>
                    <td>{{ number_format($billet->prix, 2, ',', ' ') }} DH</td>
                    <td>
                        @if($billet->statut == 'Valide')
                            <span class="status status-valide">Valide</span>
                        @elseif($billet->statut == 'Utilisé')
                            <span class="status status-utilise">Utilisé</span>
                        @else
                            <span class="status status-annule">Annulé</span>
                        @endif
                    </td>
                    <td style="font-family: monospace;">{{ $billet->code_unique }}</td>
                    <td>{{ \Carbon\Carbon::parse($billet->evenement->date)->format('d/m/Y H:i') }}</td>
                    <td>{{ $billet->nom_spectateur ?? ($billet->commande->client->prenom . ' ' . $billet->commande->client->nom) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Ce document contient les billets valides pour l'événement indiqué. Présentez-le imprimé ou sur mobile à l'entrée.
    </div>
</body>
</html>
