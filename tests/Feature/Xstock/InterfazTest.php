<?php

namespace Tests\Feature\Xstock;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InterfazTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $role = Role::create(['nombre' => 'Administrador', 'permisos' => []]);

        return User::factory()->create(['role_id' => $role->id, 'estado' => 'activo']);
    }

    public function test_recordarme_del_login_se_envia_y_funciona(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
            'remember' => 'on',
        ]);

        $this->assertAuthenticated();
        $response->assertCookie(auth()->guard()->getRecallerName());
    }

    public function test_pantallas_de_acceso_estan_en_espanol(): void
    {
        $this->get(route('login'))->assertOk()->assertSee('Recordarme')->assertSee('name="remember"', false);
        $this->get(route('password.request'))->assertOk()->assertSee('Recuperar Contraseña');
        $this->get(route('password.reset', 'token'))->assertOk()->assertSee('Nueva Contraseña');
    }

    public function test_mensajes_de_validacion_en_espanol(): void
    {
        $this->actingAs($this->admin())
            ->from(route('usuarios.index'))
            ->post(route('usuarios.store'), [])
            ->assertSessionHasErrors(['name' => 'El campo nombre es obligatorio.']);
    }

    public function test_mensaje_de_exito_se_muestra_en_el_layout(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->withSession(['success' => 'Usuario creado correctamente.'])
            ->get(route('usuarios.index'))
            ->assertOk()
            ->assertSee('flash-alert', false)
            ->assertSee('Usuario creado correctamente.');
    }

    public function test_mensaje_de_error_de_sesion_se_muestra(): void
    {
        $this->actingAs($this->admin())
            ->withSession(['error' => 'No se puede eliminar el rol.'])
            ->get(route('roles.index'))
            ->assertOk()
            ->assertSee('No se puede eliminar el rol.');
    }

    public function test_confirmaciones_usan_modal_y_escapan_nombres(): void
    {
        $admin = $this->admin();
        \App\Models\Producto::create([
            'nombre' => "Prueba'); alert(1); ('",
            'precio' => 100, 'precio_compra' => 50, 'stock' => 5, 'estado' => 'activo',
        ]);

        $html = $this->actingAs($admin)->get(route('productos.index'))->assertOk()->getContent();

        $this->assertStringNotContainsString('return confirm(', $html);
        $this->assertStringContainsString('data-confirm="¿Eliminar el producto «Prueba&#039;); alert(1); (&#039;»?"', $html);
    }
}
