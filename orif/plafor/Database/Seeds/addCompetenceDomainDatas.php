<?php


namespace Plafor\Database\Seeds;


use CodeIgniter\Database\Seeder;

class addCompetenceDomainDatas extends Seeder
{
    public function run()
    {
        //Competence domain//
        $competence_domain = array(
            array('id' => '1','fk_course_plan' => '1','symbol' => 'A','name' => 'Saisie, interprétation et mise en œuvre des exigences des applications','archive' => NULL),
            array('id' => '2','fk_course_plan' => '1','symbol' => 'B','name' => 'Développement d’applications en tenant compte des caractéristiques de qualité','archive' => NULL),
            array('id' => '3','fk_course_plan' => '1','symbol' => 'C','name' => 'Création et maintenance de données ainsi que de leurs structures ','archive' => NULL),
            array('id' => '4','fk_course_plan' => '1','symbol' => 'D','name' => ' Mise en service d’appareils TIC','archive' => NULL),
            array('id' => '5','fk_course_plan' => '1','symbol' => 'E','name' => 'Travail sur des projets','archive' => NULL),
            array('id' => '6','fk_course_plan' => '2','symbol' => 'A','name' => 'Mise en service d’appareils TIC','archive' => NULL),
            array('id' => '7','fk_course_plan' => '2','symbol' => 'B','name' => 'Mise en service de serveurs et réseaux','archive' => NULL),
            array('id' => '8','fk_course_plan' => '2','symbol' => 'C','name' => 'Garantie de l’exploitation TIC ','archive' => NULL),
            array('id' => '9','fk_course_plan' => '2','symbol' => 'D','name' => 'Assistance aux utilisateurs','archive' => NULL),
            array('id' => '10','fk_course_plan' => '2','symbol' => 'E','name' => 'Développement d’applications en tenant compte des caractéristiques de qualité','archive' => NULL),
            array('id' => '11','fk_course_plan' => '2','symbol' => 'F','name' => 'Travaux dans le cadre de projets','archive' => NULL),
            array('id' => '12','fk_course_plan' => '3','symbol' => 'A','name' => 'Mise en service d’appareils TIC','archive' => NULL),
            array('id' => '13','fk_course_plan' => '3','symbol' => 'B','name' => 'Planification, installation, et configuration des réseaux','archive' => NULL),
            array('id' => '14','fk_course_plan' => '3','symbol' => 'C','name' => 'Planification, installation, et configuration des serveurs','archive' => NULL),
            array('id' => '15','fk_course_plan' => '3','symbol' => 'D','name' => 'Maintenance de réseaux et serveurs','archive' => NULL),
            array('id' => '16','fk_course_plan' => '3','symbol' => 'E','name' => 'Travail sur des projets','archive' => NULL),
            array('id' => '17','fk_course_plan' => '4','symbol' => 'A','name' => 'Installation, mise en service et maintenance de terminaux ICT utilisateurs','archive' => NULL),
            array('id' => '18','fk_course_plan' => '4','symbol' => 'B','name' => 'Garantie du bon fonctionnement de l’exploitation de terminaux ICT utilisateurs en réseau','archive' => NULL),
            array('id' => '19','fk_course_plan' => '4','symbol' => 'C','name' => 'Soutien des utilisateurs dans la mise en œuvre des moyens ICT','archive' => NULL),
            array('id' => '20','fk_course_plan' => '4','symbol' => 'D','name' => 'Déroulement de travaux de support ICT','archive' => NULL)
        );

        foreach ($competence_domain as $competence_domain)
        $this->db->table('competence_domain')->insert($competence_domain);
    }

}