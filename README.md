# Plafor - Follow an apprenticeship plan

This application is designed to follow apprenticeship plans in the context of the swiss computer sciences apprenticeships (CFC).

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

### Prerequisites

This project is developed on an AMP server with PHP 8.1 and MySQL 8.0.
It is based on the CodeIgniter 4 framework.
- PHP 8.1 or newer
- Composer
- An empty database

### Installing

After cloning this git repository, make a copy of the `env_dist` file and rename it to `.env`. Adjust the content of this file to match your development environment.

Get the required packages with a composer command :

```shell
composer install
```

Generate the database structure with spark :

```shell
php spark migrate --all
```

You should be able to run the application and login with :
- admin
	- `admin1234`
- trainer1
	- `trainer1234`
- trainer2
	- `trainer1234`
- app1
	- `app1234`
- app2
	- `app1234`

## Running the tests

We provide a partial PHPUnit test set wich can be run with this command :

```shell
vendor/bin/phpunit
```

## Deployment

Use the last release to deploy the project and follow the same steps on your production environment as described for a development environment.
## Built With

* [CodeIgniter 4](https://www.codeigniter.com/) - PHP framework
* [Bootstrap](https://getbootstrap.com/) - Design library with personalized css
* [ReactJS v17.0.2](https://fr.reactjs.org/) - Design Library to add simple interactivity

## Authors

* **Orif, domaine informatique** - *Initiating and following the project* - [GitHub account](https://github.com/OrifInformatique)

See also the list of [contributors](https://github.com/OrifInformatique/plafor/contributors) who participated in this project.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details
