<?php
/**
 * Fichier de model pour comment
 *
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

namespace Plafor\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;
use User\Models\User_model;

class CommentModel extends Model {
    protected $table = 'comment';
    protected $primaryKey = 'id';
    protected $allowedFields = ['fk_user', 'fk_acquisition_status',
        'comment', 'date_creation'];
    protected $validationRules;
    private $acquisitionStatusModel = null;

    public function __construct(ConnectionInterface &$db = null,
        ValidationInterface $validation = null)
    {
        $this->validationRules = array(
            'comment'=>[
                'label' => 'plafor_lang.comment',
                'rules' => 'required|max_length['.config('\Plafor\Config\PlaforConfig')->SQL_TEXT_MAX_LENGTH.']',
            ]
        );
        parent::__construct($db, $validation);
    }


    /**
     * @param $fkTrainerId /the id of fk_trainer
     * @return array|null
     */
    public function getTrainer($fkTrainerId) {
        return (new User_model())->find($fkTrainerId);
    }

    /**
     * @param $fkAcquisitionStatusId
     * @return array|null
     */
    public function getAcquisitionStatus($fkAcquisitionStatusId) {
        $acquisitionStatusModel = model('AcquisitionStatusModel');
        return $acquisitionStatusModel->find($fkAcquisitionStatusId);
    }
}
?>
