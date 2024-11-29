<?php

/**
 * Seed file for the table "acquisition_status"
 *
 * @author      Orif (ViDi, ThJo)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 *
 */

namespace Plafor\Database\Seeds;

use CodeIgniter\Database\Seeder;

class addAcquisitionStatuses extends Seeder
{
    public function run()
    {
        for ($objective_id = 333; $objective_id <= 506 ; $objective_id++)
        {
            $status =
            [
                "fk_objective"           => $objective_id,
                "fk_user_course"         => 1,
                "fk_acquisition_level"   => 1,
            ];

            $this->db->table("acquisition_status")->insert($status);
        }

        for ($objective_id = 507; $objective_id <= 649 ; $objective_id++)
        {
            $status =
            [
                "fk_objective"           => $objective_id,
                "fk_user_course"         => 2,
                "fk_acquisition_level"   => 1,
            ];

            $this->db->table("acquisition_status")->insert($status);
        }
    }
}