<?php

test('study:status command shows correct information', function () {
    $this->artisan('study:status')
        ->expectsOutput('Application: '.config('app.name'))
        ->expectsOutput('Nivel: Junior')
        ->expectsOutput('Bloque: J1')
        ->assertSuccessful();
});

test('study:status command shows correct information with level argument', function () {
    $this->artisan('study:status Senior')
        ->expectsOutput('Application: '.config('app.name'))
        ->expectsOutput('Nivel: Senior')
        ->expectsOutput('Bloque: J1')
        ->assertSuccessful();
});

test('study:status command shows correct information with completed option', function () {
    $this->artisan('study:status --completed')
        ->expectsOutput('Application: '.config('app.name'))
        ->expectsOutput('Nivel: Junior')
        ->expectsOutput('Bloque: J1')
        ->expectsOutput('Estado: Completado')
        ->assertSuccessful();
});
