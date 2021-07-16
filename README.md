## P7 BileMo   -  Description du projet

_Contexte_

## Créez un web service exposant une API

**BileMo est une entreprise offrant toute une sélection de téléphones mobiles haut de gamme.**

BileMo est une entreprise offrant toute une sélection de téléphones mobiles haut de gamme.

Vous êtes en charge du développement de la vitrine de téléphones mobiles de l’entreprise BileMo. Le business modèle de BileMo n’est pas de vendre directement ses produits sur le site web, mais de fournir à toutes les plateformes qui le souhaitent l’accès au catalogue via une API (Application Programming Interface). Il s’agit donc de vente exclusivement en B2B (business to business).

Il va falloir que vous exposiez un certain nombre d’API pour que les applications des autres plateformes web puissent effectuer des opérations.


## Besoin client

Le premier client a enfin signé un contrat de partenariat avec BileMo ! C’est le branle-bas de combat pour répondre aux besoins de ce premier client qui va permettre de mettre en place l’ensemble des API et de les éprouver tout de suite.

Après une réunion dense avec le client, il a été identifié un certain nombre d’informations. Il doit être possible de :
- se connecter (login)- users
- consulter la liste des produits BileMo 
- consulter les détails d’un produit BileMo 
- consulter la liste des utilisateurs inscrits liés à un client sur le site web 
- consulter le détail d’un utilisateur inscrit lié à un client 
- ajouter un nouvel utilisateur lié à un client 
- supprimer un utilisateur ajouté par un client.

 Seuls les clients référencés peuvent accéder aux API. Les clients de l’API doivent être authentifiés via OAuth ou JWT

### Pré Requis

- Symfony 5.3.2 (env: dev, debug: true)
- PHP 7.4.9 (cli) (built: Aug 4 2020 11:52:41) ( ZTS Visual C++ 2017 x64 )
- Wampserver - MySQL 5.7.31
- MariaDB - 10.4.13
- Apache - 2.4.46
- PHP 7.4.9
- composer 
- postman 8.7.0 (si besoin): [Lien](https://www.postman.com/downloads/)

### Librairies
Faker v1.9.2
JWT Authenticator

###  GUIDE D'INSTALLATION 
( à supposer que votre environnement soit configuré avec env.dev)

- Clonez ou téléchargez le repository GitHub dans le dossier :
  git clone :(https://github.com/pascalinecte91/projet_bileMo.git)

- creation DB - configurer le fichier .env exemple :
  DATABASE_URL="mysql://root@127.0.0.1:3306/projet_bileMo?serverVersion=5.7"
- composer install


## Configuration JWT
 - composer require lexik/jwt-authentication-bundle
 - mkdir config/jwt

 1. openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
  - Mettre la Passphrase (fichier .env) JWT_PASSPHRASE 

 2. openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
  - Remmettre la Passphrase (fichier .env) JWT_PASSPHRASE :

### Commandes Symfony executions diverses
 - php bin/console doctrine:database:create
 - php bin/console doctrine:migrations:migrate
 - symfony console doctrine:fixture:load
 - symfony server

 
- _Vider cache si besoin_
  - php bin/console c:c ( cache:clear )

- _Routes du projet_
  - php bin/console debug:router

## DOCUMENTATION
NelmioDocApiBundle

## Login user
{
    "username": "pascaline@gmail.com",
    "password" : "azerty"
}

A finir

Auteur
- pascale CHRISTOPHE  Elève OpenClassroom
   
