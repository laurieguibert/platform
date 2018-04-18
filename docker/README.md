## Run project with docker

1. Modify the parameters of the .env file

2. Build and run containers with

    ```bash
    $ docker-compose build
    $ docker-compose up -d
    ```

3. Prepare Symfony app
    1. Update app/config/parameters.yml

        ```yml
        # path/to/your/symfony-project/app/config/parameters.yml
        parameters:
            database_host: db
        ```

    2. Run symfony

        ```bash
        $ docker-compose exec php bash
        $ composer install
        $ sf3 doctrine:schema:update --force
        $ sf3 doctrine:fixtures:load --no-interaction
        $ sf3 server:run 0.0.0.0:8000
        ``

## Usage

* Symfony app: visit [localhost:8000](localhost:8000)  
* Symfony dev mode: visit [localhost:8000/app_dev.php](localhost:8000)  
* Logs (Kibana): [localhost:81](localhost:8000)
* Logs (files location): logs/nginx and logs/symfony
