<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue; // Implementamos para poder decir que es un proceso en 2º plano
use Illuminate\Queue\InteractsWithQueue; // Trait que sirve para interactuar con la queue
use Illuminate\Support\Facades\Log;

class LogUserRegistered implements ShouldQueue
{
    use InteractsWithQueue;
    public $tries = 3;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event): void // Para poner más eventos los separamos con pipe |
    {
        //$this->release(5); Tiempo de espera
        throw new Exception("An error ocurred in the registration: {$this->attempts()}");
        //Log::info("Nuevo usuario registrado", ["id" => $event->user->id]);
    }

    public function failed(UserRegistered $event, $exception): void
    {
        Log::critical("The log in the register from the user {$event->user["id"]}");
    }
}
