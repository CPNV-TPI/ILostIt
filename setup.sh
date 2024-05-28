# !/bin/bash
# Updates the system
sudo apt update && sudo apt upgrade;

# Installs required packages to be able to install php8.3
sudo apt install ca-certificates apt-transport-https software-properties-common lsb-release -y;
curl -sSL https://packages.sury.org/php/README.txt | sudo bash -x;

# Updates the lists
sudo apt update;

# Installs all required packages for this project
sudo apt install apache2 php8.3 php8.3-mysql php8.3-zip unzip git mariadb-server -y;

# Restarts apache2 after php8.3 installed
sudo systemctl restart apache2;

# Installs composer
wget -q -O composer-setup.php https://getcomposer.org/installer;
php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;";
php composer-setup.php;
php -r "unlink('composer-setup.php');";

# Moves composer file to a PATH directory
sudo mv composer.phar /usr/local/bin/composer;

# Deletes the current directory html and its content
sudo rm -rf /var/www/html;

# Gets the latest version of the project
git clone "https://github.com/CPNV-TPI/ILostIt.git";

# Moves the clone above to /var/www/html
sudo mv ~/ILostIt /var/www/html;
cd /var/www/html;

# Won't be here if it was real deployment as it should only get from main
git switch release/1.0.0;

# Installs the required dependencies
composer install --no-dev;

# Automatic configuration of MariaDB
echo "To configure the database, we will need to change the root password for security reasons but also create a new user that will be assigned to the I Lost It database."
echo "You will now be asked for a few information :"
echo

read -s -p "Desired root password : " rootPassword
echo
read -p "Username (new user): " newUserUsername
read -s -p "Password (new user): " newUserPassword
echo

# Deletes the anonymous user
sudo mysql -u root --execute "DELETE FROM mysql.user WHERE User='';";
# Deletes the test databases
sudo mysql -u root --execute "DROP DATABASE IF EXISTS test;";
sudo mysql -u root --execute "DELETE FROM mysql.db WHERE Db='test' OR Db='test_%';";
# Creates the new user with its password
sudo mysql -u root --execute "CREATE USER '$newUserUsername'@'localhost' IDENTIFIED BY '$newUserPassword';";
# Executes the script to create the database
sudo mysql -u root --execute "source /var/www/html/database/DDS_TPI_ILostIt_Db.sql";
# Grants all rights to new user on ilostit database
sudo mysql -u root --execute "GRANT ALL ON ilostit.* TO '$newUserUsername'@'localhost' WITH GRANT OPTION;";
# Changes the root password
sudo mysql -u root --execute "ALTER USER 'root'@'localhost' IDENTIFIED BY '$rootPassword';";

# Enables rewrite and actions Apache modules
sudo a2enmod rewrite;
sudo a2enmod actions;

# Writes the .htaccess file for this project
(echo "RewriteEngine On"; echo "RewriteCond %{REQUEST_FILENAME} !-f"; echo "RewriteCond %{REQUEST_FILENAME} !-d"; echo "RewriteRule ^ index.php [QSA,L]") > /var/www/html/.htaccess;

# Restarts the Apache server
sudo systemctl restart apache2;