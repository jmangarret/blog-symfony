
1. Tener instalado en el equipo local git, composer y nodejs.

2. Ejecutar los siguientes comandos.
git clone https://github.com/jmangarret/blog-symfony5.git

mkdir blog-symfony5/blog
mover manualemnte el proyecto a la carpeta blog excepto la carpeta docker y el compose.yml 

cd blog
composer install 
npm install yarn
yarn install 
yarn encore prod 

3. Copiar archivo dentro de blog .env

4. Para correr con docker.
Instalar docker.
En la raiz donde esta el docker-compose.yml ejecutar 
docker composer -d up
Abrir navegador http://localhost:8000/

5. Correr localmente (opcional)

php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console server:start



