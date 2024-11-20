# WayPinPoint
A Platform to book activities via Web or Mobile 

# WebApp
## Setup
After cloning the repository, navigate to the yii-application folder and run the following commands in the terminal:
./yii migrate --migrationPath=@yii/rbac/migrations
./yii rbac/init
./php yii migrate --migrationPath=@vendor/devanych/yii2-cart/migrations
./yii migrate
