<?php
/**
 * Seed file for the table "teaching_module"
 *
 * @author      Orif (ViDi, ThJo)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
namespace Plafor\Database\Seeds;

use CodeIgniter\Database\Seeder;

class addTeachingModule extends Seeder
{
    public function run()
    {
        // Last Update: 2024
        $modules = [
            [ // *** Developer / Infrastructure *** (1)
                "module_number" => 106,
                "version" => 1,
                "official_name" => "Interroger, traiter et assurer la maintenance des bases de données",
            ],
            [ // *** Developer / Infrastructure ***
                "module_number" => 107,
                "version" => 1,
                "official_name" => "Mettre en œuvre des solutions ICT avec la technologie blockchain",
            ],
            [ // *** Developer / Infrastructure ***
                "module_number" => 109,
                "version" => 1,
                "official_name" => "Exploiter et surveiller des services dans le cloud public",
            ],
            [ // *** Developer / Infrastructure ***
                "module_number" => 110,
                "version" => 1,
                "official_name" => "Analyser et représenter des données avec des outils",
            ],
            [ // *** Developer / Infrastructure *** (5)
                "module_number" => 114,
                "version" => 4,
                "official_name" => "Mettre en œuvre des systèmes de codification, de compression et d’encryptage",
            ],
            [ // *** Developer / Infrastructure / Operator ***
                "module_number" => 117,
                "version" => 4,
                "official_name" => "Mettre en place l'infrastructure informatique d'une petite entreprise",
            ],
            [ // *** Developer / Infrastructure / Operator ***
                "module_number" => 122,
                "version" => 3,
                "official_name" => "Automatiser des procédures à l’aide de scripts",
            ],
            [ // *** Infrastructure / Operator ***
                "module_number" => 123,
                "version" => 2,
                "official_name" => "Activer les services d'un serveur",
            ],
            [ // *** Operator ***
                "module_number" => 126,
                "version" => 3,
                "official_name" => "Installer of the périphériques en réseau",
            ],
            [ // *** Infrastructure / Operator *** (10)
                "module_number" => 129,
                "version" => 3,
                "official_name" => "Mettre en service des composants réseaux",
            ],
            [ // *** Infrastructure ***
                "module_number" => 141,
                "version" => 4,
                "official_name" => "Installer des systèmes de bases de données",
            ],
            [ // *** Infrastructure ***
                "module_number" => 143,
                "version" => 3,
                "official_name" => "Implanter un système de sauvegarde et de restauration",
            ],
            [ // *** Infrastructure ***
                "module_number" => 145,
                "version" => 3,
                "official_name" => "Exploiter et étendre un réseau",
            ],
            [ // *** Infrastructure ***
                "module_number" => 157,
                "version" => 4,
                "official_name" => "Planifier et exécuter l’introduction d’un système informatique",
            ],
            [ // *** Infrastructure *** (15)
                "module_number" => 158,
                "version" => 3,
                "official_name" => "Planifier et exécuter la migration de logiciels",
            ],
            [ // *** Infrastructure ***
                "module_number" => 159,
                "version" => 3,
                "official_name" => "Configurer et synchroniser le service d’annuaire",
            ],
            [ // *** Developer / Infrastructure ***
                "module_number" => 162,
                "version" => 1,
                "official_name" => "Analyser et modéliser des données",
            ],
            [ // *** Developer / Infrastructure ***
                "module_number" => 164,
                "version" => 1,
                "official_name" => "Créer des bases de données et y insérer des données",
            ],
            [ // *** Developer ***
                "module_number" => 165,
                "version" => 1,
                "official_name" => "Utiliser des bases de données NoSQL",
            ],
            [ // *** Infrastructure *** (20)
                "module_number" => 169,
                "version" => 1,
                "official_name" => "Mettre à disposition des services avec des conteneurs",
            ],
            [ // *** Infrastructure ***
                "module_number" => 182,
                "version" => 3,
                "official_name" => "Implémenter la sécurité système",
            ],
            [ // *** Developer ***
                "module_number" => 183,
                "version" => 3,
                "official_name" => "Implémenter la sécurité d'une application",
            ],
            [ // *** Infrastructure ***
                "module_number" => 184,
                "version" => 4,
                "official_name" => "Implémenter la sécurité réseau",
            ],
            [ // *** Developer / Infrastructure ***
                "module_number" => 185,
                "version" => 1,
                "official_name" => "Analyser et implémenter des mesures visant à assurer la sécurité informatique des PME",
            ],
            [ // *** Developer / Infrastructure ***(25)
                "module_number" => 187,
                "version" => 1,
                "official_name" => "Mettre en service un poste de travail ICT avec le système d’exploitation",
            ],
            [ // *** Infrastructure ***
                "module_number" => 188,
                "version" => 1,
                "official_name" => "Exploiter, surveiller et assurer la maintenance des services",
            ],
            [ // *** Developer / Infrastructure ***
                "module_number" => 190,
                "version" => 1,
                "official_name" => "Mettre en place et exploiter une plateforme de virtualisation",
            ],
            [ // *** Developer / Infrastructure ***
                "module_number" => 210,
                "version" => 1,
                "official_name" => "Utiliser un cloud public pour des applications",
            ],
            [ // *** Operator ***
                "module_number" => 214,
                "version" => 3,
                "official_name" => "Instruire les utilisateurs sur le comportement avec des moyens informatiques",
            ],
            [ // *** Developer / Infrastructure *** (30)
                "module_number" => 216,
                "version" => 1,
                "official_name" => "Intégrer les terminaux IoE dans une plateforme existante",
            ],
            [ // *** Developer / Infrastructure ***
                "module_number" => 217,
                "version" => 1,
                "official_name" => "Concevoir, planifier et mettre en place un service pour l’IoE",
            ],
            [ // *** Developer / Infrastructure ***
                "module_number" => 223,
                "version" => 3,
                "official_name" => "Réaliser des applications multi-utilisateurs orientées objets",
            ],
            [ // *** Developer / Infrastructure ***
                "module_number" => 231,
                "version" => 1,
                "official_name" => "Appliquer la protection et la sécurité des données",
            ],
            [ // *** Developer / Infrastructure ***
                "module_number" => 241,
                "version" => 1,
                "official_name" => "Initialiser des solutions ICT innovantes",
            ],
            [ // *** Developer / Infrastructure *** (35)
                "module_number" => 245,
                "version" => 1,
                "official_name" => "Mettre en œuvre des solutions ICT innovantes",
            ],
            [ // *** Developer / Infrastructure ***
                "module_number" => 248,
                "version" => 1,
                "official_name" => "Réaliser des solutions ICT avec des technologies actuelles",
            ],
            [ // *** Developer / Infrastructure ***
                "module_number" => 254,
                "version" => 3,
                "official_name" => "Décrire des processus métier dans son propre environnement professionnel",
            ],
            [ // *** Developer / Infrastructure ***
                "module_number" => 259,
                "version" => 1,
                "official_name" => "Développer des solutions ICT avec le machine learning",
            ],
            [ // *** Operator ***
                "module_number" => 260,
                "version" => 1,
                "official_name" => "Mettre en pratique des outils d’Office",
            ],
            [ // *** Operator *** (40)
                "module_number" => 261,
                "version" => 1,
                "official_name" => "Garantir la fonction des terminaux utilisateurs dans la structure réseau",
            ],
            [ // *** Operator ***
                "module_number" => 262,
                "version" => 1,
                "official_name" => "Exécuter l'évaluation de moyens ICT",
            ],
            [ // *** Operator ***
                "module_number" => 263,
                "version" => 1,
                "official_name" => "Garantir la sécurité des terminaux ICT utilisateurs",
            ],
            [ // *** Developer ***
                "module_number" => 293,
                "version" => 1,
                "official_name" => "Créer et publier un site Web",
            ],
            [ // *** Developer ***
                "module_number" => 294,
                "version" => 1,
                "official_name" => "Réaliser le front-end d’une application Web interactive",
            ],
            [ // *** Developer *** (45)
                "module_number" => 295,
                "version" => 1,
                "official_name" => "Réaliser le back-end pour des applications",
            ],
            [ // *** Infrastructure ***
                "module_number" => 300,
                "version" => 4,
                "official_name" => "Intégrer des services réseau multi-plateformes",
            ],
            [ // *** Operator ***
                "module_number" => 304,
                "version" => 2,
                "official_name" => "Installer et configurer un ordinateur mono-poste",
            ],
            [ // *** Operator ***
                "module_number" => 305,
                "version" => 2,
                "official_name" => "Installer, configurer et administrer un système d’exploitation",
            ],
            [ // *** Developer / Infrastructure ***
                "module_number" => 306,
                "version" => 4,
                "official_name" => "Réaliser de petits projets dans son propre environnement professionnel",
            ],
            [ // *** Developer / Infrastructure *** (50)
                "module_number" => 319,
                "version" => 1,
                "official_name" => "Concevoir et implémenter des applications",
            ],
            [ // *** Developer ***
                "module_number" => 320,
                "version" => 1,
                "official_name" => "Programmer orienté objet",
            ],
            [ // *** Developer ***
                "module_number" => 321,
                "version" => 1,
                "official_name" => "Programmer des systèmes distribués",
            ],
            [ // *** Developer ***
                "module_number" => 322,
                "version" => 1,
                "official_name" => "Concevoir et implémenter des interfaces utilisateur",
            ],
            [ // *** Developer ***
                "module_number" => 323,
                "version" => 1,
                "official_name" => "Programmer de manière fonctionnelle",
            ],
            [ // *** Developer *** (55)
                "module_number" => 324,
                "version" => 1,
                "official_name" => "Prendre en charge des processus DevOps avec des outils logiciels",
            ],
            [ // *** Developer / Infrastructure ***
                "module_number" => 335,
                "version" => 3,
                "official_name" => "Réaliser une application pour mobile",
            ],
            [ // *** Developer / Infrastructure ***
                "module_number" => 346,
                "version" => 1,
                "official_name" => "Concevoir et réaliser des solutions cloud",
            ],
            [ // *** Developer ***
                "module_number" => 347,
                "version" => 1,
                "official_name" => "Utiliser un service avec des conteneurs",
            ],
            [ // *** Developer ***
                "module_number" => 426,
                "version" => 1,
                "official_name" => "Développer un logiciel avec des méthodes agiles",
            ],
            [ // *** Developer / Infrastructure / Operator *** (60)
                "module_number" => 431,
                "version" => 2,
                "official_name" => "Exécuter des mandats demandés autonome dans son propre environnement professionnel",
            ],
            [ // *** Operator ***
                "module_number" => 437,
                "version" => 1,
                "official_name" => "Travailler dans le support",
            ],
            [ // *** Developer ***
                "module_number" => 450,
                "version" => 1,
                "official_name" => "Tester des applications",
            ],
        ];

        foreach ($modules as $module) {
            $this->db->table('teaching_module')->insert($module);
        }
    }
}
