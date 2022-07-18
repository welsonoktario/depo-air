<x-app-layout>
  <x-slot name="header">
    <div class="inline-flex items-center w-full gap-4">
      <x-button type="button" onclick="window.history.back()">Kembali</x-button>

      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        {{ __('Edit Barang') }}
      </h2>
    </div>
  </x-slot>

  <div x-data="barang" class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
      <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
        <form class="p-6 bg-white border-b border-gray-200" action="{{ route('barang.update', $barang->id) }}"
          method="POST" enctype="multipart/form-data">
          @csrf
          @method('PATCH')
          <div class="w-full mb-3">
            <x-label for="nama">Nama Barang</x-label>
            <x-input value="{{ $barang->nama }}" name="nama" type="text" required />
          </div>
          <div class="w-full mb-3">
            <x-label for="deskripsi">Deskripsi Barang</x-label>
            <x-input value="{{ $barang->deskripsi }}" name="deskripsi" type="text" required />
          </div>
          <div class="w-full mb-3">
            <x-label for="harga">Harga Barang</x-label>
            <x-input value="{{ $barang->harga }}" name="harga" type="number" required />
          </div>
          <div class="w-full mb-3">
            <x-label for="satuan">Satuan Barang</x-label>
            <x-input value="{{ $barang->satuan }}" name="satuan" type="text" required />
          </div>
          <div class="w-full mb-3">
            <x-label for="min">Minimum Pembelian</x-label>
            <x-input value="{{ $barang->min_pembelian }}" name="min" type="number" min="1" required />
          </div>
          <div class="w-full mb-3">
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
          <div class="w-full mb-3">
            <x-label for="min">Foto Barang</x-label>
            <x-input name="foto" type="file" accept="image/*" x-ref="foto" @change="previewFoto" required />
          </div>

          <template x-if="imgsrc">
            <p>
              <img :src="imgsrc" class="w-1/4 mx-auto">
            </p>
          </template>

          <div class="inline-flex justify-end w-full mt-3">
            <x-button class="bg-indigo-600 hover:bg-indigo-500">Simpan</x-button>
          </div>
        </form>
      </div>
    </div>
  </div>

  @push('scripts')
    <script>
      Alpine.data('barang', () => ({
        barang: {{ Js::from($barang) }},
        imgsrc: null,
        init() {
          this.imgsrc = `/storage/${this.barang.gambar}`;
        },
        previewFoto() {
          let file = this.$refs.foto.files[0];
          if (!file || file.type.indexOf('image/') === -1) return;
          this.imgsrc = null;
          let reader = new FileReader();

          reader.onload = e => {
            this.imgsrc = e.target.result;
          }

          reader.readAsDataURL(file);
        },
      }));

      Alpine.start();
    </script>
  @endpush
</x-app-layout>
