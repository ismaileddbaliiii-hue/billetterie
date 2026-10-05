<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture #{{ $commande->id }}</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; color:#111; margin:0; padding:20px; }
        .header { margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 28px; }
        .header .meta { color: #555; font-size: 12px; }
        .box { border: 1px solid #ddd; border-radius: 8px; padding: 12px; margin-bottom: 16px; }
        .box h2 { margin-top: 0; font-size: 14px; }
        .row { display: flex; justify-content: space-between; margin-bottom: 6px; }
        .row strong { color:#333; }
        .table { width:100%; border-collapse: collapse; margin-bottom: 12px; }
        .table th, .table td { border:1px solid #ccc; padding:7px; font-size: 12px; }
        .table th { background:#f2f2f2; text-align:left; }
        .text-right { text-align:right; }
        .text-center { text-align:center; }
        .small { font-size:10px; color:#555; }
        .footer { border-top:1px solid #ccc; margin-top:20px; padding-top:10px; font-size:10px; color:#555; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Facture n°{{ $commande->id }}</h1>
        <p class="meta">Émise le {{ \Carbon\Carbon::parse($commande->date)->format('d/m/Y H:i') }}</p>
    </div>

    <div class="row">
        <div class="box" style="flex:1;margin-right:8px;">
            <h2>Organisateur</h2>
            <p><strong>{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</strong></p>
            <p>{{ Auth::user()->email }}</p>
            @if(Auth::user()->telephone)<p>{{ Auth::user()->telephone }}</p>@endif
        </div>
        <div class="box" style="flex:1;margin-left:8px;">
            <h2>Client</h2>
            <p><strong>{{ $commande->client->prenom ?? 'N/A' }} {{ $commande->client->nom ?? '' }}</strong></p>
            <p>{{ $commande->client->email ?? '-' }}</p>
            @if($commande->client->telephone)<p>{{ $commande->client->telephone }}</p>@endif
        </div>
    </div>

    <div class="box">
        <h2>Informations de paiement</h2>
        <p>Prestataire: {{ $commande->paiement->prestataire ?? 'N/A' }}</p>
        <p>Référence: {{ $commande->paiement->reference ?? 'N/A' }}</p>
        @if($commande->paiement && $commande->paiement->date)
            <p>Date: {{ \Carbon\Carbon::parse($commande->paiement->date)->format('d/m/Y H:i') }}</p>
        @endif
    </div>

    <div class="box">
        <h2>Événement</h2>
        <p>{{ $commande->evenement->titre ?? 'N/A' }}</p>
        <p>Date: {{ \Carbon\Carbon::parse($commande->evenement->date ?? now())->format('d/m/Y H:i') }}</p>
        <p>Lieu: {{ $commande->evenement->lieu->nom ?? 'N/A' }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Billet</th>
                <th>Type</th>
                <th class="text-center">Quantité</th>
                <th class="text-right">Prix unitaire</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($commande->billets as $billet)
                <tr>
                    <td>{{ $billet->evenement->titre ?? 'N/A' }}</td>
                    <td>{{ $billet->typeBillet->nom ?? 'Standard' }}</td>
                    <td class="text-center">1</td>
                    <td class="text-right">{{ number_format($billet->prix, 2) }} DH</td>
                    <td class="text-right">{{ number_format($billet->prix, 2) }} DH</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="row" style="margin-top:8px; font-weight:bold; font-size:14px;">
        <span>Total TTC</span>
        <span>{{ number_format($commande->montant_total, 2) }} DH</span>
    </div>

    <div class="footer">
        <p>Toutes taxes incluses.</p>
        <p>Cette facture est générée automatiquement. Merci de votre confiance.</p>
        <p>Facture générée le {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>