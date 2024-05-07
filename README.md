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
DB_HOST="HOSTNAME"
DB_PORT="" # Generally 3306
DB_NAME=""
DB_USER=""
DB_PASSWORD="THE_USER_PASSWORD"

MAIL_SMTP_HOST="HOSTNAME"
MAIL_SMTP_PORT="" # Generally 465
MAIL_USER=""
MAIL_PASSWORD="THE_USER_PASSWORD"
# https://console.cloud.google.com/apis/credentials
MAIL_GOOGLE_CLIENT_ID=""
MAIL_GOOGLE_CLIENT_SECRET=""
MAIL_GOOGLE_REFRESH_TOKEN="" 
````

## Deployment

### On dev environment

TBD

### On integration environment

TBD

## Directory structure

```
└── DDS_PreTPI_ILostIt/
    ├── Controller
    ├── Model
    ├── View
    └── src/
        ├── css
        └── js
```

## Collaborate

TBD

## License

[MIT License](https://github.com/CPNV-TPI/ILostIt/blob/develop/LICENSE)

## Contact

You can contact me at anytime by email : diogo.dasilva2@eduvaud.ch
