<?php

use App\Models\Service;
use App\Models\User;

it('switches between English and Swahili', function () {
    $this->withHeader('Referer', '/')
        ->get('/language/sw')
        ->assertRedirect()
        ->assertSessionHas('locale', 'sw');

    $this->withHeader('Referer', '/')
        ->get('/language/en')
        ->assertRedirect()
        ->assertSessionHas('locale', 'en');
});

it('rejects unsupported languages', function () {
    $this->get('/language/fr')->assertNotFound();
});

it('renders the homepage in the selected language', function () {
    Service::create([
        'name' => 'Test Service',
        'slug' => 'test-service',
        'description' => 'Test service description',
        'is_active' => true,
    ]);

    $this->withSession(['locale' => 'sw'])
        ->get('/')
        ->assertOk()
        ->assertSee('TOVUTI MOJA KWA HUDUMA ZAKO')
        ->assertSee('Angalia huduma');
});
