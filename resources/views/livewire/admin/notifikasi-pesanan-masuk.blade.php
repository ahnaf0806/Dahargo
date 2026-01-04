<div wire:key="notif-pesanan"
     @if($enablePoll)
        wire:poll.5s.visible="muat"
     @endif
>
    <a href="{{ route('admin.pesanan.index') }}"
       class="relative inline-flex h-10 w-10 items-center justify-center rounded-full hover:bg-gray-100">
        <!-- icon -->
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 11-6 0h6z"/>
        </svg>

        @if($jumlah > 0)
            <span class="absolute -right-1 -top-1 rounded-full bg-red-600 px-2 py-0.5 text-[11px] font-bold text-white">
                {{ $jumlah }}
            </span>
        @endif
    </a>
</div>
