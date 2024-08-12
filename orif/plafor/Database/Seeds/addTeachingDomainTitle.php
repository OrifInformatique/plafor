<?php
/**
 * Seed file for the table "teaching_domain_title"
 *
 * @author      Orif (ViDi, ThJo)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
namespace Plafor\Database\Seeds;

use CodeIgniter\Database\Seeder;

class addTeachingDomainTitle extends Seeder {

    public function run(){
        $titles = [
            [
                "title" => "Compétences de base élargies"
            ],
            [
                "title" => "Culture générale"
            ],
            [
                "title" => "Informatique"
            ],
            [
                "title" => "Maturité profesionnelle technique"
            ],
            [
                "title" => "Travail pratique individuel"
            ]
        ];

        foreach ($titles as $title) {
            $this->db->table("teaching_domain_title")->insert($title);
        }
    }
}