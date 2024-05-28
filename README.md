# I LOST IT

## Description
This project is a web app designed to help students and teachers from the CPNV (school in Switzerland) finding their lost objects.

## Getting started

### Prerequesites

- MariaDB v10.11.7 (LTS)
- PhpStorm 2024.1.1
- HeidiSQL (or any other DB editor that accepts MariaDB)
- PHP v8.3.6
- Composer v2.7.3

### Configuration

#### 1 - Environment variables
For this project to work, you'll need to have a .env file, named exactly ".env.local", in Model folder.

````dotenv
HOSTNAME="localhost" # For example (/!\ Without http:// or https://)

DB_HOST="HOSTNAME"
DB_PORT="" # Generally 3306
DB_NAME=""
DB_USER=""
DB_PASSWORD="THE_USER_PASSWORD"

MAIL_SMTP_HOST="HOSTNAME"
MAIL_SMTP_PORT="" # Generally 465
MAIL_USER=""
MAIL_PASSWORD="THE_USER_PASSWORD"
````

## Deployment

### On dev environment
All the tutorials below are for Windows.

#### 1. PHP
To install PHP, you can follow this tutorial : https://help.learnosity.com/hc/en-us/articles/360000757757-Environment-Setup-Guide-PHP#installing-php-on-windows-1011

#### 2. Composer
For this, you can follow this tutorial directly made by Composer team : https://getcomposer.org/doc/00-intro.md#using-the-installer

#### 3. Git
To install Git, you can follow the tutorial present on this page under "Installing on Windows" : https://git-scm.com/book/en/v2/Getting-Started-Installing-Git

#### 4. PHPStorm
1. Download PHPStorm on this following link (you'll probably need to create an account) -> https://www.jetbrains.com/fr-fr/phpstorm/
2. Make the basic installation of the program (no modifications have been made on my side)
3. Configure PHP in the settings -> https://www.jetbrains.com/help/phpstorm/configuring-local-interpreter.html

#### 5. MariaDB
1. To install MariaDB, you can follow this link : https://mariadb.com/kb/en/installing-mariadb-msi-packages-on-windows/

#### 6. HeidiSQL
1. Install HeidiSQL using this link : https://www.heidisql.com/download.php?download=installer
2. Make the basic installation, no modification is needed
3. Open the application and try connecting to the database previously installed and configured
4. Click on File > Load a SQL file... > Select the file named DDS_TPI_ILostIt_Db.sql (in database folder) > click on Run.
   <br> Right-click on the left side of the app and click on refresh, the database named ***ilostit*** should appear.

#### 7. Download Project and install packages
Now, choose a folder where you want to download this project. Open a CMD and type the following commands :

```bash
git pull https://github.com/CPNV-TPI/ILostIt.git
cd ILostIt

git flow init # All by default

php composer.phar install

php -S localhost:8080
```

After that, you can now open your IDE and work ! You are now ready !

### On integration environment

The tutorial below is made for Debian 12. Be aware that it can work on other distros, but I can't assume it will at 100%.

## Directory structure

```
└── DDS_PreTPI_ILostIt/
    ├── Controller
    ├── database           # Contains DB related scripts
    ├── docs
        ├── database       # Contains MCD - MLD
        ├── design         # Contains mockups and graphical charter
    ├── Model
    ├── src/
        ├── css
        ├── img
        └── js
    ├── tests              # Contains code tests
    └── View
```

## Collaborate

Collaboration on this project is warmly welcomed.

- If you have an issue concerning the project, please open an issue explaining with the maximum of details and with screens if possible.

- If you want to add new features, correct existing code or anything else, open a new pull request. It will be treated as soon as possible.
If your code is accepted, it will be pulled.

Make sure to explain as much as you can the news features or issues that you have !

## License

[MIT License](https://github.com/CPNV-TPI/ILostIt/blob/develop/LICENSE)

## Contact

You can contact me at anytime by email : diogo.dasilva2@eduvaud.ch
