<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-900">Edit Profil</h2>
    </x-slot>

    <div class="max-w-xl space-y-4">
        @if(session('success'))
            <div class="rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">
                <ul class="list-disc pl-4">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.profile.update') }}"
              class="rounded-xl border bg-white p-6 shadow-sm space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="text-sm font-medium">Nama</label>
                <input name="name" value="{{ old('name', $user->name) }}"
                       class="mt-1 w-full rounded-lg border px-3 py-2 text-sm" />
            </div>

            <div>
                <label class="text-sm font-medium">Email</label>
                <input name="email" type="email"
                       value="{{ old('email', $user->email) }}"
                       class="mt-1 w-full rounded-lg border px-3 py-2 text-sm" />
            </div>

            <div>
                <label class="text-sm font-medium">
                    Password Baru <span class="text-gray-400">(opsional)</span>
                </label>
                <input name="password" type="password"
                       class="mt-1 w-full rounded-lg border px-3 py-2 text-sm" />
            </div>

            <div>
                <label class="text-sm font-medium">Konfirmasi Password</label>
                <input name="password_confirmation" type="password"
                       class="mt-1 w-full rounded-lg border px-3 py-2 text-sm" />
            </div>

            <div class="pt-2">
                <button class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
