# Symfony API Example

[![Build Status](https://travis-ci.org/loekiedepo/Symfony-API-Example.svg?branch=master)](https://travis-ci.org/loekiedepo/Symfony-API-Example)
[![StyleCI](https://styleci.io/repos/75643731/shield?branch=master)](https://styleci.io/repos/75643731)

A small example of how an API could be written in Symfony. This project allow a user to receive/add/update/delete cars.

All output is standardized so that it is easy to parse in any language on any environment.

## Features

- Data retrieval
   - [GET all entities](#get-all-entities)
   - [GET entity by id](#get-entity-by-id)
- Data manipulation
   - [POST new entity](#post-new-entity)
   - [PATCH update existing entity](#patch-update-existing-entity)
   - [DELETE remove existing entity](#delete-removing-existing-entity)
   

## URLS

```
// GET
symfony.app/car

// GET by id
symfony.app/car/{id}

// POST new
symfony.app/car

// PATCH update
symfony.app/car/{id}

// DELETE remove
symfony.app/car/{id}
```

## Installation

### Requirements

- PHP >= 7.1
- Composer
- MySQL >= 5.5

### Commands

1. Clone this repo
2. Run `composer install`
3. Run `php bin/console doc:mig:mig`
4. Run `php bin/console doc:fixtures:load`

### Testing

1. Run `php bin/console doc:mig:mig --env=test`
2. Run `composer test`

For more information which tests are run, please refer to the `"test"` section of `composer.json`

## Technical Docs

### GET all entities
```shell
# Request
$ curl --request GET \
    --url http://symfony.app/car
  
# Response
[
    {
        "id": 1,
        "brand": "Ford",
        "name": "Mustang",
        "year": 1972
    },
    {
        "id": 2,
        "brand": "Toyota",
        "name": "Corolla",
        "year": 1983
    },
    
]
```

### GET entity by id
```shell
# Request
$ curl --request GET \
    --url http://symfony.app/car/1
  
# Response
{
    "id": 1,
    "brand": "Ford",
    "name": "Mustang",
    "year": 1972
}
```

### POST new entity
```shell
# Request
$ curl --request POST \
    --url http://symfony.app/car \
    --header 'content-type: application/json' \
    --data '{
	  "brand": "Ford",
	  "name": "Mustang",
	  "year": 1972
  }'
  
# Response
{
    "id": 1,
    "brand": "Ford",
    "name": "Mustang",
    "year": 1972
}
```

### PATCH update existing entity
```shell
# Request
$ curl --request PATCH \
    --url http://symfony.app/car/1 \
    --header 'content-type: application/json' \
    --data '{
      "year": 2016
  }'
  
# Response
{
    "id": 1,
    "brand": "Ford",
    "name": "Mustang",
    "year": 2016
}
```

### DELETE remove existing entity
```shell
# Request
$ curl --request DELETE \
    --url http://symfony.app/car/1

# Response
{
    "message": "Car deleted"
}
```
