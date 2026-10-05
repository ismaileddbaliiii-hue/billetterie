<?php
// routes/api.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\BilletVerificationController;

// ==================== API ROUTES V1 ====================
Route::prefix('v1')->group(function () {
    
    // Route pour générer le QR code
    Route::get('/generate-qr/{code}', [TicketController::class, 'generateQRCode']);
    
    // Route pour vérifier un billet
    Route::get('/verify/{code}', [BilletVerificationController::class, 'verify']);
    
    // Routes protégées
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/validate-ticket/{code}', [TicketController::class, 'validateTicket']);
        Route::get('/download-ticket/{code}', [TicketController::class, 'download']);
        Route::get('/my-tickets', [TicketController::class, 'myTickets']);
        Route::get('/tickets/{code}', [TicketController::class, 'show']);
    });
});

// Webhooks (commentés pour l'instant)
// Route::post('/webhooks/stripe', [App\Http\Controllers\Api\WebhookController::class, 'handleStripe']);
// Route::post('/webhooks/paypal', [App\Http\Controllers\Api\WebhookController::class, 'handlePaypal']);