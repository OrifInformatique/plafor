<?php

namespace Plafor\Database\Seeds;

use CodeIgniter\Database\Seeder;

class addRoundMultipleColumn extends Seeder
{
    public function run()
    {
        $halfPointDomainTitleId = [
            1, // Compétences de base élargies
        ];

        $oneDecimalPointDomainTitleId = [
            2, // Culture générale
            3, // Informatique
            4, // Maturité professionnelle technique
            5, // TPI
        ];

        $this->db->table('teaching_domain')
            ->whereIn('fk_teaching_domain_title', $halfPointDomainTitleId)
            ->set(['round_multiple' => 0.5])
            ->update();

        $this->db->table('teaching_domain')
             ->whereIn('fk_teaching_domain_title',
                 $oneDecimalPointDomainTitleId)
            ->set(['round_multiple' => 0.1])
            ->update();

        $this->db->table('teaching_domain')
             ->where('round_multiple is null')
            ->set(['round_multiple' => 0.1])
            ->update();

        $this->db->table('teaching_subject')
             ->where('round_multiple is null')
            ->set(['round_multiple' => 0.1])
            ->update();
    }
}
