##P7 BileMo##

**Contexte**

##Créez un web service exposant une API##

***BileMo est une entreprise offrant toute une sélection de téléphones mobiles haut de gamme.***

***Vous êtes en charge du développement de la vitrine de téléphones mobiles de l’entreprise BileMo***


##ENVIRONNEMENT DE DEVELOPPEMENT##

+ Symfony 5.3.2 (env: dev, debug: true)
+ PHP 7.4.9 (cli) (built: Aug  4 2020 11:52:41) ( ZTS Visual C++ 2017 x64 )
+ Wampserver - MySQL 5.7.31
+ MariaDB - 10.4.13
+ Apache - 2.4.46
+ PHP 7.4.9

### Librairies ###

Faker v1.9.2
php bin/console lexik:jwt:generate-keypair



*téléchargez :*
Ici ce qui suit: [Lien](https://www.postman.com/downloads/)

##INSTALLATIONS##

- Clonez ou téléchargez le repository GitHub dans le dossier :
  git clone :[Lien](https://github.com/pascalinecte91/projet_bileMo.git)
- composer create-project symfony/skeleton projet_bileMo
- composer require profiler
- compser require ORM
- composer require selializer
- composer req "spomky-labs/lexik-jose-bridge:^3.0"
- composer require fzaninotto/faker

##Commandes Symfony pour installations diverses##

- *Creation de la base de donnée*
    - php bin/console doctrine:database:create
- *Migrations*
    - php bin/console make:migration
    - php bin/console doctrine:migrations migrate
- *Fixtures*
    - symfony console doctrine:fixtures:load  / ou sf cd:f:l
- *Vider cache si besoin*
    - php bin/console c:c ( cache:clear )
- *Routes du projet*
    - php bin/console debug:router
 *les principales*
  phone_index                GET      ANY      ANY    /phone
  phone_new                  POST     ANY      ANY    /phone
  show_phone                 GET      ANY      ANY    /phone/show/{id}
  phone_edit                 PUT      ANY      ANY    /phone/edit/{id}
  phone_delete               DELETE   ANY      ANY    /phone/{id}
  update_phone               PUT      ANY      ANY    /phone/{id}
 
