- git clone https://github.com/Mckaay/RBR_TASK.git
- cd RBR_TASK
- cp .env.example .env
- composer install
- ./vendor/bin/sail up -d
- ./vendor/bin/sail php artisan key:generate
- ./vendor/bin/sail php artisan migrate:fresh --seed
- http://localhost:8080/login test@example.com  password


- testowanie powiadomień:

- utworzyc taska z jutrzejsza data due_date
- w console.php zmienic na Schedule::job(new SendTaskDueReminders())->everyFifteenSeconds();
- ./vendor/bin/sail php artisan schedule:run  
- ./vendor/bin/sail php artisan queue:work

- http://localhost:8025 - pod tym linkiem mozna podejrzec wysłane mejle
 
