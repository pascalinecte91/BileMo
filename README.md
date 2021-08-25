## P7 BileMo   -  Description du projet

_Contexte_

## Créez un web service exposant une API
**BileMo est une entreprise offrant toute une sélection de téléphones mobiles haut de gamme.**

Vous êtes en charge du développement de la vitrine de téléphones mobiles de l’entreprise BileMo. Le business modèle de BileMo n’est pas de vendre directement ses produits sur le site web, mais de fournir à toutes les plateformes qui le souhaitent l’accès au catalogue via une API (Application Programming Interface). Il s’agit donc de vente exclusivement en B2B (business to business).

Il va falloir que vous exposiez un certain nombre d’API pour que les applications des autres plateformes web puissent effectuer des opérations.


## Besoin client

Le premier client a enfin signé un contrat de partenariat avec BileMo ! C’est le branle-bas de combat pour répondre aux besoins de ce premier client qui va permettre de mettre en place l’ensemble des API et de les éprouver tout de suite.

Après une réunion dense avec le client, il a été identifié un certain nombre d’informations. Il doit être possible de :

Seuls les clients référencés peuvent accéder aux API. Les clients de l’API doivent être authentifiés via OAuth ou JWT.

  1. consulter la liste des produits BileMo 
  2. consulter les détails d’un produit BileMo 
  3. consulter la liste des utilisateurs inscrits liés à un client sur le site web 
  4. consulter le détail d’un utilisateur inscrit lié à un client 
  5. ajouter un nouvel utilisateur lié à un client 
  6. supprimer un utilisateur ajouté par un client.

 Seuls les clients référencés peuvent accéder aux API. Les clients de l’API doivent être authentifiés via OAuth ou JWT

### Pré Requis

  - Symfony 5.3.3 (env: dev)
  - PhpMyAdmin 5.1.1
  - Wampserver - MySQL 5.7.31
  - MariaDB - 10.4.13
  - Apache - 2.4.46
  - PHP 7.4.9
  - composer [Link download composer](https://getcomposer.org/download/)
  - postman 8.7.0 [Link download postman](https://www.postman.com/downloads/)
  - info symfony [Link doctrine 5.3 version](https://symfony.com/doc/current/doctrine.html)

### Librairies
* Faker v1.9.2
* JWT Authenticator

###  GUIDE D'INSTALLATION 
( à supposer que votre environnement soit configuré avec env.dev)

  - Clonez ou téléchargez le repository GitHub dans le dossier :
    git clone : [lien vers dossier à cloner](https://github.com/pascalinecte91/projet_bileMo.git)

  - creation DB - configurer le fichier .env exemple :
    DATABASE_URL="mysql://root@127.0.0.1:3306/projet_bileMo?serverVersion=5.7"
  - composer install


## Configuration JWT
  - composer require lexik/jwt-authentication-bundle
  - mkdir config/jwt
  - [Documentation du bundle JWT](https://github.com/lexik/LexikJWTAuthenticationBundle)

  1. openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
    - Mettre la Passphrase (fichier .env) JWT_PASSPHRASE 

  2. openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
    - Remmettre la Passphrase (fichier .env) JWT_PASSPHRASE 


## Commandes Symfony executions diverses
  1. Creation database : 
  * php bin/console doctrine:database:create
  2. Proceder à la migration : 
  * php bin/console make:migration
  * php bin/console doctrine:migrations:migrate
  3. Installer les fixtures :
  * php bin/console doctrine:fixture:load


## Autres commandes:

- Démarrer serveur : 
    - symfony server:start
 - Vider cache si besoin ( utile pour les groupes):
    - php bin/console c:c ( cache:clear )
- Consulter chaque route du projet:
    - php bin/console debug:router

## DOCUMENTATION
NelmioDocApiBundle
- Accès  API Documentation
  [localhost acces Doc](http://localhost:8000/api/doc)

## Login user
{
    "username": "pascaline@gmail.com",
    "password" : "azerty"
}

Auteur
- pascale CHRISTOPHE  Elève OpenClassroom
   
