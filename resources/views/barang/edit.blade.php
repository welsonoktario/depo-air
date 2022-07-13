<x-app-layout>
  <x-slot name="header">
    <div class="inline-flex w-full items-center gap-4">
      <x-button type="button" onclick="window.history.back()">Kembali</x-button>

      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        {{ __('Edit Barang') }}
      </h2>
    </div>
  </x-slot>

  <div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
      <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
        <form class="border-b border-gray-200 bg-white p-6" action="{{ route('barang.update', $barang->id) }}"
          method="POST">
          @csrf
          @method('PATCH')
          <div class="mb-3 w-full">
            <x-label for="nama">Nama Barang</x-label>
            <x-input value="{{ $barang->nama }}" name="nama" type="text" required />
          </div>
          <div class="mb-3 w-full">
            <x-label for="deskripsi">Deskripsi Barang</x-label>
            <x-input value="{{ $barang->deskripsi }}" name="deskripsi" type="text" required />
          </div>
          <div class="mb-3 w-full">
            <x-label for="harga">Harga Barang</x-label>
            <x-input value="{{ $barang->harga }}" name="harga" type="number" required />
          </div>
          <div class="mb-3 w-full">
            <x-label for="satuan">Satuan Barang</x-label>
            <x-input value="{{ $barang->satuan }}" name="satuan" type="text" required />
          </div>
          <div class="mb-3 w-full">
            <x-label for="min">Minimum Pembelian</x-label>
            <x-input value="{{ $barang->min_pembelian }}" name="min" type="number" min="1" required />
          </div>
          <div class="mb-3 w-full">
            <x-label for="kategori">Kategori Barang</x-label>
            <x-select name="kategori" required>
              <option value="" selected disabled>Pilih kategori</option>
              @foreach ($kategoris as $kategori)
                <option value="{{ $kategori->id }}" @selected($kategori->id == $barang->kategori_id)>
                  {{ $kategori->nama }}
                </option>
              @endforeach
            </x-select>
          </div>

          <div class="mt-3 inline-flex w-full justify-end">
            <x-button class="bg-indigo-600 hover:bg-indigo-500">Simpan</x-button>
          </div>
        </form>
      </div>
    </div>
  </div>
</x-app-layout>
