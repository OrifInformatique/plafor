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
        // Modules
        $modules = [
            // 1xx
            [
                'module_number' => 100,
                'official_name' => "Distinguer, préparer et évaluer des données",
                'version' => 3,
            ],
            [
                'module_number' => 101,
                'official_name' => "Réaliser et publier un site Web",
                'version' => 3,
            ],
            [
                'module_number' => 104,
                'official_name' => "Implémenter un modèle de données",
                'version' => 3,
            ],
            [
                'module_number' => 105,
                'official_name' => "Traiter une base de données avec SQL",
                'version' => 3,
            ],
            [
                'module_number' => 106,
                'official_name' => "Interroger, traiter et assurer la maintenance des bases de données",
                'version' => 1,
            ],
            [
                'module_number' => 107,
                'official_name' => "Mettre en œuvre des solutions ICT avec la technologie blockchain",
                'version' => 1,
            ],
            [
                'module_number' => 109,
                'official_name' => "Exploiter et surveiller des services dans le cloud public",
                'version' => 1,
            ],
            [
                'module_number' => 110,
                'official_name' => "Analyser et représenter des données avec des outils",
                'version' => 1,
            ],
            [
                'module_number' => 114,
                'official_name' => "Mettre en œuvre des systèmes de codification, de compression et d'encryptage",
                'version' => 3,
            ],
            [
                'module_number' => 114,
                'official_name' => "Mettre en œuvre des systèmes de codification, de compression et d'encryptage",
                'version' => 4,
            ],
            [
                'module_number' => 115,
                'official_name' => "Mettre en oeuvre des équipements multimédias", // Yes, it's written "oeuvre", not "œuvre"
                'version' => 2,
            ],
            [
                'module_number' => 117,
                'official_name' => "Mettre en place l'infrastructure informatique d'une petite entreprise",
                'version' => 3,
            ],
            [
                'module_number' => 117,
                'official_name' => "Mettre en place l'infrastructure informatique et réseau d'une petite entreprise",
                'version' => 4,
            ],
            [
                'module_number' => 120,
                'official_name' => "Implémenter les interfaces graphiques d'applications",
                'version' => 3,
            ],
            [
                'module_number' => 121,
                'official_name' => "Elaborer des tâches de pilotage",
                'version' => 3,
            ],
            [
                'module_number' => 122,
                'official_name' => "Automatiser des procédures à l'aide de scripts",
                'version' => 3,
            ],
            [
                'module_number' => 123,
                'official_name' => "Activer les services d'un serveur",
                'version' => 2,
            ],
            [
                'module_number' => 124,
                'official_name' => "Etendre ou modifier une place de travail avec ordinateur",
                'version' => 3,
            ],
            [
                'module_number' => 126,
                'official_name' => "Installer des périphériques en réseau",
                'version' => 3,
            ],
            [
                'module_number' => 127,
                'official_name' => "Assurer l'exploitation de serveurs",
                'version' => 3,
            ],
            [
                'module_number' => 129,
                'official_name' => "Mettre en services des composants réseaux",
                'version' => 3,
            ],
            [
                'module_number' => 130,
                'official_name' => "Contrôler un réseau et mesurer ses flux",
                'version' => 3,
            ],
            [
                'module_number' => 133,
                'official_name' => "Réaliser des applications Web en Session-Handling",
                'version' => 3,
            ],
            [
                'module_number' => 138,
                'official_name' => "Planifier et installer des places de travail informatique",
                'version' => 1,
            ],
            [
                'module_number' => 140,
                'official_name' => "Administrer et exploiter des bases de données",
                'version' => 2,
            ],
            [
                'module_number' => 141,
                'official_name' => "Installer des systèmes de bases de données",
                'version' => 3,
            ],
            [
                'module_number' => 141,
                'official_name' => "Installer des systèmes de bases de données",
                'version' => 4,
            ],
            [
                'module_number' => 143,
                'official_name' => "Implanter un système de sauvegarde et de restauration",
                'version' => 3,
            ],
            [
                'module_number' => 145,
                'official_name' => "Exploiter et étendre un réseau",
                'version' => 3,
            ],
            [
                'module_number' => 146,
                'official_name' => "Relier une entreprise à Internet",
                'version' => 3,
            ],
            [
                'module_number' => 150,
                'official_name' => "Adapter une application de commerce électronique",
                'version' => 3,
            ],
            [
                'module_number' => 151,
                'official_name' => "Intégrer des bases de données dans des applications web",
                'version' => 3,
            ],
            [
                'module_number' => 152,
                'official_name' => "Intégrer des contenus multimédias dans des applications Web",
                'version' => 3,
            ],
            [
                'module_number' => 153,
                'official_name' => "Développer les modèles de données",
                'version' => 3,
            ],
            [
                'module_number' => 154,
                'official_name' => "Organiser la mise en exploitation d'applications",
                'version' => 2,
            ],
            [
                'module_number' => 155,
                'official_name' => "Développer des procédures en temps réel",
                'version' => 2,
            ],
            [
                'module_number' => 156,
                'official_name' => "Développer de nouveaux services, planifier leur introduction",
                'version' => 2,
            ],
            [
                'module_number' => 157,
                'official_name' => "Planifier et exécuter l'introduction d'un système informatique",
                'version' => 4,
            ],
            [
                'module_number' => 158,
                'official_name' => "Planifier et exécuter la migration de logiciels",
                'version' => 3,
            ],
            [
                'module_number' => 159,
                'official_name' => "Configurer et synchroniser le service d'annulaire",
                'version' => 3,
            ],
            [
                'module_number' => 162,
                'official_name' => "Analyser et modéliser des données",
                'version' => 1,
            ],
            [
                'module_number' => 164,
                'official_name' => "Créer des bases de données et y insérer des données",
                'version' => 1,
            ],
            [
                'module_number' => 165,
                'official_name' => "Utiliser des bases de données NoSQL",
                'version' => 1,
            ],
            [
                'module_number' => 182,
                'official_name' => "Implémenter la sécurité système",
                'version' => 3,
            ],
            [
                'module_number' => 183,
                'official_name' => "Implémenter la sécurité d'une application",
                'version' => 3,
            ],
            [
                'module_number' => 184,
                'official_name' => "Implémenter la sécurité réseau",
                'version' => 3,
            ],
            [
                'module_number' => 184,
                'official_name' => "Implémenter la sécurité réseau",
                'version' => 4,
            ],
            [
                'module_number' => 185,
                'official_name' => "Analyser et implémenter des mesures visant à assuré la sécurité informatique des PME",
                'version' => 1,
            ],
            [
                'module_number' => 187,
                'official_name' => "Mettre en service un post de travail ICT avec le système d'exploitation",
                'version' => 1,
            ],
            [
                'module_number' => 190,
                'official_name' => "Mettre en place et exploiter une plateforme de virtualisation",
                'version' => 1,
            ],
            // 2xx
            [
                'module_number' => 210,
                'official_name' => "Utiliser un cloud public pour des applications",
                'version' => 1,
            ],
            [
                'module_number' => 213,
                'official_name' => "Développer l'esprit d'équipe",
                'version' => 3,
            ],
            [
                'module_number' => 214,
                'official_name' => "Instruire les utilisateurs sur le comportement avec des moyens informatiques",
                'version' => 3,
            ],
            [
                'module_number' => 216,
                'official_name' => "Intégrer les terminaux IoE dans une plateforme existante",
                'version' => 1,
            ],
            [
                'module_number' => 217,
                'official_name' => "Concevoir, planifier et mettre en place un service pour l'IoE",
                'version' => 1,
            ],
            [
                'module_number' => 223,
                'official_name' => "Réaliser des applications multi-utilisateurs orientées objets",
                'version' => 3,
            ],
            [
                'module_number' => '226A',
                'official_name' => "Implémenter (sans hérédité) sur la base des classes",
                'version' => 4,
            ],
            [
                'module_number' => '226B',
                'official_name' => "Implémenter orienté objets (avec hérédité)",
                'version' => 4,
            ],
            [
                'module_number' => 231,
                'official_name' => "Appliquer la protection et la sécurité des données",
                'version' => 1,
            ],
            [
                'module_number' => 239,
                'official_name' => "Mettre en service un serveur web",
                'version' => 3,
            ],
            [
                'module_number' => 241,
                'official_name' => "Initialiser des solutions ICT innovantes",
                'version' => 1,
            ],
            [
                'module_number' => 242,
                'official_name' => "Réaliser des applications pour microprocesseurs",
                'version' => 3,
            ],
            [
                'module_number' => 245,
                'official_name' => "Mettre en œuvre des solutions ICT innovantes",
                'version' => 1,
            ],
            [
                'module_number' => 248,
                'official_name' => "Réaliser des solutions ICT avec des technologies actuelles",
                'version' => 1,
            ],
            [
                'module_number' => 253,
                'official_name' => "Visualiser les signaux de capteurs",
                'version' => 2,
            ],
            [
                'module_number' => 254,
                'official_name' => "Décrire les processus métier dans votre propre environnement professionnel",
                'version' => 3,
            ],
            [
                'module_number' => 256,
                'official_name' => "Réaliser la partie cliente des applications Web",
                'version' => 2,
            ],
            [
                'module_number' => 259,
                'official_name' => "Développer des solutions ICT avec le machine learning",
                'version' => 1,
            ],
            [
                'module_number' => 260,
                'official_name' => "Mettre en pratique des outils bureautiques",
                'version' => 1,
            ],
            [
                'module_number' => 261,
                'official_name' => "Garantir la fonction des terminaux utilisateurs dans la structure réseau",
                'version' => 1,
            ],
            [
                'module_number' => 262,
                'official_name' => "Exécuter l'évaluation de moyens ICT",
                'version' => 1,
            ],
            [
                'module_number' => 263,
                'official_name' => "Garantir la sécurité des terminaux ICT utilisateurs",
                'version' => 1,
            ],
            [
                'module_number' => 293,
                'official_name' => "Créer et publier un site Web",
                'version' => 1,
            ],
            [
                'module_number' => 294,
                'official_name' => "Réaliser le front-end d'une application Web interactive",
                'version' => 1,
            ],
            [
                'module_number' => 295,
                'official_name' => "Réaliser le back-end pour des applications",
                'version' => 1,
            ],
            // 3xx
            [
                'module_number' => 300,
                'official_name' => "Intégrer des services réseau multi-plate-formes",
                'version' => 3,
            ],
            [
                'module_number' => 300,
                'official_name' => "Intégrer des services réseau multi-plateformes",
                'version' => 4,
            ],
            [
                'module_number' => 301,
                'official_name' => "Appliquer les outils bureautiques",
                'version' => 1,
            ],
            [
                'module_number' => 302,
                'official_name' => "Utiliser les fonctions avancées d'Office",
                'version' => 3,
            ],
            [
                'module_number' => 304,
                'official_name' => "Installer et configurer un ordinateur mono-poste",
                'version' => 2,
            ],
            [
                'module_number' => 305,
                'official_name' => "Installer, configurer et administrer un système d'exploitation",
                'version' => 2,
            ],
            [
                'module_number' => 306,
                'official_name' => "Réaliser de petits projets dans son propre environnement professionnel",
                'version' => 4,
            ],
            [
                'module_number' => 307,
                'official_name' => "Réaliser des pages Web interactives",
                'version' => 3,
            ],
            [
                'module_number' => 318,
                'official_name' => "Analyser et programmer orienté objet avec des composants",
                'version' => 3,
            ],
            [
                'module_number' => 319,
                'official_name' => "Concevoir et implémenter des applications",
                'version' => 1,
            ],
            [
                'module_number' => 320,
                'official_name' => "Programmer orienté objet",
                'version' => 1,
            ],
            [
                'module_number' => 321,
                'official_name' => "Programmer des systèmes distribués",
                'version' => 1,
            ],
            [
                'module_number' => 322,
                'official_name' => "Concevoir et implémenter des interfaces utilisateur",
                'version' => 1,
            ],
            [
                'module_number' => 323,
                'official_name' => "Programmer de manière fonctionnelle",
                'version' => 1,
            ],
            [
                'module_number' => 324,
                'official_name' => "Prendre en charge des processus DevOps avec des outils logiciels",
                'version' => 1,
            ],
            [
                'module_number' => 326,
                'official_name' => "Développer et implémenter orienté objets",
                'version' => 3,
            ],
            [
                'module_number' => 330,
                'official_name' => "Mettre en service un système de téléphonie IP",
                'version' => 3,
            ],
            [
                'module_number' => 335,
                'official_name' => "Réaliser une application pour mobile",
                'version' => 3,
            ],
            [
                'module_number' => 340,
                'official_name' => "Virtualiser une infrastructure informatique",
                'version' => 3,
            ],
            [
                'module_number' => 346,
                'official_name' => "Concevoir et réaliser des solutions cloud",
                'version' => 1,
            ],
            [
                'module_number' => 347,
                'official_name' => "Utiliser un service avec des conteneurs",
                'version' => 1,
            ],
            // 4xx
            [
                'module_number' => 403,
                'official_name' => "Implémenter de manière procédurale des déroulements de programme",
                'version' => 1,
            ],
            [
                'module_number' => 404,
                'official_name' => "Programmer orienté objets selon directives",
                'version' => 1,
            ],
            [
                'module_number' => 411,
                'official_name' => "Développer et appliquer des structures de données et algorithmes",
                'version' => 1,
            ],
            [
                'module_number' => 426,
                'official_name' => "Développer un logiciel avec des méthodes agiles",
                'version' => 1,
            ],
            [
                'module_number' => 431,
                'official_name' => "Exécuter des mandats de manière autonome dans son propre environnement professionnel",
                'version' => 2,
            ],
            [
                'module_number' => 437,
                'official_name' => "Travailler dans le support",
                'version' => 1,
            ],
            [
                'module_number' => 450,
                'official_name' => "Tester des applications",
                'version' => 1,
            ],
        ];

        foreach ($modules as $module) {
            $this->db->table('teaching_module')->insert($module);
        }
    }
}
