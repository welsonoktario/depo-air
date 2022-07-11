<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Customer') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          @if ($customers->count())
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
              <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                  <th scope="col" class="px-6 py-3">Nama</th>
                  <th scope="col" class="px-6 py-3">Telepon</th>
                  <th scope="col" class="px-6 py-3">Email</th>
                  <th scope="col" class="px-6 py-3">Alamat</th>
                </thead>
                <tbody>
                  @foreach ($customers as $customer)
                    <tr class="bg-white border-b">
                      <td scope="row" class="px-6 py-4 font-medium whitespace-nowrap">
                        {{ $customer->user->nama }}
                      </td>
                      <td class="px-6 py-4">{{ $customer->user->telepon }}</td>
                      <td class="px-6 py-4">{{ $customer->user->email }}</td>
                      <td class="px-6 py-4">
                        {{ $customer->alamats->count() ? $customer->alamats[0]->alamat : '-' }}
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
