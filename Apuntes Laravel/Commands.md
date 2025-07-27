
`Crear proyecto`
- composer create-project laravel/laravel name

`Ver todos los comandos`
- php artisan list

`Abrir servidor`
- php -S localhost:8000 -t public      

`Borra los datos de la BDD y con el seed ejecuta el seed predeterminado`
- php artisan migrate:fresh --seed

`Crear modelos, factories, seeders, controllers, events`
- php artisan make:model Category               
``
`Ejecuta específicamente un seeder`
- php artisan db:seed --class=ProductTableSeeder

`Ver endpoints`
- php artisan route:list

`Crear request`
- php artisan make:request UpdateProductRequest

`Reiniciar cache de Laravel`
- php artisan config:clear
- php artisan cache:clear
- php artisan config:cache

`Permite ejecutar código de PHP en tiempo real`
- php artisan tinker

`Actualizar caché`
- php artisan config:clear
- php artisan cache:clear

`Especificar evento para un listener`
- php artisan make:listener LogUserRegistered --event=UserRegistered

`Sirve para ejecutar trabajos (jobs) en segundo plano`
- php artisan queue:work

`Crear comando personalizado`

- php artisan make:command name

`Para ver la programación de tareas

- php artisan schedule:list

`Para que se ejecute la programación de tareas

- php artisan schedule:work

`Ejecutar todos los tests disponibles`

- php artisan test

`Para generar una APP_KEY nueva`
- php artisan key:generate --env=testing

`Generar key JWT`
- php artisan jwt:secret

`Limpiar caché de rutas`
- php artisan route:clear
[[Laravel 🔥]]

