<x-app-layout>
  <x-slot name="header">
    <div class="inline-flex items-center gap-4 w-full">
      <x-button type="button" onclick="window.history.back()">Kembali</x-button>

      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Tambah Barang') }}
      </h2>
    </div>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <form class="p-6 bg-white border-b border-gray-200" action="{{ route('barang.store') }}" method="POST">
          @csrf
          <div class="mb-3 w-full">
            <x-label for="nama">Nama Barang</x-label>
            <x-input name="nama" type="text" required />
          </div>
          <div class="mb-3 w-full">
            <x-label for="harga">Harga Barang</x-label>
            <x-input name="harga" type="number" required />
          </div>
          <div class="mb-3 w-full">
            <x-label for="kategori">Kategori Barang</x-label>
            <x-select name="kategori" required>
              <option value="" selected disabled>Pilih kategori</option>
              @foreach ($kategoris as $kategori)
                <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
              @endforeach
            </x-select>
          </div>

          <div class="inline-flex justify-end w-full mt-3">
            <x-button class="bg-indigo-600 hover:bg-indigo-500">Tambah</x-button>
          </div>
        </form>
      </div>
    </div>
  </div>
</x-app-layout>
