<?php
/**
 * Fichier de model pour trainer_apprentice
 *
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

namespace Plafor\Models;


use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Validation\ValidationInterface;
use User\Models\User_model;

class TrainerApprenticeModel extends \CodeIgniter\Model
{
    protected $table = 'trainer_apprentice';
    protected $primaryKey = 'id';
    protected $allowedFields = ['fk_trainer', 'fk_apprentice'];
    protected $validationRules;

    public function __construct(ConnectionInterface &$db = null,
        ValidationInterface $validation = null)
    {
        $this->validationRules = array(
            'fk_trainer' => [
                'label' => 'plafor_lang.field_trainer_link',
                'rules' => 'required|numeric|AreApprenticeAndTrainerNotLinked[{fk_apprentice}]'
            ],
            'fk_apprentice' => [
                'label' => 'plafor_lang.field_trainer_link',
                'rules' => 'required|numeric'
            ]
        );
        parent::__construct($db, $validation);
    }

    /**
     * @param $fkTrainerId
     * @return array
     */
    public function getTrainer($fkTrainerId) {
        $user_model = model('User_model');
        return $user_model->find($fkTrainerId);
    }


    /**
     * @param $fkApprenticeId
     * @return array
     */
    public function getApprentice($fkApprenticeId) {
        $user_model = model('User_model');
        return $user_model->find($fkApprenticeId);

    }


    /**
     * @param $fkTrainerId
     * @return array
     */
    public function getApprenticeIdsFromTrainer($fkTrainerId) {
        $trainerApprenticeModel = model('TrainerApprenticeModel');
        return $trainerApprenticeModel->where('fk_trainer', $fkTrainerId)
                                      ->findColumn('fk_apprentice');
    }


    /**
     * Get the apprentices unassigned to a trainer
     *
     * @return array
     *
     */
    public function getUnassignedApprentices()
    {
        $user_model = model('User_model');

        $unassigned_apprentices = [];
        $assigned_apprentices_list = [];

        $apprentices = $user_model->getApprentices();

        $assinged_apprentices = $this->select('fk_apprentice')->distinct()->findAll();;

        foreach($assinged_apprentices as $assinged_apprentice)
            array_push($assigned_apprentices_list, $assinged_apprentice['fk_apprentice']);

        foreach($apprentices as $apprentice)
        {
            if(!in_array($apprentice['id'], $assigned_apprentices_list))
                array_push($unassigned_apprentices, $apprentice);
        }

        return $unassigned_apprentices;
    }


    /**
     * Check if the given trainer is linked to the given apprentice
     *
     * @param int $apprentice_id
     * @param int $trainer_id
     *
     * @return bool
     */
    public function isTrainerLinkedToApprentice(int $trainer_id,
        int $apprentice_id) : bool
    {
        $link_id = $this->select('id')
            ->where('fk_trainer', $trainer_id)
            ->where('fk_apprentice', $apprentice_id)
            ->first();
        return !empty($link_id);
    }
}
