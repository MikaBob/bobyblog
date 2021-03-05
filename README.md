# Bobyblog

Just Another Blog. Mobile compatible

#  Install

## Requirements

  - Sympfony 5  => [https://symfony.com/](https://symfony.com/download)
  
  - Composer => [https://getcomposer.org/](https://getcomposer.org/download/) 
  
  - Node & Npm => [https://nodejs.org/](https://nodejs.org/en/)

- Yarn (a "better" packet maanger than npm) `npm install yarn -g`
- Gulp (for automation) `npm install gulp -g`

## Compile

### Source

`composer install`

`yarn install`

### Database

`php app/console doctrine:database:create`

`php app/console doctrine:schema:update`
 
 ### Scripts
 
`gulp js`

`gulp css`
