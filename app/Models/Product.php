<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    /*
     * se coloca dentro de tu modelo Eloquent en Laravel, y significa que el modelo:
     * ✅ Usa factories para generar datos de prueba (gracias a HasFactory).
     * ✅ Soporta borrado lógico 
     */

    protected $table = 'product';
    protected $fillable = ['name', 'description', 'price', 'category_id'];

    protected $hidden = [
        "created_at",
        "updated_at"
    ];
    // Esconder atributos en la response

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

/*
* SoftDeletes
* 🔄 Se puede restaurar con `$model->restore()`.
* 🧹 Se puede borrar definitivamente con `$model->forceDelete()`.
* (No se muestra pero aparece en la BDD como eliminado, forma de seguridad para borrados equivocados)
*/