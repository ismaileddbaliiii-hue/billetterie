<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Billet;
use App\Models\Commande;
use App\Models\Evenement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Afficher le formulaire de profil
     */
    public function edit()
    {
        $user = Auth::user();
        $clientId = $user->id;

        $totalCommandes = Commande::where('client_id', $clientId)->count();
        $commandesConfirmees = Commande::where('client_id', $clientId)->where('statut', 'Confirmée')->count();
        $totalBillets = Billet::whereHas('commande', function ($query) use ($clientId) {
            $query->where('client_id', $clientId);
        })->count();
        $billetsValides = Billet::whereHas('commande', function ($query) use ($clientId) {
            $query->where('client_id', $clientId);
        })->where('statut', 'Valide')->count();
        $totalDepense = Commande::where('client_id', $clientId)->where('statut', 'Confirmée')->sum('montant_total');

        $evenementsRecents = Evenement::where('date', '>=', now())
            ->where('statut', 'Publié')
            ->with('lieu')
            ->orderBy('date')
            ->take(6)
            ->get();

        $commandesRecentes = Commande::where('client_id', $clientId)
            ->with('billets.evenement')
            ->latest()
            ->take(5)
            ->get();

        return view('client.profil.edit', compact(
            'user',
            'totalCommandes',
            'commandesConfirmees',
            'totalBillets',
            'billetsValides',
            'totalDepense',
            'evenementsRecents',
            'commandesRecentes'
        ));
    }

    /**
     * Mettre à jour le profil
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'prenom' => 'required|string|max:100',
            'nom' => 'required|string|max:100',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
            'telephone' => 'nullable|string|max:20',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }

            $file = $request->file('profile_image');
            $path = $file->store('uploads/profile_images', 'public');
            $validated['profile_image'] = $path;
        }

        $user->update($validated);

        return redirect()->route('client.profil.edit')
            ->with('success', 'Votre profil a été mis à jour avec succès.');
    }

    /**
     * Changer le mot de passe
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('client.profil.edit')
            ->with('success', 'Votre mot de passe a été changé avec succès.');
    }

    /**
     * Exporter les données personnelles (RGPD)
     */
    public function export()
    {
        $user = Auth::user();
        
        $data = [
            'user' => $user->only(['id', 'prenom', 'nom', 'email', 'telephone', 'created_at']),
            'commandes' => $user->commandes()->with(['billets.evenement'])->get(),
            'billets' => $user->billets()->with('evenement')->get(),
        ];

        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Supprimer le compte (RGPD)
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password',
        ]);

        $user = Auth::user();
        
        // Logout before deletion
        Auth::logout();
        
        // Delete the user
        $user->delete();

        return redirect('/')
            ->with('success', 'Votre compte a été supprimé avec succès.');
    }
}