# 🐳 Adam Busfy ZADANIE

## Installation

1. Clone this rep.

2. Run `docker-compose up`

3. The 3 containers are deployed: 

```
Creating symfony-docker_db_1    ... done
Creating symfony-docker_php_1   ... done
Creating symfony-docker_nginx_1 ... done
```

4. Use this value for the DATABASE_URL environment variable of Symfony:

```
DATABASE_URL=mysql://app_user:helloworld@db:3306/app_db?serverVersion=5.7
```

5. Open new terminal and run `docker-compose exec php sh` , inside run `php bin/console make:migration` and `npm run watch`

6. Run the migration with `php bin/console doctrine:migrations:migrate`

7. Try it out! : http://127.0.0.1/login