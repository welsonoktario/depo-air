<x-app-layout>
  <x-slot name="header">
    <div class="inline-flex items-center justify-between w-full">
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        {{ __('Depo') }}
      </h2>

      <x-button-link href="{{ route('depo.create') }}">Tambah Depo</x-button-link>
    </div>
  </x-slot>

  <div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
      <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          <div class="grid grid-cols-4 gap-8">
            @forelse ($depos as $depo)
              <div class="p-4 bg-indigo-400 shadow-sm sm:rounded-lg">
                <p class="text-lg font-semibold tracking-tighter">{{ $depo->nama }}</p>
                <div class="flex flex-row justify-start gap-2 mt-4">
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
