## Hotels

### Install

```
git clone https://github.com/a1ex7/ct-hotels.git
cd ct-hotels
cp .env.example .env
docker-compose up -d --build
docker-compose exec app composer install
```

### App init

```
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate --seed
```

### App container shell
```
docker-compose exec app bash
```

### API

Generate documentation
```
docker-compose exec app php artisan l5-swagger:generate
```
then go to
http://localhost/api/documentation

### TEST

```
docker-compose exec app php artisan test
```
or
```
docker-compose exec app ./vendor/bin/phpunit
```
