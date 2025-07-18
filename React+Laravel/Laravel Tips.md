# Migrations

`Las migraciones son archivos que definen cómo debe crearse o modificarse la base de datos usando código PHP.`
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




[[Laravel 🔥]]

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

`Para obtener información de las peticiones y guardarlas en log predeterminadamente se escribirán en storage/logs/laravel.log, un log general que contiene todos los logs a lo largo del proyecto, en el .env podemos poner una opción para que haya un log concreto que se genere diariamente `

`Single para un log con todo y daily para los logs separados por día`

LOG_STACK=single,daily 

### Obtener datos y loggearlos

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


### Devolver datos 

`Mediante el método`  terminate  `devolveremos lo que queramos al cliente`

public function terminate(Request $request, Response $response)
{
Log::info("Response sent:", [
"status" => $response->getStatusCode(),
"content" => $response->getContent()
]);
}

