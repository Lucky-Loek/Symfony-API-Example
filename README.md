# Symfony API Example

[![Build Status](https://travis-ci.org/loekiedepo/Symfony-API-Example.svg?branch=master)](https://travis-ci.org/loekiedepo/Symfony-API-Example)
[![StyleCI](https://styleci.io/repos/75643731/shield?branch=master)](https://styleci.io/repos/75643731)

A small example of how an API could be written in Symfony. This project allow a user to receive/add/update/delete cars.

All output is standardized so that it is easy to parse in any language on any environment.

It now also features an implementation of [JSON Web Tokens](https://jwt.io/) for authenticating users.

## Features

- JWT Authentication
   - [GET token](#get-a-token)
- Data retrieval
   - [GET all entities](#get-all-entities)
   - [GET entity by id](#get-entity-by-id)
- Data manipulation
   - [POST new entity](#post-new-entity)
   - [PATCH update existing entity](#patch-update-existing-entity)
   - [DELETE remove existing entity](#delete-removing-existing-entity)
   
## Important!

There are two SSH keys in `/var/jwt`. These encrypt all the tokens we send and read. I have committed
them for the ease of use and testing, but remember to ALWAYS generate new ones and keep them secret
in your own applications. See the [official documentation](https://github.com/lexik/LexikJWTAuthenticationBundle/blob/master/Resources/doc/index.md#getting-started) on how to do this.

## Workflow

1. Register user (manually or through fixture. May add easier registration on request)
2. User retrieves token
3. User consumes API
4. Token times out after 10 minutes

![Workflow sequence](http://imgur.com/J514Tv1)   

## URLS

```bash
# POST retrieve a token
symfony.app/api/token

# GET car
symfony.app/car

# GET car by id
symfony.app/car/{id}

# POST new car
symfony.app/car

# PATCH update car
symfony.app/car/{id}

# DELETE remove car
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

For more information which tests are run, please refer to the `"test"` section of `composer.json`.
You can run the given tests separately, i.e. `composer behat` or `composer phpunit`

## Technical Docs

### Error messages

All error messages have the same format, so they can be easily parsed in any language:

```JSON
{
    "error": {
        "code": 401,
        "message": "Not privileged to request the resource."
    }
}
```

### GET a token

#### Request
```bash
$ curl --request POST \
    --url http://symfony.app/api/token \
    --header 'password: unsafepassword' \
    --header 'username: admin'
```

#### Response
```JSON
{
	"token": "eyJhbGciOiJSUzI1NiJ9.eyJ1c2VybmFtZSI6ImFkbWluIiwiZXhwIjoxNDg0NTgyNTc4LCJpYXQiOjE0ODQ1ODE5Nzh9.t31C2jYVHWybZ2szEFwkGEzspYFyg9BlTyolnYtznnm8eFPIZI00hZPYCPFX2Ka7-gBFb3keM_2WVhfXKvreQpaFzge2HQ1lfMgVBCCUsxoiESUo6qCkna0Vb6ttv1qLyBRAqui_ijjANaAqEgO648vnIP0BMOYkjzw9-jNJNRQ25Bv4Y7bc_LGcGJQc2wGlg5sxWqMYhHwwCncBNPpdwTj9e9WULGBv0U1Hc_8I5eCrQFrCJGeQaKnEiy1GKXdRCSqwfCqEDrbXhgkBGygUbPGAYrfU8SnrtxFRI_EN92PByo2rjpy_M5gL-Md6czN5xDSxJHmswValR-I1ga1WkqEf194erD7KJmRRXUpz1HwNDWPDm1RJfzVgj0vTlW7kCKdLqGkkvaVnPuToxLhAPnp-kfdFkprtND0J8CajdiKaYVia4DwOjK4w_lbnfLMzZp6s6o7eKQ4h7_vkZAGu_DA0f6fVOuGQc5cqef_1oMqbKKrhVWL4xMg9wovpkAm_AF-iii-cjaXejArKzZ_4sKku5fc7BleSIHH0sXXLWlE_bI6ftc3AAxTl1buIOwpqrKDwlU_YfO8d9YkuZCRG-I0B8Nu0hfW6qh3jwIaqlqaAP6ZqAfAk8Sd6cQw8eqSqjhFjtSKA2J-DYn4lP2DC-0-_6ydj8sl3pB-DV7MEVVI"
}
```

### GET all entities

#### Request

```bash
$ curl --request GET \
    --url http://symfony.app/car \
    --header 'Authorization: Bearer {token}'
```

#### Response

```JSON
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

#### Request

```bash
$ curl --request GET \
    --url http://symfony.app/car/{id} \
    --header 'Authorization: Bearer {token}'
```

#### Response
```JSON
{
    "id": 1,
    "brand": "Ford",
    "name": "Mustang",
    "year": 1972
}
```

### POST new entity

#### Request

```bash
$ curl --request POST \
    --url http://symfony.app/car \
    --header 'content-type: application/json' \
    --header 'Authorization: Bearer {token}'
    --data '{
	  "brand": "Ford",
	  "name": "Mustang",
	  "year": 1972
  }'
```

#### Response
```JSON
{
    "id": 1,
    "brand": "Ford",
    "name": "Mustang",
    "year": 1972
}
```

### PATCH update existing entity

#### Request
```bash
$ curl --request PATCH \
    --url http://symfony.app/car/{id} \
    --header 'content-type: application/json' \
    --header 'Authorization: Bearer {token} \
    --data '{
      "year": 2016
  }'
```

#### Response
```JSON
{
    "id": 1,
    "brand": "Ford",
    "name": "Mustang",
    "year": 2016
}
```

### DELETE remove existing entity

#### Request
```bash
$ curl --request DELETE \
    --url http://symfony.app/car/{id} \
    --header 'Authorization: Bearer {token}
```

#### Response
```JSON
{
    "message": "Car deleted"
}
```
