<?php
/**
 * Controller pour faire la migration des données
 * Effacé après initialisation
 * Required level all pour la migration mot de passe
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

namespace Plafor\Controllers;


use CodeIgniter\Config\Services;

class Migration extends \CodeIgniter\Controller
{
    public function index()
    {
        echo view("Plafor\migrationIndex");
    }

    public function init()
    {
        if ($this->request->getPost('password') === 'ys3vTFiR6gyGajz') {

            $file = fopen(WRITEPATH . 'appStatus.json', 'r+');
            $initDatas = fread($file, 100);

            if ((json_decode($initDatas, true))['initialized'] === false) {
                $this->response->setStatusCode('201')->send();
                $migrate = Services::migrations();
                try {
                    $migrate->setNamespace('User');
                    $migrate->latest();
                    $migrate->setNamespace('Plafor');
                    $migrate->latest();
                } catch (\Exception $e) {
                    echo $e->getMessage();
                }
                fclose($file);
                fwrite(fopen(WRITEPATH . 'appStatus.json', 'w+'), json_encode(['initialized' => true]));
                unlink((new \ReflectionClass('\Plafor\Controllers\Migration'))->getFileName());
                unlink(ROOTPATH . 'orif/plafor/Views/migrationindex.php');
                return $this->response->setStatusCode(200);

            } else {
                return $this->response->setStatusCode('400');
            }
        } else {
            return $this->response->setStatusCode('401');
        }

    }
}






