<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_la_raiz_muestra_el_sitio_publico(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Tu inmobiliaria');
    }

    public function test_el_login_administrativo_es_publico(): void
    {
        $this->get('/administracion/ingreso')
            ->assertOk()
            ->assertSee('Ingresar al panel');
    }

    public function test_el_dashboard_requiere_autenticacion(): void
    {
        $this->get('/administracion')
            ->assertRedirect('/administracion/ingreso');
    }

    public function test_los_tipos_de_propiedad_requieren_autenticacion(): void
    {
        $this->get('/administracion/tipos-propiedad')
            ->assertRedirect('/administracion/ingreso');
    }

    public function test_las_ubicaciones_requieren_autenticacion(): void
    {
        $this->get('/administracion/ubicaciones')
            ->assertRedirect('/administracion/ingreso');
    }

    public function test_el_autocomplete_de_ubicaciones_requiere_autenticacion(): void
    {
        $this->get('/administracion/ubicaciones/buscar?buscar=Tigre')
            ->assertRedirect('/administracion/ingreso');
    }

    public function test_las_propiedades_requieren_autenticacion(): void
    {
        $this->get('/administracion/propiedades')
            ->assertRedirect('/administracion/ingreso');
    }

    public function test_el_formulario_de_propiedades_requiere_autenticacion(): void
    {
        $this->get('/administracion/propiedades/crear')
            ->assertRedirect('/administracion/ingreso');
    }

    public function test_las_consultas_requieren_autenticacion(): void
    {
        $this->get('/administracion/consultas')
            ->assertRedirect('/administracion/ingreso');
    }

    public function test_las_tasaciones_requieren_autenticacion(): void
    {
        $this->get('/administracion/tasaciones')
            ->assertRedirect('/administracion/ingreso');
    }
}
