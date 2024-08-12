<?php
/**
 * Seed file for the table "teaching_subject"
 *
 * @author      Orif (ViDi, ThJo)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
namespace Plafor\Database\Seeds;

use CodeIgniter\Database\Seeder;

class addTeachingSubject extends Seeder{

    public function run(){

        $subjects = [
            // ****** Infrastructure ******
            [ // Compétences de base élargies
                "fk_teaching_domain" => 1,
                "name" => "Mathématiques",
                "subject_weight" => 0.0,
            ],
            [ 
                "fk_teaching_domain" => 1,
                "name" => "Anglais technique",
                "subject_weight" => 0.0,
            ],
            [ // Culture générale
                "fk_teaching_domain" => 2,
                "name" => "ECG",
                "subject_weight" => 0.0,
            ],
            [ 
                "fk_teaching_domain" => 2,
                "name" => "Travail personnel d'appronfondissement (TPA)",
                "subject_weight" => 0.0,
            ],
            [ 
                "fk_teaching_domain" => 2,
                "name" => "Examen final",
                "subject_weight" => 0.0,
            ],
            [ // TPI
                "fk_teaching_domain" => 4,
                "name" => "Travail pratique individuel (TPI)",
                "subject_weight" => 0.0,
            ],
            // ****** Developer ******
            [ // Compétences de base élargies
                "fk_teaching_domain" => 5,
                "name" => "Mathématiques",
                "subject_weight" => 0.0,
            ],
            [ 
                "fk_teaching_domain" => 5,
                "name" => "Anglais technique",
                "subject_weight" => 0.0,
            ],
            [ // Culture générale
                "fk_teaching_domain" => 6,
                "name" => "ECG",
                "subject_weight" => 0.0,
            ],
            [ 
                "fk_teaching_domain" => 6,
                "name" => "Travail personnel d'appronfondissement (TPA)",
                "subject_weight" => 0.0,
            ],
            [ 
                "fk_teaching_domain" => 6,
                "name" => "Examen final",
                "subject_weight" => 0.0,
            ], 
            [ // TPI
                "fk_teaching_domain" => 8,
                "name" => "Travail pratique individuel (TPI)",
                "subject_weight" => 0.0,
            ],
            // ****** Operator ******
            [ // Compétences de base élargies
                "fk_teaching_domain" => 9,
                "name" => "Anglais technique",
                "subject_weight" => 0.0,
            ],
            [ // Culture générale
                "fk_teaching_domain" => 10,
                "name" => "ECG",
                "subject_weight" => 0.0,
            ],
            [ 
                "fk_teaching_domain" => 10,
                "name" => "Travail personnel d'appronfondissement (TPA)",
                "subject_weight" => 0.0,
            ],
            [ 
                "fk_teaching_domain" => 10,
                "name" => "Examen final",
                "subject_weight" => 0.0,
            ],
            [ // TPI
                "fk_teaching_domain" => 12,
                "name" => "Travail pratique individuel (TPI)",
                "subject_weight" => 0.0,
            ],
            // ****** Maturité profesionnelle technique ******
            [ 
                "fk_teaching_domain" => 13,
                "name" => "Allemand",
                "subject_weight" => 0.0,
            ],
            [ 
                "fk_teaching_domain" => 13,
                "name" => "Anglais",
                "subject_weight" => 0.0,
            ],
            [ 
                "fk_teaching_domain" => 13,
                "name" => "Économie et droit",
                "subject_weight" => 0.0,
            ],
            [ 
                "fk_teaching_domain" => 13,
                "name" => "Français",
                "subject_weight" => 0.0,
            ],
            [ 
                "fk_teaching_domain" => 13,
                "name" => "Histoire et institutions politiques",
                "subject_weight" => 0.0,
            ],
            [ 
                "fk_teaching_domain" => 13,
                "name" => "Mathématiques",
                "subject_weight" => 0.0,
            ],            
        ];

        foreach ($subjects as $subject) {
            $this->db->table("teaching_subject")->insert($subject);
        }
    }
}