<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Categorie;
use App\Models\Auteur;
use App\Models\Livre;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Créer un administrateur
        User::create([
            'login' => 'ADMIN001',
            'nom' => 'DIALLO',
            'prenom' => 'Mamadou',
            'email' => 'admin@bibliotheque.gn',
            'telephone' => '+224620000001',
            'password' => Hash::make('Admin@123'),
            'role' => 'Radmin',
            'actif' => true,
        ]);

        // Créer un bibliothécaire
        User::create([
            'login' => 'BIBLIO001',
            'nom' => 'CAMARA',
            'prenom' => 'Fatoumata',
            'email' => 'bibliothecaire@bibliotheque.gn',
            'telephone' => '+224620000002',
            'password' => Hash::make('Biblio@123'),
            'role' => 'Rbibliothecaire',
            'actif' => true,
        ]);

        // Créer des lecteurs
        User::create([
            'login' => 'ETU202401',
            'nom' => 'BAH',
            'prenom' => 'Ousmane',
            'email' => 'ousmane.bah@etudiant.gn',
            'telephone' => '+224620000003',
            'password' => Hash::make('Etudiant@123'),
            'role' => 'Rlecteur',
            'actif' => true,
        ]);

        User::create([
            'login' => 'ETU202402',
            'nom' => 'SYLLA',
            'prenom' => 'Aissatou',
            'email' => 'aissatou.sylla@etudiant.gn',
            'telephone' => '+224620000004',
            'password' => Hash::make('Etudiant@123'),
            'role' => 'Rlecteur',
            'actif' => true,
        ]);

        // Créer des catégories
        $categories = [
            ['libelle' => 'Informatique', 'description' => 'Livres sur les technologies de l\'information'],
            ['libelle' => 'Mathématiques', 'description' => 'Ouvrages de mathématiques'],
            ['libelle' => 'Physique', 'description' => 'Livres de physique'],
            ['libelle' => 'Littérature', 'description' => 'Romans et œuvres littéraires'],
            ['libelle' => 'Histoire', 'description' => 'Ouvrages historiques'],
            ['libelle' => 'Sciences Économiques', 'description' => 'Livres d\'économie'],
            ['libelle' => 'Médecine', 'description' => 'Ouvrages médicaux'],
            ['libelle' => 'Droit', 'description' => 'Livres juridiques'],
        ];

        foreach ($categories as $categorie) {
            Categorie::create($categorie);
        }

        // Créer des auteurs
        $auteurs = [
            ['nom' => 'Knuth', 'prenom' => 'Donald', 'nationalite' => 'Américaine', 'biographie' => 'Informaticien et mathématicien américain'],
            ['nom' => 'Cormen', 'prenom' => 'Thomas H.', 'nationalite' => 'Américaine', 'biographie' => 'Professeur d\'informatique'],
            ['nom' => 'Martin', 'prenom' => 'Robert C.', 'nationalite' => 'Américaine', 'biographie' => 'Ingénieur logiciel et auteur'],
            ['nom' => 'Hawking', 'prenom' => 'Stephen', 'nationalite' => 'Britannique', 'biographie' => 'Physicien théoricien'],
            ['nom' => 'Chinua', 'prenom' => 'Achebe', 'nationalite' => 'Nigériane', 'biographie' => 'Écrivain nigérian'],
            ['nom' => 'Ki-Zerbo', 'prenom' => 'Joseph', 'nationalite' => 'Burkinabè', 'biographie' => 'Historien et homme politique'],
        ];

        foreach ($auteurs as $auteur) {
            Auteur::create($auteur);
        }

        // Créer des livres
        $livres = [
            [
                'titre' => 'Introduction aux Algorithmes',
                'isbn' => '978-2100545261',
                'resume' => 'Manuel de référence sur les algorithmes',
                'nombre_exemplaires' => 5,
                'nombre_disponibles' => 5,
                'editeur' => 'Dunod',
                'annee_publication' => 2010,
                'categorie_id' => 1,
                'auteurs' => [2],
            ],
            [
                'titre' => 'Clean Code',
                'isbn' => '978-0132350884',
                'resume' => 'Guide pour écrire du code propre et maintenable',
                'nombre_exemplaires' => 3,
                'nombre_disponibles' => 3,
                'editeur' => 'Prentice Hall',
                'annee_publication' => 2008,
                'categorie_id' => 1,
                'auteurs' => [3],
            ],
            [
                'titre' => 'Une brève histoire du temps',
                'isbn' => '978-2290016497',
                'resume' => 'Exploration de l\'univers et du temps',
                'nombre_exemplaires' => 4,
                'nombre_disponibles' => 4,
                'editeur' => 'Flammarion',
                'annee_publication' => 1988,
                'categorie_id' => 3,
                'auteurs' => [4],
            ],
            [
                'titre' => 'Le monde s\'effondre',
                'isbn' => '978-2070369409',
                'resume' => 'Roman sur le colonialisme en Afrique',
                'nombre_exemplaires' => 6,
                'nombre_disponibles' => 6,
                'editeur' => 'Présence Africaine',
                'annee_publication' => 1958,
                'categorie_id' => 4,
                'auteurs' => [5],
            ],
            [
                'titre' => 'Histoire générale de l\'Afrique',
                'isbn' => '978-9232017123',
                'resume' => 'Histoire complète du continent africain',
                'nombre_exemplaires' => 2,
                'nombre_disponibles' => 2,
                'editeur' => 'UNESCO',
                'annee_publication' => 1980,
                'categorie_id' => 5,
                'auteurs' => [6],
            ],
        ];

        foreach ($livres as $livreData) {
            $auteurs = $livreData['auteurs'];
            unset($livreData['auteurs']);
            
            $livre = Livre::create($livreData);
            $livre->auteurs()->attach($auteurs);
        }

        $this->command->info('✅ Base de données peuplée avec succès !');
        $this->command->info('📊 4 utilisateurs créés');
        $this->command->info('📚 5 livres ajoutés');
        $this->command->info('📁 8 catégories créées');
        $this->command->info('✍️ 6 auteurs ajoutés');
    }
}