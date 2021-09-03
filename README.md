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

  - MySQL 5.7
  - Apache  2.4
  - PHP 7.4
  - Composer [Link download composer](https://getcomposer.org/download/)


##  Guide d'installation

  - Clonez ou téléchargez le repository GitHub dans le dossier :
  ```sh
  git clone https://github.com/pascalinecte91/projet_bileMo.git
  ```

  - Copier le fichier .env dans un autre fichier .env.local
  - creation DataBase, configurer le fichier .env.local exemple :
  ```yaml
    DATABASE_URL="mysql://root@127.0.0.1:3306/projet_bileMo?serverVersion=5.7"
  ```
  - lancez l'installation des dépendances
  ```sh
   composer install
   
  - php bin/console asset:install
  ```
## Configuration JWT
  - mkdir config/jwt
  - [Documentation du bundle JWT](https://github.com/lexik/LexikJWTAuthenticationBundle)
  ```sh
  1. openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
    - Mettre la Passphrase (fichier .env.local) JWT_PASSPHRASE 

  2. openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
    - Remmettre la Passphrase (fichier .env.local) JWT_PASSPHRASE 
  ```

## Commandes Symfony executions diverses
  ```
  1. Creation database : 
    - php bin/console doctrine:database:create
  2. Proceder à la migration : 
    - php bin/console doctrine:migrations:migrate
  3. Installer les fixtures :
    - php bin/console doctrine:fixture:load
```

## Autres commandes:
```
 - Vider cache si besoin:
    - php bin/console cache:clear
```

## Documentation

- Accès  API Documentation
  http://[votre domaine]/api/doc

## Login user (si fixtures installées)
{
    "username": "pascaline@gmail.com",
    "password" : "azerty"
}

Auteur
- pascale CHRISTOPHE  Elève OpenClassroom
   
