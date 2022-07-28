<x-app-layout>
  <x-slot name="header">
    <div class="inline-flex items-center justify-between w-full">
      <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-white">
        {{ __('Kurir') }}
      </h2>

      <x-button-link href="{{ route('kurir.create') }}">Tambah Kurir</x-button-link>
    </div>
  </x-slot>

  <div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
      <div class="overflow-hidden bg-white shadow dark:bg-zinc-800 sm:rounded-lg">
        <div class="p-6 bg-white dark:bg-zinc-800">
          @if ($kurirs->count())
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
              <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-700 uppercase dark:text-white bg-gray-50 dark:bg-zinc-600">
                  <th scope="col" class="px-6 py-3">Nama</th>
                  <th scope="col" class="px-6 py-3">Telepon</th>
                  <th scope="col" class="px-6 py-3">Email</th>
                  <th scope="col" class="px-6 py-3">Alamat</th>
                </thead>
                <tbody>
                  @foreach ($kurirs as $kurir)
                    <tr class="bg-white border-b last:border-b-0 dark:bg-zinc-700 dark:border-zinc-600">
                      <td scope="row" class="px-6 py-4 font-medium whitespace-nowrap">
                        {{ $kurir->user->nama }}
                      </td>
                      <td class="px-6 py-4">{{ $kurir->user->telepon }}</td>
                      <td class="px-6 py-4">{{ $kurir->user->email }}</td>
                      <td class="px-6 py-4">{{ $kurir->alamat }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @else
            <p>Kosong</p>
          @endif
        </div>
      </div>
    </div>
  </div>

  @push('scripts')
    <script>
      Alpine.start();
    </script>
  @endpush
</x-app-layout>
