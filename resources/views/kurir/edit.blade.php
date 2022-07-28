<x-app-layout>
  <x-slot name="header">
    <div class="inline-flex w-full items-center gap-4">
      <x-button type="button" onclick="window.history.back()">Kembali</x-button>

      <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-white">
        {{ __('Edit Kurir') }}
      </h2>
    </div>
  </x-slot>

  <div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
      <div class="overflow-hidden bg-white dark:bg-zinc-700 shadow-sm sm:rounded-lg">
        <form class="border-b border-gray-200 bg-white p-6" action="{{ route('kurir.update', $kurir->id) }}"
          method="POST">
          @csrf
          @method('PATCH')
          <div class="mb-3 w-full">
            <x-label for="nama">Nama</x-label>
            <x-input name="nama" type="text" value="{{ $kurir->user->nama }}" required />
          </div>
          <div class="mb-3 w-full">
            <x-label for="email">Email</x-label>
            <x-input name="email" type="email" value="{{ $kurir->user->email }}" required />
          </div>
          <div class="mb-3 w-full">
            <x-label for="telepon">Telepon</x-label>
            <x-input name="telepon" type="tel" value="{{ $kurir->user->telepon }}" required />
          </div>
          <div class="mb-3 w-full">
            <x-label for="alamat">Alamat</x-label>
            <x-input name="alamat" type="text" autocomplete="street-address" value="{{ $kurir->alamat }}"
              required />
          </div>

          <div class="mt-3 inline-flex w-full justify-end">
            <x-button class="bg-indigo-600 hover:bg-indigo-500">Simpan</x-button>
          </div>
        </form>
      </div>
    </div>
  </div>
</x-app-layout>
