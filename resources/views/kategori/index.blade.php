<x-app-layout>
  <x-slot name="header">
    <div class="inline-flex justify-between items-center w-full">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Kategori') }}
      </h2>

      <x-button-link href="{{ route('kategori.create') }}">Tambah Kategori</x-button-link>
    </div>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          @if ($kategoris->count())
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
              <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                  <th scope="col" class="px-6 py-3">Nama</th>
                  <th scope="col" class="px-6 py-3 w-20">
                    <span class="sr-only">Edit</span>
                  </th>
                </thead>
                <tbody>
                  @foreach ($kategoris as $kategori)
                    <tr class="bg-white border-b">
                      <td scope="row" class="px-6 py-4 font-medium whitespace-nowrap">{{ $kategori->nama }}</td>
                      <td class="px-6 py-4">
                        <x-button-link href="{{ route('kategori.edit', $kategori->id) }}">Edit</x-button-link>
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
