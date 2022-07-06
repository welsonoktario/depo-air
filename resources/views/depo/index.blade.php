<x-app-layout>
  <x-slot name="header">
    <div class="inline-flex justify-between items-center w-full">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Depo') }}
      </h2>

      <x-button-link href="{{ route('depo.create') }}">Tambah Depo</x-button-link>
    </div>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          <div class="grid grid-cols-4 gap-8">
            @forelse ($depos as $depo)
              <div class="bg-indigo-400 p-4 shadow-sm sm:rounded-lg">
                <p class="font-semibold text-lg tracking-tighter">{{ $depo->nama }}</p>
                <div class="flex flex-row justify-start gap-2 mt-4">
                  <x-button-link class="text-xs" href="{{ route('depo.show', $depo->id) }}">Detail</x-button-link>
                  <x-button-link class="text-xs" href="{{ route('depo.edit', $depo->id) }}">Edit</x-button-link>
                </div>
              </div>
            @empty
              <p>Belum ada data depo</p>
            @endforelse
          </div>
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
