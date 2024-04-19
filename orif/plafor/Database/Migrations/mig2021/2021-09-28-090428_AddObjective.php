<?php
/**
 * Fichier de migration créant la table
 * competence_domain avec les nouveaux objectifs si n'existent pas
 *
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
namespace Plafor\Database\Migrations\mig2021;
use CodeIgniter\Database\Migration;

class AddObjective extends Migration {

    public function up() {

        /* Add new datas for new "ordonnance sur la formation professionnelle" */
        $seeder = \Config\Database::seeder();
        $seeder->call('\Plafor\Database\Seeds\addObjective2021Datas');
    }

    public function down() {
        
    }
}