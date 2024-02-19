<?php
/**
 * Fichier de model pour acquisition_level
 *
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

namespace Plafor\Models;
use CodeIgniter\Model;
use phpDocumentor\Reflection\Types\Void_;

class AcquisitionLevelModel extends Model{
    private static $acquisitionLevelModel;
    protected $table='acquisition_level';
    protected $primaryKey='id';
    protected $allowedFields=['name'];

    /**
     * @param $acquisitionLevelId /the id of acquisition_level
     * @return array|null
     */
    public static function getAcquisitionStatus($acquisitionLevelId){
        return AcquisitionStatusModel::getInstance()->where('fk_acquisition_level',$acquisitionLevelId)->findAll();
    }

    /**
     * @return AcquisitionLevelModel
     */
    public static function getInstance(){
        if (AcquisitionLevelModel::$acquisitionLevelModel==null)
            AcquisitionLevelModel::$acquisitionLevelModel=new AcquisitionLevelModel();
        return AcquisitionLevelModel::$acquisitionLevelModel;
    }

}






?>