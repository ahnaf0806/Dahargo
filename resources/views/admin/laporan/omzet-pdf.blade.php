<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Laporan Omzet</title>
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
    .title { font-size: 18px; font-weight: bold; margin-bottom: 6px; }
    .meta { margin-bottom: 12px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #333; padding: 6px; }
    th { background: #f2f2f2; }
    .right { text-align: right; }
  </style>
</head>
<body>
  <div class="title">Laporan Omzet ({{ ucfirst($mode) }})</div>
  <div class="meta">
    Periode: {{ $start->format('d M Y') }} – {{ $end->format('d M Y') }}<br>
    Dicetak: {{ $dicetakPada->format('d M Y H:i') }}
  </div>

  <table>
    <thead>
      <tr>
        <th style="width: 45%;">Periode</th>
        <th style="width: 20%;" class="right">Transaksi</th>
        <th style="width: 35%;" class="right">Omzet</th>
      </tr>
    </thead>
    <tbody>
      @forelse($rows as $r)
        <tr>
          <td>{{ $r['periode'] }}</td>
          <td class="right">{{ number_format($r['transaksi']) }}</td>
          <td class="right">Rp {{ number_format($r['omzet'], 0, ',', '.') }}</td>
        </tr>
      @empty
        <tr><td colspan="3">Tidak ada data pada periode ini.</td></tr>
      @endforelse
    </tbody>
    <tfoot>
      <tr>
        <th class="right">TOTAL</th>
        <th class="right">{{ number_format($totalTransaksi) }}</th>
        <th class="right">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</th>
      </tr>
    </tfoot>
  </table>
</body>
</html>
