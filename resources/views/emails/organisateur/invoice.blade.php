<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture commande #{{ $commande->id }}</title>
</head>
<body style="font-family: sans-serif; color: #111; line-height: 1.6;">
    <h2>Bonjour {{ $commande->client->prenom ?? '' }} {{ $commande->client->nom ?? '' }},</h2>
    <p>Votre facture pour la commande <strong>#{{ $commande->id }}</strong> a été générée et est attachée à cet e-mail.</p>
    <p>Montant total : <strong>{{ number_format($commande->montant_total, 2) }} DH</strong></p>
    <p>Événement : <strong>{{ $commande->evenement->titre ?? 'N/A' }}</strong></p>

    <p>Merci de votre confiance,</p>
    <p><strong>{{ config('app.name', 'TicketFlow') }}</strong></p>
</body>
</html>