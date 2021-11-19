L application fonctionne sous la version 8 de php et symfony (version 5.3) et utilise la base de données mysql de Apache. (branche master)

Dans le dossier du code :
Yarn,composer et symfony doivent être installés.

yarn upgrade
npm run dev
php composer update
php composer.phar recipes:install symfony/webpack-encore-bundle --force -v 

Afin de créer la base de données :
Sous phpMyAdmin, créer une base de donnée vide "ospdb" puis rentrer les commandes suivantes depuis le fichier de code :
symfony console doctrine:migrations:migrate
symfony console doctrine:fixtures:load

Par défault, l'identifiant  pour se connecter à la base de données depuis le code de projet a pour valeur root tandis que le mot de passe est null.
Selon la configuration de php, ces valeurs peuvent changer. Si les identifiant sont différents, il faut changer dans le fichier .env la ligne 
DATABASE_URL=mysql://root:@127.0.0.1:3306/ospdb (DATABASE_URL=mysql://identifiant:motdepasse@127.0.0.1:3306/ospdb)

Afin de se connecter à l'application ont été créer : 
Chef de projet:
john.kind@gmail.coms
Mot de passe : Test1234
Manager :
marcel.abc@gmail.coms
Mot de passe : Test1234






