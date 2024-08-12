<?php
/**
 * Seed file for the table "teaching_domain_module"
 *
 * @author      Orif (ViDi, ThJo)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
namespace Plafor\Database\Seeds;

use CodeIgniter\Database\Seeder;

class addTeachingDomainModule extends Seeder {

    public function run(){

        $domains = [
           
        ];

        foreach ($domains as $domain) {
            $this->db->table("teaching_domain_module")->insert($domain);
        }
    }
}