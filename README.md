# PHP Laravel environment
Docker environment required to run Laravel (based on official php and mysql docker hub repositories).

---

## Requirements
* Docker version 18.06 or later
* Docker compose version 1.22 or later
* An editor or IDE
* WSL2 (For Windows 10 or 11)

Note: OS recommendation - Linux Ubuntu based.

---

## Components
1. Nginx 1.20.2
2. PHP 8.1.3 fpm
3. MariaDB 10.6
4. Laravel 10.13.5
5. Node 19.6

## Setting up DEV environment
1.You can clone this repository or download it as a zip file.

```bash
git clone 
```

2.Open terminal and go to the project directory.

```bash
cd consultorio_medico
```

3.Build, start and install the docker images from your terminal:
```bash
make build 
make build-dev
make start-dev
make composer-install
```

4.Run config env, migrations, seeders for dev environment (This delete all data, and create new data):
```bash
make config-dev
```

5.Check and open in your browser next url: [http://localhost](http://localhost).

Note 1: If you want to change default docker configurations (NGINX_PORT, etc...) - open `.env.dev` file, edit necessary environment variable value and stop, rebuild, start docker containers.

Note 2: Remember if you change NGINX_PORT in `.env.dev`, the url will be: [http://localhost:NGINX_PORT](http://localhost:NGINX_PORT).
---

## Install/Uninstall dependencies from NPM, Composer, etc...
For installing/uninstalling dependencies from Node (NPM, YARN) or Composer, you two options:
1. Install dependencies from local shell you need to get shell access inside container:
```bash
make ssh-root
composer require laravel/ui // for example
```
Note: Please use `exit` command in order to return from container's shell to local shell.

2. Install dependencies from local shell without getting shell access inside container:
```bash
make node-install OPTIONS="--save-dev @babel/core @babel/preset-env" // for example
or
make node COMMAND="npm install --save-dev @babel/core @babel/preset-env || node -v || npm -v || yarn -v" // for example

make composer-require PACKAGE="laravel/ui" // for example
make composer-require-dev PACKAGE="laravel/ui" // for example
make composer-remove PACKAGE="laravel/ui" // for example
make composer-remove-dev PACKAGE="laravel/ui" // for example
```
## Guidelines
* make help - show all available commands
* [Commands](docs/commands.md)
* [Materio](docs/materio.md)

## License
[The MIT License (MIT)](LICENSE)
