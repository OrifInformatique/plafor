<?php


namespace Plafor\Database\Seeds;


use CodeIgniter\Database\Seeder;

class addCompetenceDomain2021Datas extends Seeder
{
    public function run()
    {
        $competence_domains = array(
            array('id' => '21','fk_course_plan' => '5','symbol' => 'A','name' => 'Suivi des projets ICT','archive' => NULL),
            array('id' => '22','fk_course_plan' => '5','symbol' => 'B','name' => 'Assistance et conseils dans l’environnement ICT','archive' => NULL),
            array('id' => '23','fk_course_plan' => '5','symbol' => 'C','name' => 'Création et maintenance de données numériques','archive' => NULL),
            array('id' => '24','fk_course_plan' => '5','symbol' => 'D','name' => 'Fourniture et exploitation de solutions ICT','archive' => NULL),
            array('id' => '25','fk_course_plan' => '5','symbol' => 'E','name' => 'Exploitation des réseaux','archive' => NULL),
            array('id' => '26','fk_course_plan' => '5','symbol' => 'F','name' => 'Exploitation des systèmes de serveurs et de leurs services','archive' => NULL),
            array('id' => '27','fk_course_plan' => '6','symbol' => 'A','name' => 'Suivi des projets ICT','archive' => NULL),
            array('id' => '28','fk_course_plan' => '6','symbol' => 'B','name' => 'Assistance et conseils dans l’environnement ICT','archive' => NULL),
            array('id' => '29','fk_course_plan' => '6','symbol' => 'C','name' => 'Création et maintenance de données numériques','archive' => NULL),
            array('id' => '30','fk_course_plan' => '6','symbol' => 'G','name' => 'Développement d’applications','archive' => NULL),
            array('id' => '31','fk_course_plan' => '6','symbol' => 'H','name' => 'Délivrance et fonctionnement des applications','archive' => NULL),
            array('id' => '32','fk_course_plan' => '7','symbol' => 'A','name' => 'Mise en service d’appareils TIC','archive' => NULL),
            array('id' => '33','fk_course_plan' => '7','symbol' => 'B','name' => 'Mise en service de serveurs et réseaux','archive' => NULL),
            array('id' => '34','fk_course_plan' => '7','symbol' => 'C','name' => 'Garantie de l’exploitation TIC ','archive' => NULL),
            array('id' => '35','fk_course_plan' => '7','symbol' => 'D','name' => 'Assistance aux utilisateurs','archive' => NULL),
            array('id' => '36','fk_course_plan' => '7','symbol' => 'E','name' => 'Développement d’applications en tenant compte des caractéristiques de qualité','archive' => NULL),
            array('id' => '37','fk_course_plan' => '7','symbol' => 'F','name' => 'Travaux dans le cadre de projets','archive' => NULL)
        );

        foreach ($competence_domains as $competence_domain)
            $this->db->table('competence_domain')->insert($competence_domain);
    }

}