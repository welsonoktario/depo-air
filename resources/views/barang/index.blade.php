<x-app-layout>
  <x-slot name="header">
    <div class="inline-flex items-center justify-between w-full">
      <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-white">
        {{ __('Barang') }}
      </h2>

      <x-button-link href="{{ route('barang.create') }}">Tambah Barang</x-button-link>
    </div>
  </x-slot>

  <div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
      <div class="overflow-hidden bg-white shadow dark:bg-zinc-800 sm:rounded-lg">
        <div class="p-6 bg-white dark:bg-zinc-800 dark:text-white">
          @if ($barangs->count())
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
              <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-700 uppercase dark:text-white bg-gray-50 dark:bg-zinc-600">
                  <th scope="col" class="px-6 py-3">Nama</th>
                  <th scope="col" class="px-6 py-3">Harga</th>
                  <th scope="col" class="px-6 py-3">Satuan</th>
                  <th scope="col" class="px-6 py-3">Min. Pembelian</th>
                  <th scope="col" class="px-6 py-3">Kategori</th>
                  <th scope="col" class="w-20 px-6 py-3">
                    <span class="sr-only">Edit</span>
                  </th>
                </thead>
                <tbody>
                  @foreach ($barangs as $barang)
                    <tr class="bg-white border-b last:border-b-0 dark:bg-zinc-700">
                      <td scope="row" class="px-6 py-4 font-medium whitespace-nowrap">{{ $barang->nama }}</td>
                      <td class="px-6 py-4">@rupiah($barang->harga)</td>
                      <td class="px-6 py-4">{{ $barang->satuan }}</td>
                      <td class="px-6 py-4">{{ $barang->min_pembelian }}</td>
                      <td class="px-6 py-4">{{ $barang->kategori->nama ?? '-' }}</td>
                      <td class="px-6 py-4">
                        <x-button-link href="{{ route('barang.edit', $barang->id) }}">Edit</x-button-link>
                      </td>
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
