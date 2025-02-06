# WayPinPoint

A Platform to book activities via Web or Mobile 

### Passos para instalação WebApp

#### Instalação do yii advanced e atualização do composer

1. Passo - Correr o comando php init

2. Passo - composer update

#### Correr os comandos de migração db


1. Passo - ./yii migrate --migrationPath=@yii/rbac/migrations

2. Passo - ./yii rbac/init

3. Passo - ./yii migrate --migrationPath=@vendor/devanych/yii2-cart/migrations

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
| emily | supplier1 |
| julia | supplier2 |
| matthew | manager1 |
| david | manager2 |
| lucas | salesperson1 |
| john | salesperson2 |
| clara | guide1 |
| sophie | guide2 |
| olivia | client1 |
| luke | client2 |

> NOTA: Para receber o bilhete e a fatura no email, registar conta com um email a que tenha acesso.


| Projeto elaborado por |
|-----------------------|
| Nº 2231438 André Barroso | 
| Nº 2232356 Joel Barbeiro |
| Nº 2232494 Pedro Lourenço	|
