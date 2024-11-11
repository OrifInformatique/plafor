<?php
/**
 * File managing Global Constant and helper functions for the Unit testing
 *
 * @author      Orif (ThJo)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 *
 */

use CodeIgniter\CodeIgniter;

// Constant for units testing
// User types
const ADMIN_USER_TYPE = 1;
const TRAINER_USER_TYPE = 2;
const APPRENTICE_USER_TYPE = 3;

const FAKE_USER_TYPE = 999;

// Admin
const ADMIN_ID = 100;
const ADMIN_NAME = "UT_Admin";
const ADMIN_HASHED_PW = '$2y$10$tUB5R1MGgbO.zD//WArnceTY8IgnFkVVsudIdHBxIrEXJ2z3WBvcK';
const ADMIN_ARCHIVE = NULL;
const ADMIN_CREATION_DATE = "2020-07-09 08:11:05";


// Trainers
const TRAINER_DEV_ID = 101;
const TRAINER_DEV_NAME = "UT_FormateurDev";
const TRAINER_DEV_HASHED_PW = '$2y$10$Q3H8WodgKonQ60SIcu.eWuVKXmxqBw1X5hMpZzwjRKyCTB1H1l.pe';
const TRAINER_DEV_ARCHIVE = NULL;
const TRAINER_DEV_CREATION_DATE = "2020-07-09 13:15:24";

const TRAINER_SYS_ID = 102;
const TRAINER_SYS_NAME = "UT_FormateurSysteme";
const TRAINER_SYS_HASHED_PW = '$2y$10$Br7mIRYfLufWkrSpi2SyB.Wz0vHZQp7dQf7f2bKy5i/CkhHomSvli';
const TRAINER_SYS_ARCHIVE = NULL;
const TRAINER_SYS_CREATION_DATE = "2020-07-09 13:15:47";

const TRAINER_OPE_ID = 103;
const TRAINER_OPE_NAME = "UT_FormateurOperateur";
const TRAINER_OPE_HASHED_PW = '$2y$10$SbMYPxqnngLjxVGlG4hW..lrc.pr5Dd74nY.KqdANtEESIvmGRpWi';
const TRAINER_OPE_ARCHIVE = NULL;
const TRAINER_OPE_CREATION_DATE = "2020-07-09 13:24:22";

// Apprentices
const APPRENTICE_DEV_ID = 104;
const APPRENTICE_DEV_NAME = "UT_ApprentiDev";
const APPRENTICE_DEV_HASHED_PW = '$2y$10$6TLaMd5ljshybxANKgIYGOjY0Xur9EgdzcEPy1bgy2b8uyWYeVoEm';
const APPRENTICE_DEV_ARCHIVE = NULL;
const APPRENTICE_DEV_CREATION_DATE = "2020-07-09 13:16:05";

const APPRENTICE_SYS_ID = 105;
const APPRENTICE_SYS_NAME = "UT_ApprentiSysteme";
const APPRENTICE_SYS_HASHED_PW = '$2y$10$0ljkGcDQpTc0RDaN7Y2XcOhS8OB0t0QIhquLv9NcR79IVO9rCR/0.';
const APPRENTICE_SYS_ARCHIVE = NULL;
const APPRENTICE_SYS_CREATION_DATE = "2020-07-09 13:16:27";

const APPRENTICE_OPE_ID = 106;
const APPRENTICE_OPE_NAME = "UT_ApprentiOperateur";
const APPRENTICE_OPE_HASHED_PW = '$2y$10$jPNxV2ZZ6Il2LiBQ.CWhNOoud6NsMRFILwHN8kpD410shWeiGpuxK';
const APPRENTICE_OPE_ARCHIVE = NULL;
const APPRENTICE_OPE_CREATION_DATE = "2020-07-09 13:24:45";

// Links between apprentice and trainer
const LINK_DEV_ID = 100;
const LINK_SYS_ID = 101;
const LINK_OPE_ID = 102;

const FAKE_LINK_ID = 999;

// Course plans
const COURSE_PLAN_OPE_ID = 4;
const COURSE_PLAN_OPE_NAME = "Opératrice en informatique / Opérateur en informatique avec CFC";

const COURSE_PLAN_SYS_ID = 5;
const COURSE_PLAN_SYS_NAME = "Informaticienne / Informaticien avec CFC, orientation exploitation et infrastructure";

const COURSE_PLAN_DEV_ID = 6;
const COURSE_PLAN_DEV_NAME = "Informaticienne / Informaticien avec CFC, orientation développement d'applications";

// user course

const USER_COURSE_DEV_ID = 101;

// grade

const APPRENTICE_DEV_ID_GRADE_ID = 101;
