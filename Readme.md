# Your Project Title Here

One Paragraph of project description goes here

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

## Prerequisites

You will need to have docker installed on your local machine.

[Install Docker](https://docs.docker.com/docker-for-mac/install)

Then you will need to set up project specific configuration 

##Set Maintainer Information

Site email configuration

/api/conman.json

```
 "email": {
      "transport": "SENDMAIL",
      "fromname": "Your App Name Here",
      "fromaddr": "feedback@yoururl.com",
      "default_mimetype": "text\/html"
  }
```


##Database configuration

You will need to set db name, usesrname passwords etc...



Example:

* driver: "MySQL"
* host: db
* port: 3306
* username: root
* password: 'your_pass'
* database_name: "your_name"
* timezone: "UTC"

Nb: You will need to remove any instance of MySQL runnning on port 3306

/api/conman.json

```
"db": {
    "databases": {
      "Platform": {
        "production": {
          "driver": "MySQL",
          "host": "db",
          "port": "3306",
          "username": "root",
          "password": "your_pass",
          "database": "your_name",
          "charset": "utf8mb4"
        }
      }
    }
  },
```

Set your root db password in docker-compose.yaml

```
    environment:
      - MYSQL_ROOT_PASSWORD=secret
```



##Set the base URLs

conman.json

```
"deploy": {
    "assets": "",
    "mode": "development",
    "baseurl": "http:\/\/yoururl.dev",
    "profiles": {
      "default": {
        "baseurl": "http:\/\/yoururl.dev"
      }
    },
}
```

Set server_name and root ui directory in....

/docker/php-web/conf/nginx/sites-enabled/scaffold-ui.conf
/docker/php-web/conf/nginx/sites-enabled/scaffold-api.conf


##Change Hosts file

```
127.0.0.1   db api.yoururl.dev yoururl.dev
```


### Installing

Spin up the environment

```
docker-compose up
```

Update composer

```
composer u
```

Create a new database with mysql

```
Which ever way your prefer, MySqlPro or commandline MySql for example. 'your_name' should be the name of the database and utf8mb4 should be the encoding
```

Phinx migration configuration

```
    development:
        adapter: mysql
        host: 127.0.0.1
        name: site
        user: root
        pass: 'your_pass'
        port: 3306
        charset: utf8
```

Run DB auth Migration

```
./vendor/bin/phinx migrate -e development
```

Generate models

```
./vendor/bin/wave generate/models
```

Generate routes

```
./vendor/bin/wave generate/routes
```

Restart docker

```
docker-compose restart
```

### Installing UI

index.html should be in /ui/build directory. This can be changed by editing /docker/php-web/conf/nginx/sites-enabled/scaffold-ui.conf


## Running the tests

Todo  


## Deployment

Todo
