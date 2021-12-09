L application fonctionne sous la version 7.4 de php et la version 5.3 de symfony. Elle  utilise également  la base de données mysql de Apache et l'annuaire ldap pour se connecter
Le code est disponible sur la branche master.

Dans le dossier du code :
Php,Yarn,composer et symfony doivent être installés.
L'extension ldap doit avoir été activé dans le fichier php.ini .
Une fois que ces utilitaires ont été installé, rentrez les commandes suivantes via un outil de ligne de commande :

yarn upgrade

npm run dev

php composer update

php composer.phar recipes

install symfony/webpack-encore-bundle --force -v 

Afin de créer la base de données :
Sous phpMyAdmin, créez une base de donnée vide "ospdb" puis rentrer les commandes suivantes depuis une ligne de commande :

symfony console doctrine:migrations:migrate

symfony console doctrine:fixtures:load

Par défault, l'identifiant  pour se connecter à la base de données depuis le code de projet a pour valeur root tandis que le mot de passe est null.
Selon la configuration de php, ces valeurs peuvent changer. Si les identifiant sont différents, il faut changer dans le fichier .env la ligne 
DATABASE_URL=mysql://root:@127.0.0.1:3306/ospdb (DATABASE_URL=mysql://identifiant:motdepasse@127.0.0.1:3306/ospdb)

Les utilisateurs de l'application peuvent se connecter à l'application par le biais de leur identifiant et mot de passe ldap






