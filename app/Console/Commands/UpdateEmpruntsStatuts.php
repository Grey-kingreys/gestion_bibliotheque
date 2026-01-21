<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Emprunt;
use Carbon\Carbon;

class UpdateEmpruntsStatuts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emprunts:update-statuts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mettre à jour automatiquement les statuts des emprunts en retard';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Mise à jour des statuts des emprunts...');

        // Récupérer tous les emprunts en cours qui sont en retard
        $empruntsEnRetard = Emprunt::where('statut', 'en_cours')
            ->whereDate('date_retour_prevue', '<', Carbon::now())
            ->whereNull('date_retour_effective')
            ->get();

        $count = $empruntsEnRetard->count();

        if ($count === 0) {
            $this->info('✓ Aucun emprunt en retard à mettre à jour');
            return 0;
        }

        $this->info("⚠️  {$count} emprunt(s) en retard trouvé(s)");

        // Mettre à jour leur statut
        foreach ($empruntsEnRetard as $emprunt) {
            $joursRetard = $emprunt->joursDeRetard();
            
            $emprunt->update(['statut' => 'en_retard']);
            
            $this->line("  - Emprunt #{$emprunt->id} : {$emprunt->livre->titre} ({$joursRetard} jour(s) de retard)");
        }

        $this->info("✓ {$count} emprunt(s) mis à jour avec succès");

        return 0;
    }
}