# Keystone Tech Test

Hello and welcome to my submission for the tech test! 

## Getting started
The following instructions should help you get fully setup using the tech test as intended.

### Prerequisites

- **Docker**

    You must have a usable version of docker installed. If you don't have this, it can be installed by following the instructions at the following link:
    
    https://docs.docker.com/engine/install/


- **Docker compose**
    
    You must have a usable version of "docker compose" installed. If you don't have this, it can be installed by following the instructions at the following link:

    https://docs.docker.com/compose/install/

### Setup
To setup the project, clone this repository into whatever linux or Windows WSL instance you have docker installed on.

#### Container setup
In the `./api` directory, copy the `.env.example` file into a new `.env` file, and add a db name and password for the db root user. _(Using the root user is bad! BUT given the scope of this project, we aren't going to bother creating a new sql user specifically for it)_:

```bash
...
DB_DATABASE=$YOUR_DB_NAME_HERE
DB_USERNAME=root
DB_PASSWORD=$YOUR_DB_PASSWORD_HERE
...
```

In the root directory of the project, run the following to build and begin running all of the project containers;

```bash
ROOT_DB_PASSWORD=$YOUR_DB_PASSWORD_HERE docker compose up -d --build
```
_(Replacing `$YOUR_DB_PASSWORD_HERE` before you do so of course!)_


#### MySQL setup
At this stage, all containers should be running, however we need to do a small amount of MySQL setup for everything to function as intended.

To access the mysql container, run 

```
docker exec -it database bash
```

and login using the root user, entering the password you chose earlier when prompted. 

```
mysql -u root -p
```

Here, create a new table (of whatever name of your choosing);

```sql
CREATE TABLE $YOUR_DB_NAME_HERE;
```

Which should now mean the project has access to the MySQL table it needs.

## Using the project

The project utilises an NGINX reverse proxy to route all traffic from the tech-test to http://tech-test.localhost. Go to this address, and provided nothing went wrong in setup, the project should be there!


### Final word
Thank you for the opportunity to complete the above tech test! 

Hopefully the above got you all setup and running with my implementation; I look forward to discussing it in further detail in interview!

Best!

Olly