# Formations management

The purpose of this application is to follow the formation of one or more apprentices.
Different formations are available, all of them currently in IT.

This application is developed in french and not (yet ?) translated in other languages. However, CodeIgniter's language files are used properly and it would be easy to make your own translation.

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

### Prerequisites

Install a local PHP server, [XAMPP for example](https://www.apachefriends.org)

### Installing

1. Download [our latest release](https://github.com/OrifInformatique/plafor/releases)
2. Unzip your download in your project's directory (in your local PHP server)
3. Generate a local database, using the latest "database_structure.sql" and "user.sql" files, witch you find in the root "database" directory and the user module's "database" directory respectively.
4. Modify file application/config/config.php with your local site's URL and language (french by default)

```php
[...]

$config['base_url'] = 'http://localhost/your_project_directory/';

[...]

/*
|--------------------------------------------------------------------------
| Default Language
|--------------------------------------------------------------------------
|
| This determines which set of language files should be used. Make sure
| there is an available translation if you intend to use something other
| than english.
|
*/
$config['language'] = 'french';
[...]
```

5. Modify file application/config/database.php with the informations of your local database

```php
$db['default'] = array(
    [...]
    'hostname' => 'your_database_server',
    'username' => 'your_user',
    'password' => 'your_password',
    'database' => 'your_database_name',
    [...]
);
```

## Built With

* [CodeIgniter](https://www.codeigniter.com/) - PHP framework
* [CodeIgniter modular extensions HMVC](https://github.com/OrifInformatique/HMVC) - HMVC for CodeIgniter
* [CodeIgniter base model](https://github.com/OrifInformatique/codeigniter-base-model) - Generic model
* [Bootstrap](https://getbootstrap.com/) - To simplify views design

## Authors

* **Orif, domaine informatique** - *Initiating and following the project* - [GitHub account](https://github.com/OrifInformatique)

See also the list of [contributors](https://github.com/OrifInformatique/plafor/graphs/contributors) who participated in this project.
