<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Historial de Ventas</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }

  body {
    font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
    font-size: 10px;
    color: #1a1a2e;
    background: #fff;
    padding: 28px 32px;
  }

  /* ── Header ── */
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
    color: #10b981;
    margin-bottom: 4px;
  }
  h1 {
    font-size: 20px;
    font-weight: 700;
    color: #0f0f1a;
    letter-spacing: -0.02em;
    line-height: 1;
  }
  .subtitle { font-size: 10px; color: #898aab; margin-top: 4px; }

  .meta-count { font-size: 28px; font-weight: 800; color: #10b981; line-height: 1; }
  .meta-label { font-size: 10px; color: #898aab; margin-bottom: 4px; }
  .meta-date  { font-size: 9.5px; color: #b0b0c8; margin-top: 3px; }

  /* ── Summary row ── */
  .summary {
    display: table;
    width: 100%;
    margin-bottom: 18px;
    background: #f8f8fc;
    border-radius: 6px;
    padding: 10px 16px;
  }
  .summary-item { display: table-cell; text-align: center; }
  .summary-item + .summary-item { border-left: 1px solid #e8e8f0; }
  .summary-value { font-size: 15px; font-weight: 700; color: #0f0f1a; }
  .summary-label { font-size: 8.5px; color: #898aab; margin-top: 2px; text-transform: uppercase; letter-spacing: 0.07em; }
  .summary-accent { color: #10b981; }

  /* ── Table ── */
  table { width: 100%; border-collapse: collapse; }
  thead tr { background: #f5f5fa; }
  thead th {
    font-size: 8.5px; font-weight: 700;
    letter-spacing: 0.1em; text-transform: uppercase;
    color: #898aab; padding: 9px 12px;
    text-align: left; border-bottom: 1.5px solid #e8e8f0;
  }
  thead th.right  { text-align: right; }
  thead th.center { text-align: center; }

  tbody tr { border-bottom: 1px solid #f0f0f7; }
  tbody tr:last-child { border-bottom: none; }
  tbody td { padding: 7px 12px; font-size: 9.5px; vertical-align: middle; }
  td.num   { font-size: 9px; color: #c0c0d8; }
  td.id    { font-weight: 700; font-family: monospace; color: #10b981; }
  td.right { text-align: right; }
  td.center{ text-align: center; }
  td.muted { color: #b0b0c8; }
  td.bold  { font-weight: 600; }
  td.price { font-variant-numeric: tabular-nums; font-weight: 500; }
  td.danger{ color: #e53e3e; }

  .badge {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 99px;
    font-size: 8.5px;
    font-weight: 700;
  }
  .badge-gray   { background: #f0f0f7; color: #5f5f80; }

  /* ── Footer ── */
  .footer {
    margin-top: 20px; padding-top: 12px; border-top: 1px solid #f0f0f7;
    display: table; width: 100%;
  }
  .footer-left  { display: table-cell; font-size: 8.5px; color: #b0b0c8; }
  .footer-right { display: table-cell; text-align: right; font-size: 8.5px; color: #b0b0c8; }
  .footer-accent { color: #10b981; font-weight: 700; }
</style>
</head>
<body>

  <div class="header">
    <div class="header-left">
      <div class="brand">XStock</div>
      <h1>Historial de Ventas</h1>
      <div class="subtitle">Registro completo exportado el {{ now()->translatedFormat('d \d\e F \d\e Y') }}</div>
    </div>
    <div class="header-right">
      <div class="meta-label">Total de ventas</div>
      <div class="meta-count">{{ str_pad($ventas->count(), 2, '0', STR_PAD_LEFT) }}</div>
      <div class="meta-date">{{ now()->format('d/m/Y · H:i') }}</div>
    </div>
  </div>

  {{-- Summary strip --}}
  @php
    $totalIngresos = $ventas->sum('total');
    $totalDescuentos = $ventas->sum('descuento_total');
    $totalItems = $ventas->sum(fn($v) => $v->detalles->sum('cantidad'));
  @endphp
  <div class="summary">
    <div class="summary-item">
      <div class="summary-value summary-accent">$ {{ number_format($totalIngresos, 0, ',', '.') }}</div>
      <div class="summary-label">Ingresos Totales</div>
    </div>
    <div class="summary-item">
      <div class="summary-value">{{ $totalItems }}</div>
      <div class="summary-label">Unidades Vendidas</div>
    </div>
    <div class="summary-item">
      <div class="summary-value">$ {{ number_format($totalDescuentos, 0, ',', '.') }}</div>
      <div class="summary-label">Descuentos Aplicados</div>
    </div>
    <div class="summary-item">
      <div class="summary-value">{{ $ventas->count() > 0 ? '$ ' . number_format($totalIngresos / $ventas->count(), 0, ',', '.') : '—' }}</div>
      <div class="summary-label">Ticket Promedio</div>
    </div>
  </div>

  <table>
    <thead>
      <tr>
        <th style="width:32px">#</th>
        <th>ID Venta</th>
        <th>Fecha</th>
        <th>Hora</th>
        <th class="center">Método de Pago</th>
        <th class="center">Items</th>
        <th class="right">Descuento</th>
        <th class="right">Total</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($ventas as $i => $v)
      <tr>
        <td class="num">{{ str_pad($i + 1, 3, '0', STR_PAD_LEFT) }}</td>
        <td class="id">#{{ str_pad($v->id, 5, '0', STR_PAD_LEFT) }}</td>
        <td>{{ $v->created_at->format('d/m/Y') }}</td>
        <td class="muted">{{ $v->created_at->format('H:i') }}</td>
        <td class="center">
          <span class="badge badge-gray">{{ $v->metodo_pago }}</span>
        </td>
        <td class="center bold">{{ $v->detalles->sum('cantidad') }}</td>
        <td class="right danger">
          {{ $v->descuento_total > 0 ? '- $ ' . number_format($v->descuento_total, 0, ',', '.') : '—' }}
        </td>
        <td class="right bold">$ {{ number_format($v->total, 0, ',', '.') }}</td>
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
