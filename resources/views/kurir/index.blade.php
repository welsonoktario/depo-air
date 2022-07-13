<x-app-layout>
  <x-slot name="header">
    <div class="inline-flex justify-between items-center w-full">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Kurir') }}
      </h2>

      <x-button-link href="{{ route('kurir.create') }}">Tambah Kurir</x-button-link>
    </div>
  </x-slot>

  <div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
      <div class="overflow-hidden bg-white shadow sm:rounded-lg">
        <div class="border-b border-gray-200 bg-white p-6">
          @if ($kurirs->count())
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
              <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-700">
                  <th scope="col" class="px-6 py-3">Nama</th>
                  <th scope="col" class="px-6 py-3">Telepon</th>
                  <th scope="col" class="px-6 py-3">Email</th>
                  <th scope="col" class="px-6 py-3">Alamat</th>
                </thead>
                <tbody>
                  @foreach ($kurirs as $kurir)
                    <tr class="border-b bg-white">
                      <td scope="row" class="whitespace-nowrap px-6 py-4 font-medium">
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
