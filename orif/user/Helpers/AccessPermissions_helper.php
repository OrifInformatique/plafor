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
 * @param  int $apprentice_id => apprentice ID
 *
 * @return bool
 */ 
function isCurrentUserSelfApprentice(int $apprentice_id) : bool {

    return isCurrentUserTrainer()
        || ($_SESSION["user_access"] == config("\User\Config\UserConfig")->access_level_apprentice
        && $apprentice_id == $_SESSION["user_id"]);
}        



/**
 * Check if the user is a Trainer or has higher access
 *
 * @return bool
 */
function isCurrentUserTrainer() : bool {

    return $_SESSION["user_access"] >= config("\User\Config\UserConfig")->access_lvl_trainer;
}



/**
 * Check if the user is a trainer or has higher access AND
 * Check if the user is a trainer linked to the given apprentice
 *
 * @param int $apprentice_id => apprentice ID
 *
 * @return bool
 */
function isCurrentUserTrainerOfApprentice(int $apprentice_id) : bool{

    return isCurrentUserTrainer()
        && model("TrainerApprenticeModel")->isTrainerLinkedToApprentice($_SESSION["user_id"], $apprentice_id)
        || isCurrentUserAdmin();
}



/**
 * Check if the user is an Admin
 *
 * @return bool
 */
function isCurrentUserAdmin() : bool {

    return $_SESSION["user_access"] >= config("\User\Config\UserConfig")->access_lvl_admin;
}