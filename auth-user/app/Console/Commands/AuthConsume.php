<?php

namespace App\Console\Commands;

use App\Services\RabbitMQService;
use Illuminate\Console\Command;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthConsume extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auth:consume';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $rabbitMQService = new RabbitMQService();
        $rabbitMQService->consume();

    }
}
