<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Compra;
use App\Models\Configuracion;
use App\Models\Empleado;
use App\Models\MateriaPrima;
use App\Models\MovimientoInventario;
use App\Models\OrdenProduccion;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\User;
use App\Models\Venta;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@tallertextil.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'position' => 'Gerente General',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'supervisor@tallertextil.com'],
            [
                'name' => 'Carlos Mendoza',
                'password' => Hash::make('password'),
                'role' => 'supervisor',
                'position' => 'Jefe de Producción',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'operario@tallertextil.com'],
            [
                'name' => 'María Torres',
                'password' => Hash::make('password'),
                'role' => 'operario',
                'position' => 'Costurera',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // --- Clientes ---
        $clientes = [
            ['Confecciones Andina S.A.C.', 'RUC', '20481234567', 'ventas@andina.com', '987654321', 'Lima'],
            ['Textiles del Sur E.I.R.L.', 'RUC', '20512345678', 'contacto@textilsur.com', '956123456', 'Arequipa'],
            ['Moda Express', 'RUC', '20601234567', 'info@modaexpress.pe', '999888777', 'Lima'],
            ['Uniformes Perú', 'RUC', '20487654321', 'compras@uniformesperu.com', '944556677', 'Trujillo'],
            ['Boutique Lima', 'DNI', '45678912', 'boutiquelima@gmail.com', '933221100', 'Lima'],
        ];
        foreach ($clientes as [$nombre, $td, $doc, $email, $tel, $ciudad]) {
            Cliente::updateOrCreate(['numero_documento' => $doc], [
                'nombre' => $nombre, 'tipo_documento' => $td, 'email' => $email,
                'telefono' => $tel, 'ciudad' => $ciudad, 'estado' => true,
            ]);
        }

        // --- Proveedores ---
        $proveedores = [
            ['Hilos y Telas Import S.A.C.', '20455566677', 'importado', 'Crédito 30 días'],
            ['Distribuidora Textil Nacional', '20466677788', 'nacional', 'Contado'],
            ['Avíos y Botones Perú', '20477788899', 'nacional', 'Crédito 15 días'],
        ];
        foreach ($proveedores as [$rs, $ruc, $tipo, $cond]) {
            Proveedor::updateOrCreate(['ruc' => $ruc], [
                'razon_social' => $rs, 'tipo' => $tipo, 'condicion_pago' => $cond, 'estado' => true,
            ]);
        }

        // --- Productos ---
        $productos = [
            ['POL-001', 'Polo cuello redondo', 'Polos', 'M', 'Blanco', 29.90, 14.50, 120],
            ['POL-002', 'Polo pique', 'Polos', 'L', 'Azul marino', 39.90, 19.00, 80],
            ['UNI-001', 'Camisa escolar', 'Uniformes', '12', 'Celeste', 34.90, 16.00, 45],
            ['CHO-001', 'Chompa lana', 'Chompas', 'S', 'Gris', 79.90, 38.00, 30],
            ['PAN-001', 'Pantalón drill', 'Pantalones', '32', 'Beige', 59.90, 27.00, 4],
        ];
        foreach ($productos as [$cod, $nom, $cat, $talla, $color, $precio, $costo, $stock]) {
            Producto::updateOrCreate(['codigo' => $cod], [
                'nombre' => $nom, 'categoria' => $cat, 'talla' => $talla, 'color' => $color,
                'precio' => $precio, 'costo' => $costo, 'stock' => $stock, 'estado' => true,
            ]);
        }

        // --- Materia prima ---
        $prov1 = Proveedor::first();
        $materias = [
            ['TEL-001', 'Tela algodón jersey', 'tela', 'metro', 850, 200, 12.50],
            ['TEL-002', 'Tela drill', 'tela', 'metro', 120, 150, 15.00],
            ['HIL-001', 'Hilo poliéster', 'hilo', 'cono', 60, 40, 8.00],
            ['AVI-001', 'Botones', 'avio', 'unidad', 5000, 1000, 0.15],
        ];
        foreach ($materias as [$cod, $nom, $tipo, $uni, $stock, $min, $costo]) {
            MateriaPrima::updateOrCreate(['codigo' => $cod], [
                'nombre' => $nom, 'tipo' => $tipo, 'unidad' => $uni, 'stock' => $stock,
                'stock_minimo' => $min, 'costo_unitario' => $costo,
                'proveedor_id' => $prov1?->id, 'estado' => true,
            ]);
        }

        // --- Empleados ---
        $empleados = [
            ['Carlos Mendoza', '40112233', 'Jefe de Producción', 'Administración', 2800],
            ['María Torres', '41223344', 'Costurera', 'Confección', 1500],
            ['Jorge Ramírez', '42334455', 'Cortador', 'Corte', 1600],
            ['Ana Flores', '43445566', 'Control de Calidad', 'Control de Calidad', 1550],
            ['Pedro Castro', '44556677', 'Acabador', 'Acabado', 1450],
        ];
        foreach ($empleados as [$nom, $dni, $cargo, $area, $salario]) {
            Empleado::updateOrCreate(['dni' => $dni], [
                'nombre' => $nom, 'cargo' => $cargo, 'area' => $area,
                'salario' => $salario, 'fecha_ingreso' => now()->subMonths(rand(3, 24)),
                'estado' => true,
            ]);
        }

        // --- Pedidos con ítems (solo si no existen) ---
        if (Pedido::count() === 0) {
            $clientesDb = Cliente::all();
            $productosDb = Producto::all();
            $estados = ['pendiente', 'en_produccion', 'acabado', 'entregado'];

            for ($i = 1; $i <= 6; $i++) {
                $pedido = Pedido::create([
                    'codigo' => 'PED-'.str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                    'cliente_id' => $clientesDb->random()->id,
                    'fecha_pedido' => now()->subDays(rand(1, 30)),
                    'fecha_entrega' => now()->addDays(rand(3, 20)),
                    'estado' => $estados[array_rand($estados)],
                    'total' => 0,
                ]);
                $total = 0;
                foreach ($productosDb->random(rand(1, 3)) as $prod) {
                    $cant = rand(20, 200);
                    $sub = $cant * $prod->precio;
                    $total += $sub;
                    $pedido->items()->create([
                        'producto_id' => $prod->id,
                        'cantidad' => $cant,
                        'precio_unitario' => $prod->precio,
                        'subtotal' => $sub,
                    ]);
                }
                $pedido->update(['total' => $total]);
            }
        }

        // --- Órdenes de producción ---
        if (OrdenProduccion::count() === 0) {
            $empleadosDb = Empleado::all();
            $productosDb = Producto::all();
            $pedidosDb = Pedido::all();
            $etapas = ['corte', 'confeccion', 'acabado', 'control'];

            for ($i = 1; $i <= 8; $i++) {
                $etapa = $etapas[array_rand($etapas)];
                OrdenProduccion::create([
                    'codigo' => 'OP-'.str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                    'pedido_id' => $pedidosDb->random()->id,
                    'producto_id' => $productosDb->random()->id,
                    'empleado_id' => $empleadosDb->random()->id,
                    'cantidad' => rand(20, 150),
                    'etapa' => $etapa,
                    'avance' => rand(1, 19) * 5,
                    'estado' => 'en_proceso',
                    'fecha_inicio' => now()->subDays(rand(1, 10)),
                ]);
            }
        }

        // --- Ventas con IGV ---
        if (Venta::count() === 0) {
            $clientesDb = Cliente::all();
            $productosDb = Producto::all();
            $tipos = ['boleta', 'factura'];
            $estadosV = ['pagado', 'pagado', 'pendiente'];

            for ($i = 1; $i <= 5; $i++) {
                $venta = Venta::create([
                    'codigo' => 'VEN-'.str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                    'cliente_id' => $clientesDb->random()->id,
                    'fecha' => now()->subDays(rand(1, 20)),
                    'tipo_comprobante' => $tipos[array_rand($tipos)],
                    'estado' => $estadosV[array_rand($estadosV)],
                ]);
                $subtotal = 0;
                foreach ($productosDb->random(rand(1, 3)) as $prod) {
                    $cant = rand(5, 60);
                    $sub = $cant * $prod->precio;
                    $subtotal += $sub;
                    $venta->items()->create([
                        'producto_id' => $prod->id,
                        'cantidad' => $cant,
                        'precio_unitario' => $prod->precio,
                        'subtotal' => $sub,
                    ]);
                }
                $igv = round($subtotal * Venta::IGV, 2);
                $venta->update(['subtotal' => $subtotal, 'igv' => $igv, 'total' => $subtotal + $igv]);
            }
        }

        // --- Compras (pendientes, sin aplicar stock) ---
        if (Compra::count() === 0) {
            $proveedoresDb = Proveedor::all();
            $materiasDb = MateriaPrima::all();

            for ($i = 1; $i <= 4; $i++) {
                $compra = Compra::create([
                    'codigo' => 'COM-'.str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                    'proveedor_id' => $proveedoresDb->random()->id,
                    'fecha' => now()->subDays(rand(1, 25)),
                    'estado' => 'pendiente',
                    'stock_aplicado' => false,
                ]);
                $total = 0;
                foreach ($materiasDb->random(rand(1, 3)) as $mat) {
                    $cant = rand(50, 400);
                    $sub = $cant * $mat->costo_unitario;
                    $total += $sub;
                    $compra->items()->create([
                        'materia_prima_id' => $mat->id,
                        'cantidad' => $cant,
                        'costo_unitario' => $mat->costo_unitario,
                        'subtotal' => $sub,
                    ]);
                }
                $compra->update(['total' => $total]);
            }
        }

        // --- Configuración de la empresa ---
        Configuracion::updateOrCreate(['id' => 1], [
            'empresa' => 'Taller Textil Demo S.A.C.',
            'ruc' => '20481234567',
            'direccion' => 'Av. Industrial 1234, Lima',
            'telefono' => '(01) 555-1234',
            'email' => 'contacto@tallertextil.com',
            'moneda' => 'PEN',
            'igv' => 18,
        ]);

        // --- Movimientos de inventario demo ---
        if (MovimientoInventario::count() === 0) {
            $admin = User::where('email', 'admin@tallertextil.com')->first();
            foreach (MateriaPrima::all() as $mat) {
                MovimientoInventario::create([
                    'materia_prima_id' => $mat->id,
                    'tipo' => 'entrada',
                    'cantidad' => $mat->stock,
                    'stock_anterior' => 0,
                    'stock_resultante' => $mat->stock,
                    'motivo' => 'Stock inicial',
                    'user_id' => $admin?->id,
                    'fecha' => now()->subDays(rand(10, 40)),
                ]);
            }
        }
    }
}
