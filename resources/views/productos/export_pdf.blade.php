<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Catálogo de Productos</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }

  body {
    font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
    font-size: 10px;
    color: #1a1a2e;
    background: #fff;
    padding: 28px 32px;
  }

  .header {
    display: table;
    width: 100%;
    margin-bottom: 22px;
    padding-bottom: 16px;
    border-bottom: 1.5px solid #e8e8f0;
  }
  .header-left  { display: table-cell; vertical-align: bottom; }
  .header-right { display: table-cell; vertical-align: bottom; text-align: right; }

  .brand {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: #6366f1;
    margin-bottom: 4px;
  }
  h1 {
    font-size: 20px;
    font-weight: 700;
    color: #0f0f1a;
    letter-spacing: -0.02em;
    line-height: 1;
  }
  .subtitle {
    font-size: 10px;
    color: #898aab;
    margin-top: 4px;
  }
  .meta-count {
    font-size: 28px;
    font-weight: 800;
    color: #6366f1;
    line-height: 1;
  }
  .meta-label {
    font-size: 10px;
    color: #898aab;
    margin-bottom: 4px;
  }
  .meta-date {
    font-size: 9.5px;
    color: #b0b0c8;
    margin-top: 3px;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 4px;
  }
  thead tr {
    background: #f5f5fa;
  }
  thead th {
    font-size: 8.5px;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: #898aab;
    padding: 9px 12px;
    text-align: left;
    border-bottom: 1.5px solid #e8e8f0;
  }
  thead th.right { text-align: right; }
  thead th.center { text-align: center; }

  tbody tr { border-bottom: 1px solid #f0f0f7; }
  tbody tr:last-child { border-bottom: none; }

  tbody td {
    padding: 8px 12px;
    font-size: 9.5px;
    color: #1a1a2e;
    vertical-align: middle;
  }
  tbody td.muted { color: #b0b0c8; }
  tbody td.right { text-align: right; }
  tbody td.center { text-align: center; }
  tbody td.num {
    font-size: 9px;
    color: #c0c0d8;
    font-variant-numeric: tabular-nums;
  }
  tbody td.bold { font-weight: 600; }
  tbody td.price { font-variant-numeric: tabular-nums; font-weight: 500; }

  .badge {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 99px;
    font-size: 8.5px;
    font-weight: 700;
    letter-spacing: 0.04em;
  }
  .badge-active   { background: #eef9f0; color: #1a9e3f; }
  .badge-inactive { background: #fdf0f0; color: #d9534f; }
  .stock-pill {
    display: inline-block;
    min-width: 26px;
    text-align: center;
    padding: 2px 7px;
    border-radius: 99px;
    font-size: 8.5px;
    font-weight: 700;
  }
  .stock-ok   { background: #f0f1fe; color: #6366f1; }
  .stock-warn { background: #fff8e6; color: #c07f00; }
  .stock-zero { background: #fdf0f0; color: #d9534f; }

  .footer {
    margin-top: 20px;
    padding-top: 12px;
    border-top: 1px solid #f0f0f7;
    display: table;
    width: 100%;
  }
  .footer-left  { display: table-cell; font-size: 8.5px; color: #b0b0c8; }
  .footer-right { display: table-cell; text-align: right; font-size: 8.5px; color: #b0b0c8; }
  .footer-accent { color: #6366f1; font-weight: 700; }
</style>
</head>
<body>

  <div class="header">
    <div class="header-left">
      <div class="brand">XStock</div>
      <h1>Catálogo de Productos</h1>
      <div class="subtitle">Inventario completo exportado el {{ now()->translatedFormat('d \d\e F \d\e Y') }}</div>
    </div>
    <div class="header-right">
      <div class="meta-label">Total de productos</div>
      <div class="meta-count">{{ str_pad($productos->count(), 2, '0', STR_PAD_LEFT) }}</div>
      <div class="meta-date">{{ now()->format('d/m/Y · H:i') }}</div>
    </div>
  </div>

  <table>
    <thead>
      <tr>
        <th style="width:32px">#</th>
        <th>Nombre</th>
        <th>Categoría</th>
        <th>Proveedor</th>
        <th class="right">Precio</th>
        <th class="center">Stock</th>
        <th class="center">Estado</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($productos as $i => $p)
      <tr>
        <td class="num">{{ str_pad($i + 1, 3, '0', STR_PAD_LEFT) }}</td>
        <td class="bold">{{ $p->nombre }}</td>
        <td>{{ $p->categoria ?? '—' }}</td>
        <td class="muted">{{ $p->proveedor->nombre ?? '—' }}</td>
        <td class="right price">$ {{ number_format($p->precio, 0, ',', '.') }}</td>
        <td class="center">
          @if ($p->stock == 0)
            <span class="stock-pill stock-zero">0</span>
          @elseif ($p->stock <= 5)
            <span class="stock-pill stock-warn">{{ $p->stock }}</span>
          @else
            <span class="stock-pill stock-ok">{{ $p->stock }}</span>
          @endif
        </td>
        <td class="center">
          @if ($p->estado === 'activo')
            <span class="badge badge-active">Activo</span>
          @else
            <span class="badge badge-inactive">Inactivo</span>
          @endif
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <div class="footer">
    <div class="footer-left"><span class="footer-accent">XStock</span> — Sistema de Gestión de Inventario</div>
    <div class="footer-right">Generado automáticamente · Confidencial</div>
  </div>

</body>
</html>
