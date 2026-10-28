<?php

declare(strict_types=1);

it('renders Swagger UI documentation page', function (): void {
    $response = $this->get('/docs');

    $response->assertOk()
        ->assertSee('Begento API — Documentación Swagger / OpenAPI');
});

it('serves OpenAPI yaml specification file', function (): void {
    $response = $this->get('/docs/openapi.yaml');

    $response->assertOk()
        ->assertHeader('Content-Type', 'text/yaml; charset=utf-8')
        ->assertSee('Begento Product Catalog & Multi-Currency Pricing API', escape: false);
});
