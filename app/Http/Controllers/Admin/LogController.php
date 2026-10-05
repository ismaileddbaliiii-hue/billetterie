<?php
// app/Http/Controllers/Admin/LogController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    /**
     * Afficher la liste des logs
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('user');
        
        // Filtre par niveau
        if ($request->filled('niveau')) {
            $query->where('niveau', $request->niveau);
        }
        
        // Filtre par action
        if ($request->filled('action')) {
            $query->where('action', 'like', "%{$request->action}%");
        }
        
        // Filtre par utilisateur
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        // Filtre par date
        if ($request->filled('date_from')) {
            $query->whereDate('timestamp', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('timestamp', '<=', $request->date_to);
        }
        
        // Recherche globale
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('table_cible', 'like', "%{$search}%")
                  ->orWhere('ip', 'like', "%{$search}%");
            });
        }
        
        $logs = $query->orderBy('timestamp', 'desc')->paginate(20);
        
        // Statistiques
        $stats = [
            'total' => ActivityLog::count(),
            'critical' => ActivityLog::where('niveau', 'CRITICAL')->count(),
            'error' => ActivityLog::where('niveau', 'ERROR')->count(),
            'warning' => ActivityLog::where('niveau', 'WARNING')->count(),
            'info' => ActivityLog::where('niveau', 'INFO')->count(),
            'today' => ActivityLog::whereDate('timestamp', today())->count(),
            'this_week' => ActivityLog::whereBetween('timestamp', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => ActivityLog::whereMonth('timestamp', now()->month)->count(),
        ];
        
        // Actions distinctes pour le filtre
        $actions = ActivityLog::select('action')
            ->distinct()
            ->pluck('action')
            ->filter();
        
        // Utilisateurs pour le filtre
        $userIds = ActivityLog::whereNotNull('user_id')
            ->distinct()
            ->pluck('user_id')
            ->filter();

        $users = User::whereIn('id', $userIds)
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();

        return view('admin.logs.index', compact('logs', 'stats', 'actions', 'users'));
    }
    
    /**
     * Afficher les détails d'un log
     */
    public function show($id)
    {
        $log = ActivityLog::with('user')->findOrFail($id);
        
        return view('admin.logs.show', compact('log'));
    }
    
    /**
     * Exporter les logs en CSV
     */
    public function export(Request $request)
    {
        $query = ActivityLog::with('user');
        
        // Appliquer les mêmes filtres que l'index
        if ($request->filled('niveau')) {
            $query->where('niveau', $request->niveau);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        if ($request->filled('action')) {
            $query->where('action', 'like', "%{$request->action}%");
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('table_cible', 'like', "%{$search}%")
                  ->orWhere('ip', 'like', "%{$search}%");
            });
        }
        
        $logs = $query->orderBy('timestamp', 'desc')->get();
        
        $filename = 'logs_' . now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        
        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // En-têtes CSV (UTF-8 BOM pour Excel)
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, [
                'ID', 'Date', 'Utilisateur', 'Action', 'Table cible', 
                'ID enregistrement', 'IP', 'Niveau', 'Anciennes valeurs', 'Nouvelles valeurs'
            ]);
            
            // Données
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->created_at,
                    $log->user ? $log->user->email : 'Système',
                    $log->action,
                    $log->table_cible,
                    $log->record_id,
                    $log->ip,
                    $log->niveau,
                    $log->old_values,
                    $log->new_values,
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Nettoyer les logs anciens
     */
    public function clean(Request $request)
    {
        $months = $request->input('months', 12);
        $dateLimit = now()->subMonths($months);
        
        $deleted = ActivityLog::where('timestamp', '<', $dateLimit)->delete();
        
        return redirect()->route('admin.logs.index')
            ->with('success', "{$deleted} logs plus anciens que {$months} mois ont été supprimés.");
    }
    
    /**
     * Supprimer un log
     */
    public function destroy($id)
    {
        $log = ActivityLog::findOrFail($id);
        $log->delete();
        
        return redirect()->route('admin.logs.index')
            ->with('success', 'Log supprimé avec succès.');
    }
    
    /**
     * Supprimer tous les logs d'un certain niveau
     */
    public function deleteByLevel($niveau)
    {
        $deleted = ActivityLog::where('niveau', strtoupper($niveau))->delete();
        
        return redirect()->route('admin.logs.index')
            ->with('success', "{$deleted} logs de niveau {$niveau} ont été supprimés.");
    }
}