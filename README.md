# I LOST IT

## Description
This project is a web app designed to help students and teachers from the CPNV (school in Switzerland) finding their lost objects.

## Getting started

### Prerequesites

- MariaDB v10.11.7 (LTS)
- Visual Studio Code (latest)
- PHP v8.3.4
- Composer v2.7.3
- MacOS Sonoma 14.3.1
- Docker (latest) (for development database only but not required. If you want to use the final MariaDB server directly, you can)

### Configuration

#### 1 - Environment variables
For this project to work, you'll need to have a .env file, named exactly ".env.local", in Model folder.

````dotenv
DB_HOST="HOSTNAME"
DB_PORT="" # Generally 3306
DB_NAME=""
DB_USER=""
DB_PASSWORD="THE_USER_PASSWORD"
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

Currently, I won't accept any collaboration on this project.

## License

TBD

## Contact

You can contact me at anytime by email : diogo.dasilva2@eduvaud.ch
