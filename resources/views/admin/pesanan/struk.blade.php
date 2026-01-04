@php
  function rp($n) { return 'Rp '.number_format((float)$n, 0, ',', '.'); }

  $items = $pesanan->item ?? collect();

  // Hitung subtotal dari item (lebih “jujur” daripada total jika ada mismatch)
  $calcTotal = $items->sum(function($it){
      $qty = (int)($it->jumlah ?? $it->qty ?? 0);
      $harga = (float)($it->harga_snapshot ?? $it->harga_satuan ?? $it->menu?->harga ?? 0);
      $line = $it->total_baris ?? $it->subtotal ?? ($qty * $harga);
      return (float)$line;
  });

  // Total final pakai pesanan->total kalau ada, kalau tidak pakai hasil kalkulasi
  $grand = (float)($pesanan->total ?? 0);
  if ($grand <= 0) $grand = (float)$calcTotal;

  $bayar = $pesanan->status_pembayaran ?? ($pesanan->pembayaran?->status ?? '-');
@endphp

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Struk #{{ $pesanan->id }}</title>
  <style>
    body { font-family: Arial, sans-serif; font-size: 12px; }
    .wrap { width: 300px; margin: 0 auto; }
    .center { text-align: center; }
    .line { border-top: 1px dashed #000; margin: 8px 0; }
    table { width: 100%; border-collapse: collapse; }
    td { padding: 2px 0; vertical-align: top; }
    .right { text-align: right; }
    .muted { color: #444; font-size: 11px; }
    @media print { .no-print { display: none; } }
  </style>
</head>
<body>
  <div class="wrap">
    <div class="center">
      <div><b>DAHARGO RESTO</b></div>
      <div>Struk Pesanan</div>
    </div>

    <div class="line"></div>

    <div>
      <div>No: <b>#{{ $pesanan->id }}</b></div>
      <div>Meja: {{ $pesanan->meja?->nama ?? '-' }}</div>
      <div>Tanggal: {{ optional($pesanan->waktu_pesan ?? $pesanan->created_at)->format('d/m/Y H:i') }}</div>
      <div>Status: {{ $pesanan->status }}</div>
      <div>Bayar: {{ $bayar }}</div>
    </div>

    <div class="line"></div>

    <table>
      @foreach($items as $it)
        @php
          $nama  = $it->nama_menu_snapshot ?? $it->menu?->nama ?? 'Item';
          $qty   = (int)($it->jumlah ?? $it->qty ?? 0);
          $harga = (float)($it->harga_snapshot ?? $it->harga_satuan ?? $it->menu?->harga ?? 0);
          $line  = (float)($it->total_baris ?? $it->subtotal ?? ($qty * $harga));
        @endphp
        <tr>
          <td>
            <div><b>{{ $nama }}</b></div>
            <div class="muted">{{ $qty }} x {{ rp($harga) }}</div>
          </td>
          <td class="right">{{ rp($line) }}</td>
        </tr>
      @endforeach
    </table>

    <div class="line"></div>

    {{-- Jika mau transparan: tampilkan total hitung & total pesanan bila beda --}}
    @if((float)$pesanan->total > 0 && abs((float)$pesanan->total - (float)$calcTotal) >= 1)
      <div class="muted">Subtotal item: {{ rp($calcTotal) }}</div>
    @endif

    <table>
      <tr>
        <td><b>Total</b></td>
        <td class="right"><b>{{ rp($grand) }}</b></td>
      </tr>
    </table>

    <div class="line"></div>

    <div class="center">Terima kasih 🙏</div>

    <div class="no-print center" style="margin-top:10px;">
      <button onclick="window.print()">Print</button>
      <button onclick="window.close()">Tutup</button>
    </div>
  </div>
</body>
</html>
