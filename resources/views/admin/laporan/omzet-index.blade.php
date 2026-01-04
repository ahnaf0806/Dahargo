<x-admin-layout>

    @section('content')
    <div class="container py-3">
    <h1 class="h4 mb-3">Laporan Omzet</h1>

    <form method="GET" action="{{ route('admin.laporan.omzet.pdf') }}" class="card p-3">
        <div class="row g-3">
        <div class="col-md-3">
            <label class="form-label">Mode</label>
            <select name="mode" class="form-select" required>
            <option value="harian">Harian</option>
            <option value="bulanan">Bulanan</option>
            <option value="tahunan">Tahunan</option>
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label">Dari</label>
            <input type="date" name="start" class="form-control" required value="{{ now()->startOfMonth()->toDateString() }}">
        </div>

        <div class="col-md-3">
            <label class="form-label">Sampai</label>
            <input type="date" name="end" class="form-control" required value="{{ now()->toDateString() }}">
        </div>

        <div class="col-md-3 d-flex align-items-end">
            <button class="btn btn-primary w-100">Cetak PDF</button>
        </div>
        </div>
    </form>
    </div>
    @endsection
</x-admin-layout>
