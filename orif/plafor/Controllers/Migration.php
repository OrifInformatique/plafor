<?php


namespace Plafor\Controllers;


use CodeIgniter\Config\Services;

class Migration extends \App\Controllers\BaseController {

    public function init()
    {
        if ($this->request->getPost('password')==config('\Plafor\Config\PlaforConfig')->MIGRATION_PASSWORD){

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
                unlink(ROOTPATH.'orif/plafor/Views/migrationindex.php');
                return $this->response->setStatusCode(200);

            }
            else{
                return $this->response->setStatusCode('400');
            }
        }else{
                return $this->response->setStatusCode('401');
        }
    }

    public function index() {
        $this->display_view("\Plafor\migrationIndex.php");
    }
}





