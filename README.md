# DEHIA Auth Service
The Auth service for [DEHIA](http://sedici.unlp.edu.ar/handle/10915/116617), a platform for managing and executing data collection activities that require human intervention.

## Contents
- [DEHIA](#dehia)
- [Installation](#installation)
  - [Docker](#docker-recommended)
  - [Run locally (Linux)](#run-locally-linux)
- [Environment Variables](#environment-variables)
  - [Docker variables](#docker-variables)
  - [PHP variables](#php-variables)
- [Endpoints](#endpoints)
- [See Also](#see-also)

## DEHIA
DEHIA is a platform for Defining and Executing Human Intervention Activities. Its goal is to allow users without programming knowledge to create activities (sets of tasks, mainly for data collection) through a web authoring tool. The activities are exported to a configuration file and then "executed" (solved) from a mobile app. This kind of activities requires human intervention and cannot be solved automatically. 

There is also an API that manages the activities lifecycle, collects the data from the mobile app and returns the results. It also manages the security of the application. The API includes a Gateway and four services: Define, Auth, Collect and Results.

## Installation
You can install the service either in containerized version using Docker or locally (on Linux) using PHP7.4 and Apache or NGINX. The database can be external o
### Docker (recommended)
 1. Create an `app/.env.local` file based in `app/.env` (See [Environment Variables](#Environment-Variables))
 2. If the results service or the gateway are also run with docker, take note of the docker network.
 3. Build the image: 

 ```
 docker image build -t <image-tag> .
 ```
 4. Run the container - Only if needed: a) Expose the port you set in the `.env` file (if the gateway or the results service aren't run with Docker) b) Use a Docker network (if the gateway or the results service are run with docker). If one is run with Docker and the not the other, you will need both.
 ```
 docker run -e PORT=<container-port> --name <container-name> [-p <host-port>:<container-port>] [--network <poc-network>] <image-tag>
 ```
 5. Go to `http://localhost:<host-port>`. You should see a "Collect Index" message.
 6. Now you can add the URL to the results service and the gateway.

## Run locally (Linux)
# Environment Variables
Docker variablas go in the `.env` file. PHP variables go in the `app/.env.local` file.
## Docker varaibles
- **MYSQL_ROOT_PASSWORD**: root password for the MySQL container. 
- **MYSQL_DATABASE**: database to be created for the application. It must match the URL in `app/.env.local`
- **MYSQL_USER**: database user to be created for the application. It must match the URL in `app/.env.local`
- **MYSQL_PASSWORD**: password for the aforementioned database user. It must match the URL in `app/.env.local`
- **ADMINER_PORT**: port to be exposed for adminer user (DB client).
- **LOCAL_USER**: user in the docker system. The same id of the host user is preferred (because of the volume sharing the files)
## PHP variables
- **GOOGLE_API_KEY**: key obtained from the Google API Console.
- **GOOGLE_CLIENT_ID**: client for the DEHIA application in the Google API Console.
- **GOOGLE_CLIENT_SECRET**; secret for the DEHIA application in the GOOGLE API Console.
- **DATABASE_URL**: template for the MySQL URL. The placeholders must be filled with the information in the docker `.env` file.
- **JWT_PASSPHRASE**: symmetric key for signing the tokens (frontend <-> services). 

## Endpoints
- 


*Secured endpoint: it needs an `Authorization: Bearer <JWT-token>` header, where `JWT-token` is obtained from the gateway


## See also
- [DEHIA Frontend](https://github.com/mokocchi/autores-demo-client)
- [DEHIA Gateway](https://github.com/mokocchi/dehia_gateway)
- [DEHIA Mobile App](https://github.com/mokocchi/prototipo-app-actividades)
- [DEHIA Define Service](https://github.com/mokocchi/dehia_define)
- [DEHIA Collect Service](https://github.com/mokocchi/dehia_collect)
- [DEHIA Results Service](https://github.com/mokocchi/dehia_results)