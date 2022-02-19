# Bobyblog

Just Another Blog. Compatible for small screen

#  Install

## Requirements

  - Sympfony 5  => [https://symfony.com/](https://symfony.com/download)
  
  - Composer => [https://getcomposer.org/](https://getcomposer.org/download/) 
  
  - Node & Npm => [https://nodejs.org/](https://nodejs.org/en/)

- Yarn (a "better" packet manager than npm) `npm install yarn -g`
- Gulp (for automation) `npm install gulp -g`

## Compile

### Source

`composer install`

`yarn install`

### Database

`php bin/console doctrine:database:create`

`php bin/console doctrine:schema:update`
 
 ### Scripts
 
`gulp js`

`gulp css`
