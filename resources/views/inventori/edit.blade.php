<x-app-layout>
  <x-slot name="header">
    <div class="inline-flex items-center gap-4 w-full">
      <x-button type="button" onclick="window.history.back()">Kembali</x-button>

      <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
        {{ __('Edit Stok Barang') }}
      </h2>
    </div>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <form class="p-6 bg-white dark:bg-zinc-800" action="{{ route('inventori.update', $barang->id) }}"
          method="POST">
          @csrf
          @method('PATCH')
          <div class="mb-3 w-full">
            <x-label for="nama">Nama Barang</x-label>
            <x-input value="{{ $barang->nama }}" name="nama" type="text" required readonly />
          </div>
          <div class="mb-3 w-full">
            <x-label for="harga">Harga Barang</x-label>
            <x-input value="{{ $barang->harga }}" name="harga" type="number" required readonly />
          </div>
          <div class="mb-3 w-full">
            <x-label for="nama">Kategori Barang</x-label>
            <x-input value="{{ $barang->kategori->nama }}" name="nama" type="text" required readonly />
          </div>
          <div class="mb-3 w-full">
            <x-label for="stok">Stok Barang</x-label>
            <x-input value="{{ $barang->pivot ? $barang->pivot->stok : 0 }}" name="stok" type="number" required />
          </div>

          <div class="inline-flex justify-end w-full mt-3">
            <x-button class="bg-indigo-600 hover:bg-indigo-500">Simpan</x-button>
          </div>
        </form>
      </div>
    </div>
  </div>
</x-app-layout>
