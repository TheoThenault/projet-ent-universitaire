# Projet Ent Universitaire

## Getting started
You just imported the project and you want to run it on your machine?

Do this:
- `cd src/`
- `composer install`

To run the server :
- `cd src/`
- `symfony serve` or `symfony serve -d`

## Interact with BDD
### To do migrations :

- `symfony console doctrine:migrations:diff`
- `symfony console doctrine:migrations:migrate`

### To populate the BDD with fixtures
- `symfony console doctrine:fixtures:load`