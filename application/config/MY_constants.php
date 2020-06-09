<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| CUSTOM CONSTANTS
|--------------------------------------------------------------------------
|
| These are constants defined specially for this application.
|
*/

define('FORMATION_NUMBER_MAX_LENGTH',5);
define('OFFICIAL_NAME_MAX_LENGTH',100);
define('SYMBOL_MAX_LENGTH',10);
define('COMPETENCE_DOMAIN_NAME_MAX_LENGTH',100);
define('OPERATIONAL_COMPETENCE_NAME_MAX_LENGTH',150);
define('TAXONOMY_MAX_VALUE',6);
define('OBJECTIVE_NAME_MAX_LENGTH',350);
define('SQL_TEXT_MAX_LENGTH',65535);


/* Access levels */
define('ACCESS_LVL_APPRENTICE', 1);
define('ACCESS_LVL_TRAINER', 2);
define('ACCESS_LVL_ADMIN', 4);
