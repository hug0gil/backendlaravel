# Recordatorios

- Al crear clases poner namespace
- Usar `use` para importar clases necesarias en el archivo
- Si necesitas que un modelo funcione con JWT, debe implementar la **interfaz `JWTSubject
- Cuando una clase necesita un parámetro externo (como una clave), usar **una función anónima en `bind()`** para pasarlo
-  Antes de hacer `JWTAuth::getToken()` asegúrate de que el token esté presente, o dará `null`
# Migrations

`Las migraciones son archivos que definen cómo debe crearse o modificarse la base de datos usando código PHP.`

Ejemplo:

public function up()
{
    Schema::create('productos', function (Blueprint $table) {
        $table->id(); // id producto, clave primaria autoincremental
        
        $table->string('nombre'); // nombre del producto (obligatorio)
        $table->string('codigo_sku')->unique(); // código SKU único
        
        $table->text('descripcion')->nullable(); // descripción opcional
        
        $table->integer('stock')->default(0); // stock disponible con valor por defecto
        
        $table->decimal('precio', 10, 2); // precio con 2 decimales
        
        $table->boolean('activo')->default(true); // producto activo o no
        
        $table->date('fecha_disponibilidad')->nullable(); // fecha desde que está disponible
        
        $table->json('atributos_adicionales')->nullable(); // campo JSON para info extra
        
        $table->unsignedBigInteger('categoria_id')->nullable(); // clave foránea a categorías
        
        // Restricción foránea con borrado en cascada null (soft delete categoria)
        $table->foreign('categoria_id')
              ->references('id')->on('categorias')
              ->onDelete('set null');
        
        $table->timestamps(); // created_at y updated_at
        $table->softDeletes(); // deleted_at para borrado lógico
    });
}

public function down()
{
    Schema::dropIfExists('productos');
}

# Seeding

`Los seeders permiten poblar la base de datos con datos de prueba o iniciales de forma automática`
## <u>Cuándo usar un seeder</u>

- **Para poblar la base de datos con datos predeterminados o iniciales**, como:
    
    - Categorías base (Ej: “Electrónica”, “Ropa”, “Alimentos”).
        
    - Usuarios de prueba.
        
    - Configuraciones, roles, permisos.
        
    - Datos que tu app necesita para funcionar desde el inicio.
        
- Los **seeders** son scripts que ejecutas una o varias veces para llenar la base con datos “reales” o coherentes.
    

---

## Y cómo usar factories con seeders

- Los **factories** son plantillas para generar datos ficticios o aleatorios, que usas dentro de seeders para automatizar la creación masiva.
    
- Por ejemplo, en el seeder:


`Category::factory(5)->create()->each(function ($category) {     Product::factory(10)->create(['category_id' => $category->id]); });`

Creas 5 categorías y para cada una creas 10 productos.

---

## Resumen práctico

- Quieres datos “de verdad” que siempre estén en la base: **Seeder.**
    
- Quieres muchos datos de prueba, aleatorios y rápidos: **Factories dentro de seeders.**


# Models y Factories

`Las factories son clases que permiten generar datos falsos para modelos de Eloquent, utilizando la librería Faker`

## <u>Cuando usar factories</u>

- Las **factories** son perfectas para generar datos de prueba **rápidos, dinámicos y variados**.
    
- En los tests automatizados (PHPUnit, Pest...), las factories te permiten crear objetos con datos aleatorios o específicos para cada caso.
    
- Puedes usar:
    
    - `make()` → crea el modelo en memoria, **sin guardar en BD** (útil para pruebas rápidas sin persistencia).
        
    - `create()` → crea y guarda el modelo en la base de datos temporal de test.
        
- Puedes crear muchos registros con diferentes configuraciones para probar funcionalidades.
    

---

## <u>Ejemplo en test:</u>

`Ejemplo de clase Factory`

class ProductFactory extends Factory
{
    // Modelo asociado
    protected $model = Product::class;

    /**
     * Define el estado por defecto del modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 10, 500)
            'category_id' => Category::factory()
        ];
    }
}


`public function test_product_belongs_to_category() 
{     
$product = Product::factory()->create();    

$this->assertNotNull(product->category); 
}`

Aquí creas un producto con categoría creada automáticamente gracias a la relación en el factory.

# Middleware

Los middlewares son filtros que se ejecutan antes o después de procesar una petición HTTP. Sirven para aplicar reglas como autenticación, verificación de tokens, permisos, etc.
## <u>Como aplicar middleware</u>

`Para aplicar un middleware global lo haremos en bootstrap/app.php`

->withMiddleware(function (Middleware $middleware): void {
$middleware->append(CheckValueInHeader::class);
// Middleware global
})

`Para aplicar un middleware en un endpoint concreto routes/api.php`

Route::apiResource("/product", ProductController::class)
->middleware(CheckValueInHeader::class);

`Creación de alias (en bootstrap/app.php)`

$middleware->alias(["checkvalue" => CheckValueInHeader::class]);

`Pasar datos al proceso de middleware`

middleware("checkvalue:4545,pato")

public function handle(Request $request, Closure $next, $number, $some): Response

## <u>Logs</u>

Para obtener información de las peticiones y guardarlas en log predeterminadamente se escribirán en storage/logs/laravel.log, un log general que contiene todos los logs a lo largo del proyecto, en el .env podemos poner una opción para que haya un log concreto que se genere diariamente 

Single para un log con todo y daily para los logs separados por día`

