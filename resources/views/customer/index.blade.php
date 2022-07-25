<x-app-layout>
  <x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800">
      {{ __('Customer') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
      <div class="overflow-hidden bg-white shadow sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          @if ($customers->count())
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
              <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                  <th scope="col" class="px-6 py-3">Nama</th>
                  <th scope="col" class="px-6 py-3">Telepon</th>
                  <th scope="col" class="px-6 py-3">Email</th>
                  <th scope="col" class="px-6 py-3">Alamat</th>
                  <th scope="col"></th>
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
                      <td class="px-6 py-4">
                        <form action="{{ route('customer.destroy', $customer->id) }}" method="POST">
                          @method('DELETE')
                          @csrf
                          <x-button class="bg-rose-600 hover:bg-rose-500">Hapus</x-button>
                        </form>
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
