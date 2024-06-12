<?php


namespace User\Database\Seeds;


class AddUserDatas extends \CodeIgniter\Database\Seeder
{
    public function run()
    {
        $data = [
            ['fk_user_type'=>1,'username'=>'admin','password'=>'$2y$10$DeYetQ9iAzLhKWU.BUesPOKSbsK4CExcDLYeqSmrGiR7O.EDjMlW2','email'=>'admin@admin.com'],
            ['fk_user_type'=>2,'username'=>'trainer','password'=>'$2y$10$a2VV.wOG76z0Vw83U8ucs.XUYTV0Im5c1Z8Pgiq1OrDvh/2qFy9vq','email'=>'trainer@trainer.com'],
        ];
        foreach($data as $row) $this->db->table('user')->insert($row);
    }
}