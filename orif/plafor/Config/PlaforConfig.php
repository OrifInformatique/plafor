<?php
/**
 * Config for plafor module
 *
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
namespace Config;

use CodeIgniter\Config\BaseConfig;

class PlaforConfig extends BaseConfig
{
    public $FORMATION_NUMBER_MAX_LENGTH=5;
    public $OFFICIAL_NAME_MAX_LENGTH=100;
    public $SYMBOL_MAX_LENGTH=10;
    public $COMPETENCE_DOMAIN_NAME_MAX_LENGTH=100;
    public $OPERATIONAL_COMPETENCE_NAME_MAX_LENGTH=150;
    public $TAXONOMY_MAX_VALUE=6;
    public $OBJECTIVE_NAME_MAX_LENGTH=350;
    public $SQL_TEXT_MAX_LENGTH=65535;
    public $MIGRATION_PASSWORD='ys3vTFiR6gyGajz';
    public $MODULE_NUMBER_MAX_LENGTH=4;
    public $MODULE_NUMBER_MIN_LENGTH=3;
    public $MODULE_OFFICIAL_NAME_MAX_LENGTH=100;
    public $GRADE_LOWEST=0;
    public $GRADE_HIGHEST=6;
    public float $SCHOOL_WEIGHT = 0.8;
    public float $EXTERN_WEIGHT = 0.2;
}
