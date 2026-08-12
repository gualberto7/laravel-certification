<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('study:status {level=Junior} {--completed : Indicates if the study is completed}')]
#[Description('Display the current certification study status')]
class StudyStatusCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Application: '.config('app.name'));
        $this->info('Nivel: '.$this->argument('level'));
        $this->info('Bloque: J1');

        if ($this->option('completed')) {
            $this->info('Estado: Completado');
        } else {
            $this->info('Estado: En curso');
        }

        return Command::SUCCESS;
    }
}
