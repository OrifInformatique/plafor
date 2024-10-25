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
        $domains_modules = [
            [ // 7    => Developer
                "fk_teaching_domain" => 7,
                "fk_teaching_module" => [1,2,3,4,5,6,7,17,18,19,
                                        22,24,25,27,28,30,31,32,33,34,
                                        35,36,37,38,43,49,50,51,52,53,54,
                                        55,56,57,58,59,60,62],
            ],
            [ // 3    => Infrastructure
                "fk_teaching_domain" => 3,
                "fk_teaching_module" => [1,2,3,4,5,6,7,8,10,11,
                                        12,13,14,15,16,17,18,20,21,23,
                                        24,25,26,27,28,30,31,32,33,34,
                                        35,36,37,38,46,49,50,56,57,60],
            ],
            [ // 11   => Operator
                "fk_teaching_domain" => 11,
                "fk_teaching_module" => [6,7,8,9,10,29,39,40,41,42,
                                        44,45,47,48,60,61],
            ],
        ];

        foreach ($domains_modules as $domain) {
            foreach($domain["fk_teaching_module"] as $module){
                $data = [
                    "fk_teaching_domain" => $domain["fk_teaching_domain"],
                    "fk_teaching_module" => $module,
                ];
                $this->db->table("teaching_domain_module")->insert($data);
            }
        }
    }
}