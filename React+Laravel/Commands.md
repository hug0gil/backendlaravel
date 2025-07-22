
`Abrir servidor`
- php -S localhost:8000 -t public      

`Borra los datos de la BDD y con el seed ejecuta el seed predeterminado`
- php artisan migrate:fresh --seed

`Crear modelos, factories, seeders, controllers`
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


[[Laravel 🔥]]

