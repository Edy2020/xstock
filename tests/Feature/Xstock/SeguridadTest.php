<?php

namespace Tests\Feature\Xstock;

use App\Models\Producto;
use App\Models\Role;
use App\Models\User;
use App\Models\Venta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeguridadTest extends TestCase
{
    use RefreshDatabase;

    private Role $admin;
    private Role $vendedor;
    private Role $almacen;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Role::create(['nombre' => 'Administrador', 'permisos' => []]);
        $this->vendedor = Role::create(['nombre' => 'Vendedor', 'permisos' => ['productos.ver', 'ventas.ver', 'ventas.crear']]);
        $this->almacen = Role::create(['nombre' => 'Almacén', 'permisos' => ['productos.ver']]);
    }

    private function usuario(Role $role, array $attrs = []): User
    {
        return User::factory()->create(array_merge(['role_id' => $role->id, 'estado' => 'activo'], $attrs));
    }

    private function producto(array $attrs = []): Producto
    {
        return Producto::create(array_merge([
            'nombre' => 'Producto ' . uniqid(),
            'precio' => 1000,
            'precio_compra' => 500,
            'stock' => 10,
            'estado' => 'activo',
        ], $attrs));
    }

    private function vender(User $user, array $items)
    {
        return $this->actingAs($user)->post(route('ventas.store'), [
            'metodo_pago' => 'Efectivo',
            'productos' => json_encode($items),
        ]);
    }

    public function test_venta_valida_descuenta_stock_y_calcula_total(): void
    {
        $p = $this->producto();

        $this->vender($this->usuario($this->vendedor), [['id' => $p->id, 'cantidad' => 3, 'descuento' => 10]])
            ->assertSessionHasNoErrors();

        $this->assertSame(7, $p->fresh()->stock);
        $this->assertSame(2700, Venta::first()->total);
    }

    public function test_venta_rechaza_cantidad_negativa(): void
    {
        $p = $this->producto();

        $this->vender($this->usuario($this->vendedor), [['id' => $p->id, 'cantidad' => -50]])
            ->assertSessionHasErrors();

        $this->assertSame(10, $p->fresh()->stock);
        $this->assertSame(0, Venta::count());
    }

    public function test_venta_rechaza_descuento_mayor_a_100(): void
    {
        $p = $this->producto();

        $this->vender($this->usuario($this->vendedor), [['id' => $p->id, 'cantidad' => 1, 'descuento' => 500]])
            ->assertSessionHasErrors();

        $this->assertSame(0, Venta::count());
    }

    public function test_venta_rechaza_producto_inactivo(): void
    {
        $p = $this->producto(['estado' => 'inactivo']);

        $this->vender($this->usuario($this->vendedor), [['id' => $p->id, 'cantidad' => 1]])
            ->assertSessionHasErrors();

        $this->assertSame(10, $p->fresh()->stock);
    }

    public function test_anular_dos_veces_no_duplica_stock(): void
    {
        $p = $this->producto();
        $admin = $this->usuario($this->admin);
        $this->vender($admin, [['id' => $p->id, 'cantidad' => 4]]);
        $venta = Venta::first();

        $this->actingAs($admin)->post(route('ventas.anular', $venta));
        $this->actingAs($admin)->post(route('ventas.anular', $venta));

        $this->assertSame(10, $p->fresh()->stock);
    }

    public function test_ventas_anuladas_no_cuentan_como_ingresos(): void
    {
        $p = $this->producto();
        $admin = $this->usuario($this->admin);
        $this->vender($admin, [['id' => $p->id, 'cantidad' => 2]]);
        $this->actingAs($admin)->post(route('ventas.anular', Venta::first()));

        $this->actingAs($admin)->get(route('ventas.index'))
            ->assertOk()
            ->assertViewHas('ingresosHoy', 0);
    }

    public function test_rutas_de_consulta_exigen_permiso_ver(): void
    {
        $almacen = $this->usuario($this->almacen);

        $this->actingAs($almacen)->get(route('productos.index'))->assertOk();
        $this->actingAs($almacen)->get(route('ventas.index'))->assertForbidden();
        $this->actingAs($almacen)->get(route('ventas.export.excel'))->assertForbidden();
        $this->actingAs($almacen)->get(route('proveedores.index'))->assertForbidden();
        $this->actingAs($almacen)->get(route('estadisticas.index'))->assertForbidden();
    }

    public function test_administrador_principal_tiene_acceso_total(): void
    {
        $admin = $this->usuario($this->admin);
        $this->assertTrue($admin->hasPermission('historial.ver'));

        $p = $this->producto();
        $this->vender($admin, [['id' => $p->id, 'cantidad' => 1]]);

        $this->actingAs($admin)->get(route('dashboard'))->assertOk();
        $this->actingAs($admin)->get(route('estadisticas.index'))->assertOk();
    }

    public function test_usuario_desactivado_es_expulsado_de_su_sesion(): void
    {
        $user = $this->usuario($this->vendedor);
        $this->actingAs($user)->get(route('dashboard'))->assertOk();

        $user->update(['estado' => 'inactivo']);

        $this->actingAs($user)->get(route('dashboard'))->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_roles_solo_guardan_permisos_validos(): void
    {
        $this->actingAs($this->usuario($this->admin))->put(route('roles.update', $this->almacen), [
            'nombre' => 'Almacén',
            'permisos' => ['productos.ver', 'permiso.inventado'],
        ]);

        $this->assertSame(['productos.ver'], $this->almacen->fresh()->permisos);
    }

    public function test_actualizacion_masiva_no_modifica_al_administrador(): void
    {
        $this->admin->update(['permisos' => ['roles.gestionar']]);

        $this->actingAs($this->usuario($this->admin))->post(route('roles.bulk'), ['roles' => []]);

        $this->assertSame(['roles.gestionar'], $this->admin->fresh()->permisos);
    }

    public function test_no_se_puede_quitar_el_rol_al_ultimo_administrador(): void
    {
        $admin = $this->usuario($this->admin);

        $this->actingAs($admin)->put(route('usuarios.update', $admin), [
            'name' => $admin->name,
            'email' => $admin->email,
            'role_id' => $this->vendedor->id,
            'estado' => 'activo',
        ])->assertSessionHasErrors();

        $this->assertSame($this->admin->id, $admin->fresh()->role_id);
    }

    public function test_exportacion_csv_neutraliza_formulas(): void
    {
        $this->producto(['nombre' => '=HYPERLINK("http://malicioso")']);

        $csv = $this->actingAs($this->usuario($this->admin))
            ->get(route('productos.export.excel'))
            ->streamedContent();

        $this->assertStringContainsString("'=HYPERLINK", $csv);
    }
}
