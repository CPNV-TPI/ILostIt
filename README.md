# I LOST IT

## Description
This project is a web app designed to help students and teachers from the CPNV (school in Switzerland) finding their lost objects.

## Getting started

### Prerequesites

- MariaDB v10.11.7 (LTS)
- PhpStorm 2024.1.1
- PHP v8.3.6
- Composer v2.7.3

### Configuration

#### 1 - Environment variables
For this project to work, you'll need to have a .env file, named exactly ".env.local", in Model folder.

````dotenv
HOSTNAME="localhost" # For example

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
1. Download PHP on this following link -> https://windows.php.net/downloads/releases/php-8.3.7-nts-Win32-vs16-x64.zip
2. Extract the folder in your C: and rename the folder to **php**
3. Rename the file php.ini-development to php.ini
4. Adding the PHP folder to the Windows path environment variable -> https://help.learnosity.com/hc/en-us/articles/360000757757-Environment-Setup-Guide-PHP#installing-php-on-windows-1011

#### 2. Composer
For this, you can follow this tutorial directly made by Composer team : https://getcomposer.org/doc/00-intro.md#using-the-installer

#### 2. PHPStorm
1. Download PHPStorm on this following link (you'll probably need to create an account) -> https://www.jetbrains.com/fr-fr/phpstorm/
2. Make the basic installation of the program (no modifications have been made on my side)
3. Configure PHP in the settings -> https://www.jetbrains.com/help/phpstorm/configuring-local-interpreter.html

#### 3. MariaDB
1. To install MariaDB, you can follow this link : https://mariadb.com/kb/en/installing-mariadb-msi-packages-on-windows/
2. To create the database, execute the file DDS_TPI_ILostIt_Db.sql available in database folder

#### 4. Run Composer to install packages
Finally, after installing all the things above, if you open this project and click on composer.json file, you should be able to install the packages by clicking on the "install" button in the top right corner.

### On integration environment

TBD

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
