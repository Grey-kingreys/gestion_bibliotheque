<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Emprunt;
use App\Models\Livre;
use App\Models\User;
use App\Models\Categorie;
use App\Models\Penalite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatistiqueController extends Controller
{
    /**
     * Afficher les statistiques globales
     */
    public function index()
    {
        // ========== STATISTIQUES GLOBALES ==========
        $statsGlobales = [
            'total_utilisateurs' => User::count(),
            'utilisateurs_actifs' => User::where('actif', true)->count(),
            'total_livres' => Livre::count(),
            'livres_disponibles' => Livre::where('disponible', true)->where('nombre_disponibles', '>', 0)->count(),
            'total_emprunts' => Emprunt::count(),
            'emprunts_en_cours' => Emprunt::where('statut', 'en_cours')->count(),
            'emprunts_en_retard' => Emprunt::where('statut', 'en_retard')->count(),
            'total_penalites' => Penalite::sum('montant'),
            'penalites_non_payees' => Penalite::where('payee', false)->sum('montant'),
        ];

        // ========== EMPRUNTS PAR MOIS (12 derniers mois) ==========
        $empruntsParMois = Emprunt::select(
                DB::raw('DATE_FORMAT(date_emprunt, "%Y-%m") as mois'),
                DB::raw('COUNT(*) as total')
            )
            ->where('date_emprunt', '>=', Carbon::now()->subMonths(12))
            ->groupBy('mois')
            ->orderBy('mois', 'asc')
            ->get()
            ->map(function($item) {
                return [
                    'mois' => Carbon::parse($item->mois . '-01')->format('M Y'),
                    'total' => $item->total
                ];
            });

        // ========== TOP 10 LIVRES LES PLUS EMPRUNTÉS ==========
        $topLivres = Livre::withCount('emprunts')
            ->orderBy('emprunts_count', 'desc')
            ->limit(10)
            ->get()
            ->map(function($livre) {
                return [
                    'titre' => $livre->titre,
                    'auteurs' => $livre->auteurs->pluck('full_name')->implode(', '),
                    'total_emprunts' => $livre->emprunts_count,
                ];
            });

        // ========== CATÉGORIES LES PLUS POPULAIRES ==========
        $categoriesPopulaires = Categorie::withCount('livres')
            ->with(['livres' => function($query) {
                $query->withCount('emprunts');
            }])
            ->get()
            ->map(function($categorie) {
                $totalEmprunts = $categorie->livres->sum('emprunts_count');
                return [
                    'libelle' => $categorie->libelle,
                    'nombre_livres' => $categorie->livres_count,
                    'total_emprunts' => $totalEmprunts,
                ];
            })
            ->sortByDesc('total_emprunts')
            ->take(5);

        // ========== TAUX DE RETARD ==========
        $totalEmpruntsTermines = Emprunt::whereIn('statut', ['retourne', 'en_retard'])->count();
        $empruntsEnRetard = Emprunt::where('statut', 'en_retard')
            ->orWhere(function($query) {
                $query->where('statut', 'retourne')
                      ->whereNotNull('date_retour_effective')
                      ->whereRaw('date_retour_effective > date_retour_prevue');
            })
            ->count();
        
        $tauxRetard = $totalEmpruntsTermines > 0 
            ? round(($empruntsEnRetard / $totalEmpruntsTermines) * 100, 2) 
            : 0;

        // ========== UTILISATEURS LES PLUS ACTIFS ==========
        $utilisateursActifs = User::where('role', 'Rlecteur')
            ->withCount('emprunts')
            ->orderBy('emprunts_count', 'desc')
            ->limit(10)
            ->get()
            ->map(function($user) {
                return [
                    'nom' => $user->full_name,
                    'login' => $user->login,
                    'total_emprunts' => $user->emprunts_count,
                ];
            });

        // ========== RÉPARTITION DES STATUTS D'EMPRUNTS ==========
        $repartitionStatuts = [
            'en_attente' => Emprunt::where('statut', 'en_attente')->count(),
            'en_cours' => Emprunt::where('statut', 'en_cours')->count(),
            'retourne' => Emprunt::where('statut', 'retourne')->count(),
            'en_retard' => Emprunt::where('statut', 'en_retard')->count(),
            'rejete' => Emprunt::where('statut', 'rejete')->count(),
        ];

        // ========== STATISTIQUES PAR PÉRIODE ==========
        $aujourdhui = Carbon::today();
        $statsParPeriode = [
            'aujourd_hui' => [
                'emprunts' => Emprunt::whereDate('date_emprunt', $aujourdhui)->count(),
                'retours' => Emprunt::whereDate('date_retour_effective', $aujourdhui)->count(),
            ],
            'cette_semaine' => [
                'emprunts' => Emprunt::whereBetween('date_emprunt', [
                    Carbon::now()->startOfWeek(), 
                    Carbon::now()->endOfWeek()
                ])->count(),
                'retours' => Emprunt::whereBetween('date_retour_effective', [
                    Carbon::now()->startOfWeek(), 
                    Carbon::now()->endOfWeek()
                ])->count(),
            ],
            'ce_mois' => [
                'emprunts' => Emprunt::whereMonth('date_emprunt', Carbon::now()->month)
                    ->whereYear('date_emprunt', Carbon::now()->year)
                    ->count(),
                'retours' => Emprunt::whereMonth('date_retour_effective', Carbon::now()->month)
                    ->whereYear('date_retour_effective', Carbon::now()->year)
                    ->count(),
            ],
        ];

        // ========== DURÉE MOYENNE D'EMPRUNT ==========
        $dureesMoyennes = Emprunt::where('statut', 'retourne')
            ->whereNotNull('date_retour_effective')
            ->get()
            ->map(function($emprunt) {
                return $emprunt->date_emprunt->diffInDays($emprunt->date_retour_effective);
            });
        
        $dureeMoyenne = $dureesMoyennes->count() > 0 
            ? round($dureesMoyennes->avg(), 1) 
            : 0;

        return view('admin.statistiques.index', compact(
            'statsGlobales',
            'empruntsParMois',
            'topLivres',
            'categoriesPopulaires',
            'tauxRetard',
            'utilisateursActifs',
            'repartitionStatuts',
            'statsParPeriode',
            'dureeMoyenne'
        ));
    }

    /**
     * Exporter les statistiques en CSV
     */
    public function export()
    {
        // Récupérer toutes les statistiques
        $emprunts = Emprunt::with(['user', 'livre', 'penalite'])->get();

        $filename = 'statistiques_bibliotheque_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($emprunts) {
            $file = fopen('php://output', 'w');
            
            // En-têtes CSV
            fputcsv($file, [
                'ID Emprunt',
                'Lecteur',
                'Livre',
                'Date Emprunt',
                'Date Retour Prévue',
                'Date Retour Effective',
                'Statut',
                'Jours de Retard',
                'Pénalité (GNF)',
            ]);

            // Données
            foreach ($emprunts as $emprunt) {
                fputcsv($file, [
                    $emprunt->id,
                    $emprunt->user->full_name,
                    $emprunt->livre->titre,
                    $emprunt->date_emprunt->format('d/m/Y'),
                    $emprunt->date_retour_prevue->format('d/m/Y'),
                    $emprunt->date_retour_effective ? $emprunt->date_retour_effective->format('d/m/Y') : 'N/A',
                    $emprunt->statut,
                    $emprunt->estEnRetard() ? $emprunt->joursDeRetard() : 0,
                    $emprunt->penalite ? $emprunt->penalite->montant : 0,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}