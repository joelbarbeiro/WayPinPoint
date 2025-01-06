# WayPinPoint

A Platform to book activities via Web or Mobile 

### Passos para instalação WebApp

#### Instalação do yii advanced e atualização do composer

1. passo - Correr o comando php init

2. passo - composer update

#### Correr os comandos de migração db


1. Passo - ./yii migrate --migrationPath=@yii/rbac/migrations

2. Passo - ./yii rbac/init

3. Passo - ./php yii migrate --migrationPath=@vendor/devanych/yii2-cart/migrations

> NOTA: Com as proximas migrações a tabela user, userextra e atividades ficam populadas. 

4. Passo - ./yii migrate

#### Instalar imagick

1. Passo - instalar ImageMagick ( Para fazer checkout no carrinho )

2. Passo - Copiar php_imagick.dll para pasta c:/wamp64/bin/php/php8.1.26/ext/

3. Passo - Adicionar imagick nas extensões php.ini 

> GitHub : https://github.com/joelbarbeiro/WayPinPoint.git

#### Credenciais de acesso as contas do projeto :

| USERNAME | PASSWORD |
|----------|----------|
| supplier1 | supplier1 |
| supplier2 | supplier2 |
| manager1 | manager1 |
| manager2 | manager2 |
| salesperson1 | salesperson1 |
| salesperson2 | salesperson2 |
| guide1 | guide1 |
| guide2 | guide2 |
| client1 | client1 |
| client2 | client2 |

> NOTA: Para receber o bilhete e a fatura no email, registar conta com um email a que tenha acesso.


| Projeto elaborado por |
|-----------------------|
| Nº 2231438 André Barroso | 
| Nº 2232356 Joel Barbeiro |
| Nº 2232494 Pedro Lourenço	|
