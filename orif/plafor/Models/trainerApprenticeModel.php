<?php


namespace Plafor\Models;


use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Validation\ValidationInterface;
use User\Models\User_model;

class TrainerApprenticeModel extends \CodeIgniter\Model
{
    private static $trainerApprenticeModel=null;
    protected $table='trainer_apprentice';
    protected $primaryKey='id';
    protected $allowedFields=['fk_trainer','fk_apprentice'];
    protected $validationRules;

    public function __construct(ConnectionInterface &$db = null, ValidationInterface $validation = null)
    {
        $this->validationRules=array(
            'trainer'=>[
                'label' => 'plafor_lang.field_trainer_link',
                'rules' => 'required|numeric'
            ]);
        parent::__construct($db, $validation);
    }

    /**
     * @return TrainerApprenticeModel
     */
    public static function getInstance(){
        if (TrainerApprenticeModel::$trainerApprenticeModel==null)
            TrainerApprenticeModel::$trainerApprenticeModel=new TrainerApprenticeModel();
        return TrainerApprenticeModel::$trainerApprenticeModel;
    }

    /**
     * @param $fkTrainerId
     * @return array
     */
    public static function getTrainer($fkTrainerId){
        return User_model::getInstance()->find($fkTrainerId);
    }

    /**
     * @param $fkApprenticeId
     * @return array
     */
    public static function getApprentice($fkApprenticeId){
        return User_model::getInstance()->find($fkApprenticeId);

    }
}