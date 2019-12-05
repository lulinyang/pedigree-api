##安装
- composer update
- php artisan key:generate
- php artisan migrate
- php artisan passport:install

##开发技巧
- php artisan make:controller api/ChatController
- php artisan make:repository ChatRepository 
- php artisan make:model Models/Chat

##开启wesocket
- php artisan swoole:server


##创建计划任务
- php artisan make:command WriteRedis --command=write:redis
- php artisan make:command WriteHistoryToday --command=write:historyToday

<!-- Chat -->
