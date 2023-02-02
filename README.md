# Projet Ent Universitaire

## Getting started
You just imported the project and you want to run it on your machine?

Be sure to have PHP 8+ and execute : 

`symfony check:requirements`

Do this:
- `cd src/`
- `composer install`

To run the server :
- `cd src/`
- `symfony serve` or `symfony serve -d`

## Interact with BDD
### To apply migrations : 
- `symfony console doctrine:migrations:migrate`
### To make migrations :
- `symfony console doctrine:migrations:diff`

### To populate the BDD with fixtures
- `symfony console doctrine:fixtures:load`