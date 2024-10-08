<?php
/**
 * Class managing helper functions who check User Access permissions
 *
 * @author      Orif (ThJo)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 *
 */

use CodeIgniter\CodeIgniter;

/**
 * Get the access level of the specified user.
 *
 * @param int|string|array $user User specified.
 * Int and string values corresponds to the user ID.
 * Array values corresponds to the entire user entry from database.
 *
 * @return int
 *
 */
function getUserAccessLevel(int|string|array $user): int
{
    if(is_string($user) || is_int($user))
    {
        $user_type    = model("User_model")->find($user)["fk_user_type"];
        $access_level = model("User_type_model")->find($user_type)["access_level"];
    }

    else
        $access_level = model("User_type_model")->find($user["fk_user_type"])["access_level"];

    return $access_level;
}



/* *************************************** */
/* ********** APPRENTICE CHECKS ********** */
/* *************************************** */

/**
 * Check if the current user is an apprentice.
 *
 * @return bool
 *
 */
function isCurrentUserApprentice(): bool
{
    return $_SESSION["user_access"] == config("\User\Config\UserConfig")->access_level_apprentice;
}



/**
 * Check if the specified user is an apprentice.
 *
 * @param int|string|array $user User specified.
 *
 * @return bool
 *
 */
function isSpecifiedUserApprentice(int|string|array $user): bool
{
    return getUserAccessLevel($user) == config("\User\Config\UserConfig")->access_level_apprentice;
}



/**
 * Check if the current user has apprentice access or higher.
 *
 * @return bool
 *
 */
function hasCurrentUserApprenticeAccess(): bool
{
    return isCurrentUserApprentice()
        || isCurrentUserTrainer()
        || isCurrentUserAdmin();
}



/**
 * Check if the apprentice specified is the current user (apprentice).
 *
 * @param int $apprentice_id ID od the apprentice.
 *
 * @return bool
 *
 */
function isCurrentUserSelfApprentice(int $apprentice_id): bool
{
    return isCurrentUserApprentice()
        && $apprentice_id == $_SESSION["user_id"];
}



/* ************************************ */
/* ********** TRAINER CHECKS ********** */
/* ************************************ */


/**
 * Check if the current user is a trainer.
 *
 * @return bool
 *
 */
function isCurrentUserTrainer(): bool
{
    return $_SESSION["user_access"] == config("\User\Config\UserConfig")->access_lvl_trainer;
}



/**
 * Check if the specified user is a trainer.
 *
 * @param int|array $user User specified.
 *
 * @return bool
 *
 */
function isSpecifiedUserTrainer(int|array $user): bool
{
    return getUserAccessLevel($user) == config("\User\Config\UserConfig")->access_lvl_trainer;
}



/**
 * Check if the current user has trainer access or higher.
 *
 * @return bool
 *
 */
function hasCurrentUserTrainerAccess(): bool
{
    return isCurrentUserTrainer()
        || isCurrentUserAdmin();
}



/**
 * Check if the current user is the trainer of the specified apprentice.
 * If the current user is an administrator, the function will return true.
 *
 * @param int $apprentice_id ID of the apprentice.
 *
 * @return bool
 *
 */
function isCurrentUserTrainerOfApprentice(int $apprentice_id): bool
{
    return isCurrentUserAdmin()
        || isCurrentUserTrainer()
        && model("TrainerApprenticeModel")->isTrainerLinkedToApprentice($_SESSION["user_id"], $apprentice_id);
}



/* ********************************** */
/* ********** ADMIN CHECKS ********** */
/* ********************************** */



/**
 * Check if the current user is an adminstrator.
 *
 * @return bool
 *
 */
function isCurrentUserAdmin(): bool
{
    return $_SESSION["user_access"] == config("\User\Config\UserConfig")->access_lvl_admin;
}



/**
 * Check if the specified user is an admin.
 *
 * @param int|string|array $user User specified.
 *
 * @return bool
 *
 */
function isSpecifiedUserAdmin(int|string|array $user): bool
{
    return getUserAccessLevel($user) == config("\User\Config\UserConfig")->access_lvl_admin;
}



/**
 * Check if the current user has adminstrator access.
 *
 * @return bool
 *
 */
function hasCurrentUserAdminAccess(): bool
{
    return isCurrentUserAdmin();
}