<?php
/**
 * Seed file for the table "teaching_domain"
 *
 * @author      Orif (ViDi, ThJo)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
namespace Plafor\Database\Seeds;

use CodeIgniter\Database\Seeder;

class addTeachingDomain extends Seeder {

    public function run(){

        $domains = [
            // ****** Infrastructure ******
            [ // Compétences de base élargies
                "fk_course_plan" => 5,
                "fk_teaching_domain_title" => 1,
                "domain_weight" => 0.1,
                "is_eliminatory" => false,
            ],
            [ // Culture générale
                "fk_course_plan" => 5,
                "fk_teaching_domain_title" => 2,
                "domain_weight" => 0.2,
                "is_eliminatory" => false,
            ],
            [ // Informatique
                "fk_course_plan" => 5,
                "fk_teaching_domain_title" => 3,
                "domain_weight" => 0.3,
                "is_eliminatory" => true,
            ],
            [ // TPI
                "fk_course_plan" => 5,
                "fk_teaching_domain_title" => 5,
                "domain_weight" => 0.4,
                "is_eliminatory" => true,
            ],
            // ****** Developer ******
            [ // Compétences de base élargies
                "fk_course_plan" => 6,
                "fk_teaching_domain_title" => 1,
                "domain_weight" => 0.1,
                "is_eliminatory" => false,
            ],
            [ // Culture générale
                "fk_course_plan" => 6,
                "fk_teaching_domain_title" => 2,
                "domain_weight" => 0.2,
                "is_eliminatory" => false,
            ],
            [ // Informatique
                "fk_course_plan" => 6,
                "fk_teaching_domain_title" => 3,
                "domain_weight" => 0.3,
                "is_eliminatory" => true,
            ],
            [ // TPI
                "fk_course_plan" => 6,
                "fk_teaching_domain_title" => 5,
                "domain_weight" => 0.4,
                "is_eliminatory" => true,
            ],
            // ****** Operator ******
            [ // Compétences de base élargies
                "fk_course_plan" => 4,
                "fk_teaching_domain_title" => 1,
                "domain_weight" => 0.1,
                "is_eliminatory" => false,
            ],
            [ // Culture générale
                "fk_course_plan" => 4,
                "fk_teaching_domain_title" => 2,
                "domain_weight" => 0.2,
                "is_eliminatory" => false,
            ],
            [ // Informatique
                "fk_course_plan" => 4,
                "fk_teaching_domain_title" => 3,
                "domain_weight" => 0.3,
                "is_eliminatory" => true,
            ],
            [ // TPI
                "fk_course_plan" => 4,
                "fk_teaching_domain_title" => 5,
                "domain_weight" => 0.4,
                "is_eliminatory" => true,
            ],
            // ****** Maturité professionnelle technique ******
            [
                "fk_course_plan" => 5,
                "fk_teaching_domain_title" => 4,
                "domain_weight" => 0.0,
                "is_eliminatory" => false,
            ],
            [
                "fk_course_plan" => 6,
                "fk_teaching_domain_title" => 4,
                "domain_weight" => 0.0,
                "is_eliminatory" => false,
            ],
        ];

        foreach ($domains as $domain) {
            $this->db->table("teaching_domain")->insert($domain);
        }
    }
}