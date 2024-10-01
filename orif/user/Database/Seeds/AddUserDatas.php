<?php


namespace User\Database\Seeds;


class AddUserDatas extends \CodeIgniter\Database\Seeder
{
    public function run()
    {
        $data = [
            ['fk_user_type'=>1,'username'=>'admin','password'=>'$2y$10$DeYetQ9iAzLhKWU.BUesPOKSbsK4CExcDLYeqSmrGiR7O.EDjMlW2'],
            ['fk_user_type'=>2,'username'=>'trainer1','password'=>'$2y$10$a2VV.wOG76z0Vw83U8ucs.XUYTV0Im5c1Z8Pgiq1OrDvh/2qFy9vq','email'=>'trainer1@trainer.com'],
            ['fk_user_type'=>2,'username'=>'trainer2','password'=>'$2y$10$a2VV.wOG76z0Vw83U8ucs.XUYTV0Im5c1Z8Pgiq1OrDvh/2qFy9vq','email'=>'trainer2@trainer.com'],
            ['fk_user_type'=>3,'username'=>'app1','password'=>'$2y$10$3B5OJ6dN.gMwX94UU4E4Ce5hR4eMSRGrT21DB0Pw7Zmi4fkd5WCTO','email'=>'app1@app.com'],
            ['fk_user_type'=>3,'username'=>'app2','password'=>'$2y$10$3B5OJ6dN.gMwX94UU4E4Ce5hR4eMSRGrT21DB0Pw7Zmi4fkd5WCTO','email'=>'app2@app.com'],
        ];
        foreach($data as $row) $this->db->table('user')->insert($row);
    }
}