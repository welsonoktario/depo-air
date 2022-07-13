<x-app-layout>
  <x-slot name="header">
    <div class="inline-flex w-full items-center justify-between">
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        {{ __('Barang') }}
      </h2>

      <x-button-link href="{{ route('barang.create') }}">Tambah Barang</x-button-link>
    </div>
  </x-slot>

  <div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
      <div class="overflow-hidden bg-white shadow sm:rounded-lg">
        <div class="border-b border-gray-200 bg-white p-6">
          @if ($barangs->count())
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
              <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-700">
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
                    <tr class="border-b bg-white">
                      <td scope="row" class="whitespace-nowrap px-6 py-4 font-medium">{{ $barang->nama }}</td>
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
