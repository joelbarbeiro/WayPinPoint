php yii migrate/create create_dates_table --fields="date:date:notNull"

php yii migrate/create create_times_table --fields="hour:time:notNull"

php yii migrate/create create_activities_table --fields="name:string(200):notNull,description:string:notNull,photo:string(250):notNull,maxpax:integer:notNull,priceperpax:float:notNull,address:string(400):notNull"

php yii migrate/create create_calendar_table --fields="activities_id:integer:notNull:foreignKey(activities),date_id:integer:notNull:foreignKey(dates),time_id:integer:notNull:foreignKey(times)"

php yii migrate/create create_localsellpoint_table --fields="user_id:integer:foreignKey(user),address:string(400):notNull,name:string(100):notNull"

php yii migrate/create create_tickets_table --fields="activities_id:integer:notNull:foreignKey(activities),participant:integer:notNull:foreignKey(user),qr:string(250):notNull,status:integer:defaultValue(0):notNull"

php yii migrate/create create_pictures_table --fields="activities_id:integer:notNull:foreignKey(activities),user_id:integer:notNull:foreignKey(user),reviews:string(500):notNull,uri:string(250):notNull"

php yii migrate/create create_sales_table --fields="activities_id:integer:notNull:foreignKey(activities),buyer:integer:notNull:foreignKey(user),total:float:notNull"

php yii migrate/create create_bookings_table --fields="activities_id:integer:notNull:foreignKey(activities),calendar_id:integer:notNull:foreignKey(calendar),user_id:integer:notNull:foreignKey(user),numberpax:integer:notNull"

php yii migrate/create create_invoices_table --fields="user:integer:notNull:foreignKey(user),sales_id:integer:notNull:foreignKey(sales)"

php yii migrate/create create_userextras_table --fields="user:integer:notNull:foreignKey(user),phone:string(20):notnull,supplier:integer:notnull:defaultValue(0)"