`LOG_STACK=single,daily`

### <u>Obtener datos y loggearlos</u>

`En handle obtenemos los datos mediante la $request y utilizamos Log` use Illuminate\Support\Facades\Log, `usamos info porque es un log de información`

$data = [
"url" => $request->fullUrl(),
"ip" => $request->ip(),
"method" => $request->method(),
"headers" => $request->headers->all(),
"body" => $request->getContent()
];

//dd($data);
Log::info("Request recieved:", $data);


### <u>Devolver datos </u>

Mediante el método `terminate`  devolveremos lo que queramos al cliente

`public function terminate(Request $request, Response $response)
`{
`Log::info("Response sent:", [
`"status" => $response->getStatusCode(),
`"content" => $response->getContent()
`]);
`}

### <u>Agrupar endpoints </u>

//Creamos una agrupación y todos los endpoints que ponemos dentro pasan por el middleware

`Route::middleware("jwt.auth")->group(function () {
`Route::get("/who", [AuthController::class, "who"]);
`});



# JWT

**JWT (JSON Web Token)** es un método para autenticar usuarios mediante un token seguro en formato JSON que se usa en cada petición tras iniciar sesión. Utilizaremos la librería `tymon/jwt-auth`

## Instalación

Instalamos la librería mediante composer

`composer require tymon/jwt-auth`

Comando para crear el archivo de configuración 

`php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"`


Para generar la clave secreta puesta en el .env para que no sea visible

`php artisan jwt:secret`

## Configuración

### <u>jwt.php</u>

Tiempo de vida del token

`'ttl' => env('JWT_TTL', 1440),` //Va en minutos

Formato de encriptación de los token

`'algo' => env('JWT_ALGO', Tymon\JWTAuth\Providers\JWT\Provider::ALGO_HS256),`

Obtener el valor de la clave secreta para firmar y verificar

'secret' => env('JWT_SECRET'),

### <u>auth.php</u>

Laravel, por defecto, **está preparado para webs**, no para APIs, entonces necesitas:

Cambiar `defaults` y agregar `guards`:

'defaults' => [
	'guard' => 'api',
	'passwords' => 'users',
],

Añadimos a `guards`:

'api' => [
    'driver' => 'jwt',
    'provider' => 'users',
],

|Cambio|¿Para qué sirve?|
|---|---|
|`'defaults.guard' => 'api'`|Para que Laravel use JWT por defecto en las rutas protegidas|
|`'guards.api.driver' => 'jwt'`|Para decirle a Laravel que el guard `api` funciona con tokens JWT|

### <u>user.php</u>

Clase user predeterminada que modificaremos para adaptarla al uso de tokens, implementaremos la interfaz `JWTSubject` y con ella sus métodos

`class User extends Authenticatable implements JWTSubject`

- `getJWTIdentifier()`: devuelve el ID único del usuario para identificarlo en el token JWT.
    
- `getJWTCustomClaims()`: devuelve datos extra que quieres agregar al token (si quieres)

### <u>LogIn</u>

JWTAuth::attempt($credentials)
### <u>LogOut</u>

$token = JWTAuth::getToken();
JWTAuth::invalidate($token);

https://jwt.io para verificar tokens y en código para ver el token accedemos con `config('jwt.secret')`

# Service Container

### <u>💡 Regla general:</u>

 Si la clase tiene lógica de negocio, accede a datos o podría necesitar cambiarse, debes inyectarla.

### <u>Provider</u>

Todas las clases se configuran en el provider que es:

Es donde le dices a Laravel _cómo debe construir o registrar_ una clase o servicio para que pueda inyectarla automáticamente cuando se necesite.

## <u>Config</u>

Instancia moderna

Instanciamos en el constructor especificando la visibilidad del service
- `protected` para que puedan acceder a ella clases heredadas 
- `private` para esa única clase

`public function __construct(protected ProductService $productService) {}`

###  <u>Interfaces</u>

Cuando pidan una instancia de un interfaz de una instancia de la clase concreta, se hace para ganar flexibilidad y si en un futuro se quiere cambiar se cambia la clase y no las implementaciones

`$this->app->bind(abstract: MessageServiceInterface::class, HiService::class);`

###  <u>Constructor con parámetros</u>

Usamos una función anónima para instanciar la clase y ponerle el atributo que nosotros queramos

`$this->app->bind(EncryptService::class, function () {
`return new EncryptService(env("KEY_ENCRYPT"));
`});`

###  <u>Context binding</u>

Pongo un app->bind como "default" por así decirlo y luego especifíco para cada controller

`$this->app->when(InfoController::class)->needs(MessageServiceInterface::class)`
`->give(HiService::class);`



[[Laravel 🔥]]

