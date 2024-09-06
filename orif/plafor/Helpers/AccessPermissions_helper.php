<?php
/**
 * Class managing helper functions who check User Access permissions
 * File NEED to be like: FileName_helper.php
 *
 * @author      Orif (ViDi, ThJo)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

use CodeIgniter\CodeIgniter;
use User\Config\UserConfig; // Test
use Config\UserConfig as ConfigUserConfig; // Test


/**
 * Check if the user is an Apprentice or has higher access
 *
 * @return bool
 */
function isCurrentUserApprentice() : bool {

    return $_SESSION["user_access"] >= config("\User\Config\UserConfig")->access_level_apprentice;
}    



/**
 * Check if the user is a Trainer or has higher access OR
 * Check if the user is an Apprentice AND if it's is personnal page
 *
 * @param  int $course_plan_id => apprentice ID
 *
 * @return bool
 */ 
function isCurrentUserSelfApprentice(int $course_plan_id) : bool {

    return isCurrentUserTrainer()
        || ($_SESSION["user_access"] == config("\User\Config\UserConfig")->access_level_apprentice
        && $course_plan_id == $_SESSION["user_id"]);
}        



/**
 * Check if the user is a Trainer or has higher access
 *
 * @return bool
 */
function isCurrentUserTrainer() : bool {
    // return $_SESSION["user_access"] < config("\User\Config\UserConfig")->access_lvl_trainer
    // return $_SESSION["user_access"] < config(UserConfig::class)->access_lvl_trainer; // Test if this work
    return $_SESSION["user_access"] >= config(ConfigUserConfig::class)->access_lvl_trainer; // Test if this work        }
}



/**
 * Check if the user is a trainer or has higher access AND
 * Check if the user is linked to the given apprentice
 *
 * @param int $course_plan_id => apprentice ID
 *
 * @return bool
 */
function isCurrentUserTrainerOfApprentice(int $course_plan_id) : bool {

    return isCurrentUserTrainer()
        && model("TrainerApprenticeModel")->isTrainerLinkedToApprentice($_SESSION["user_id"], $course_plan_id);
}



/**
 * Check if the user is an Admin
 *
 * @return bool
 */
function isCurrentUserAdmin() : bool {

    return $_SESSION["user_access"] >= config("\User\Config\UserConfig")->access_lvl_admin;
}