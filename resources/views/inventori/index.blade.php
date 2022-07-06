<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Inventori') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          @if ($barangsAll->count())
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
              <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                  <th scope="col" class="px-6 py-3">Nama</th>
                  <th scope="col" class="px-6 py-3">Harga</th>
                  <th scope="col" class="px-6 py-3">Kategori</th>
                  <th scope="col" class="px-6 py-3">Stok</th>
                  <th scope="col" class="px-6 py-3 w-20">
                    <span class="sr-only">Edit</span>
                  </th>
                </thead>
                <tbody>
                  @foreach ($barangsAll as $barang)
                    @if (in_array($barang->id, array_column($barangs->toArray(), 'id')))
                      @php
                        $b = $barangs->firstWhere('id', $barang->id);
                      @endphp
                      <tr class="bg-white border-b">
                        <td scope="row" class="px-6 py-4 font-medium whitespace-nowrap">{{ $b->nama }}</td>
                        <td class="px-6 py-4">@rupiah($b->harga)</td>
                        <td class="px-6 py-4">{{ $b->kategori->nama ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $b->pivot->stok }}</td>
                        <td class="px-6 py-4">
                          <x-button-link href="{{ route('inventori.edit', $b->id) }}">Edit</x-button-link>
                        </td>
                      </tr>
                    @else
                      <tr class="bg-white border-b">
                        <td scope="row" class="px-6 py-4 font-medium whitespace-nowrap">{{ $barang->nama }}</td>
                        <td class="px-6 py-4">@rupiah($barang->harga)</td>
                        <td class="px-6 py-4">{{ $barang->kategori->nama ?? '-' }}</td>
                        <td class="px-6 py-4">0</td>
                        <td class="px-6 py-4">
                          <x-button-link href="{{ route('inventori.edit', $barang->id) }}">Edit</x-button-link>
                        </td>
                      </tr>
                    @endif
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
