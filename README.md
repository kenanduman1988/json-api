# json-api

### Requirements
- Docker: https://docs.docker.com/install/
- Docker Compose: https://docs.docker.com/compose/install/
### Docker Containers Build
```
docker-compose build
```

### Run docker containers
```
docker-compose up -d
```

### PHP container login
```
./php.sh
chown -R www-data:www-data .
```

### Install symfony
```
composer install
```