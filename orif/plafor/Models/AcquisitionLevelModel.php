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

class AcquisitionLevelModel extends Model {
    protected $table = 'acquisition_level';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name'];

    /**
     * @param $acquisitionLevelId /the id of acquisition_level
     * @return array|null
     */
    public function getAcquisitionStatus($acquisitionLevelId) {
        $acquisitionStatusModel = model('AcquisitionStatusModel');
        return $acquisitionStatusModel
            ->where('fk_acquisition_level', $acquisitionLevelId)
            ->findAll();
    }
}
?>
