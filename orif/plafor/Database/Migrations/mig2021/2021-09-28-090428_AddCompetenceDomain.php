<?php
/**
 * Fichier de migration créant la table
 * competence_domain avec les nouveaux domaines de compétences
 *
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
namespace Plafor\Database\Migrations\mig2021;
use CodeIgniter\Database\Migration;

class AddCompetenceDomain extends Migration {

    public function up() {
        
        /* Add new datas for new "ordonnance sur la formation professionnelle" */
        $seeder = \Config\Database::seeder();
        $seeder->call('\Plafor\Database\Seeds\addCompetenceDomain2021Datas');
    }

    public function down() {
        
    }
}