<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Billets - {{ $client->prenom }} {{ $client->nom }}</title>
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
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 16px;
        }
        .header h1 {
            font-size: 22px;
            margin-bottom: 6px;
            color: #1f2937;
        }
        .header p {
            font-size: 12px;
            color: #4b5563;
            margin-bottom: 4px;
        }
        .client-info {
            background: #f8fafc;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #3b82f6;
        }
        .client-info h2 {
            font-size: 16px;
            margin-bottom: 8px;
            color: #1f2937;
        }
        .client-info p {
            font-size: 12px;
            color: #4b5563;
            margin-bottom: 2px;
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
            margin-top: 20px;
        }
        th, td {
            padding: 10px 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        th {
            background: #f9fafb;
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #374151;
        }
        tr:nth-child(even) {
            background: #f9fafb;
        }
        .status {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 500;
            text-transform: uppercase;
        }
        .status-valide {
            background: #dcfce7;
            color: #166534;
        }
        .status-invalide {
            background: #fee2e2;
            color: #991b1b;
        }
        .qr-code {
            width: 60px;
            height: 60px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
        }
        .total-row {
            background: #f3f4f6 !important;
            font-weight: bold;
        }
        .total-row td {
            border-top: 2px solid #d1d5db;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Billets - {{ $client->prenom }} {{ $client->nom }}</h1>
        <p>Généré le {{ date('d/m/Y à H:i') }}</p>
    </div>

    <div class="client-info">
        <h2>Informations client</h2>
        <p><strong>Nom complet:</strong> {{ $client->prenom }} {{ $client->nom }}</p>
        <p><strong>Email:</strong> {{ $client->email }}</p>
        <p><strong>Téléphone:</strong> {{ $client->telephone ?? 'Non renseigné' }}</p>
        <p><strong>Date d'inscription:</strong> {{ $client->date_creation->format('d/m/Y') }}</p>
        <p><strong>Statut:</strong> {{ $client->active ? 'Actif' : 'Inactif' }}</p>
    </div>

    <div class="summary">
        <div class="box">
            <strong>{{ $billets->count() }}</strong>
            Total billets
        </div>
        <div class="box">
            <strong>{{ $billets->where('statut', 'Valide')->count() }}</strong>
            Billets valides
        </div>
        <div class="box">
            <strong>{{ number_format($billets->sum('prix'), 2, ',', ' ') }} DH</strong>
            Montant total
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Code unique</th>
                <th>Événement</th>
                <th>Type</th>
                <th>Prix</th>
                <th>Quantité</th>
                <th>Total</th>
                <th>Statut</th>
                <th>Commande</th>
                <th>QR Code</th>
            </tr>
        </thead>
        <tbody>
            @foreach($billets as $billet)
            <tr>
                <td>{{ $billet->code_unique }}</td>
                <td>
                    <strong>{{ $billet->evenement->titre ?? 'Événement inconnu' }}</strong><br>
                    <small>{{ optional($billet->evenement?->date)->format('d/m/Y H:i') ?? 'Date non définie' }}</small><br>
                    <small>{{ $billet->evenement->lieu->nom ?? 'Lieu non défini' }}</small>
                </td>
                <td>{{ $billet->typeBillet->nom ?? 'Type supprimé' }}</td>
                <td>{{ number_format($billet->prix, 2, ',', ' ') }} DH</td>
                <td>1</td>
                <td>{{ number_format($billet->prix, 2, ',', ' ') }} DH</td>
                <td>
                    <span class="status {{ $billet->statut == 'Valide' ? 'status-valide' : 'status-invalide' }}">
                        {{ $billet->statut }}
                    </span>
                </td>
                <td>
                    <small>{{ $billet->commande->reference }}</small><br>
                    <small>{{ $billet->commande->created_at->format('d/m/Y') }}</small>
                </td>
                <td>
                    @if($billet->code_unique)
                        <img src="data:image/svg+xml;base64,{{ base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(100)->generate($billet->code_unique)) }}" alt="QR Code" class="qr-code">
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Billetterie - Système de gestion de billets</p>
        <p>Document généré automatiquement le {{ date('d/m/Y à H:i:s') }}</p>
    </div>
</body>
</html>