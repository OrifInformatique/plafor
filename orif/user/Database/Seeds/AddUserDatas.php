<?php


namespace User\Database\Seeds;


class AddUserDatas extends \CodeIgniter\Database\Seeder
{
    public function run()
    {
        $data = [
            ['fk_user_type'=>1,'username'=>'admin','password'=>'$2y$10$84r63xo.M4LVcIi8IvT8cO0qYxyglPshY1jJmKLedRMcaTcxhcVYO'],
            ['fk_user_type'=>2,'username'=>'trainer','password'=>'$2y$10$Y5mWEJlmsTHdDNrR8OsqAO12PvH4t/Mc.pNMFhTHFlUDvfnhq4dni','email'=>'trainer@orif.ch'],
        ];
        foreach($data as $row) $this->db->table('user')->insert($row);
    }
}