<x-app-layout>
  @push('styles')
    <link rel="stylesheet" href="{{ secure_asset(mix('css/mapbox.css')) }}">
  @endpush

  <x-slot name="header">
    <div class="inline-flex items-center w-full gap-4">
      <x-button type="button" @click="window.history.back()">Kembali</x-button>

      <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-white">
        {{ __('Edit Depo') }}
      </h2>
    </div>
  </x-slot>

  <div x-data="depo" class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
      <div class="overflow-hidden bg-white dark:bg-zinc-700 shadow-sm sm:rounded-lg">
        <form id="editForm" class="p-6 bg-white dark:bg-zinc-800" action="{{ route('depo.update', $depo->id) }}"
          method="POST">
          @csrf
          @method('PATCH')
          <p class="mb-3 text-lg font-semibold">Detail Depo</p>
          <div class="w-full mb-3">
            <x-label for="depo_nama">Nama Depo</x-label>
            <x-input value="{{ $depo->nama }}" name="depo_nama" type="text" required />
          </div>
          <div class="w-full mb-3">
            <x-label for="depo_alamat">Alamat Depo</x-label>
            <x-input value="{{ $depo->alamat }}" name="depo_alamat" type="text" autocomplete="street-address"
              required />
          </div>
          <div class="flex flex-col w-full mb-3 h-96">
            <x-label for="depo_alamat">Lokasi Depo</x-label>
            <div id="map" class="w-full h-full rounded-md"></div>
          </div>
          <div class="w-full mb-6">
            <x-label for="depo_tipe">Tipe Cabang Depo</x-label>
            <x-select name="depo_tipe" required>
              <option value="Cabang Utama" @if ($depo->tipe_cabang == 'Cabang Utama') selected @endif>Cabang Utama
              </option>
              <option value="Cabang Pembantu" @if ($depo->tipe_cabang == 'Cabang Pembantu') selected @endif>Cabang
                Pembantu
              </option>
            </x-select>
          </div>

          <p class="mb-3 text-lg font-semibold">Detail Admin</p>
          <div class="w-full mb-3">
            <x-label for="user_nama">Nama Admin</x-label>
            <x-input value="{{ $depo->user->nama }}" name="user_nama" type="text" readonly />
          </div>
          <div class="w-full mb-3">
            <x-label for="user_telepon">Telepon Depo</x-label>
            <x-input value="{{ $depo->user->telepon }}" name="user_telepon" type="tel" readonly />
          </div>
          <div class="w-full mb-3">
            <x-label for="user_email">Email Admin</x-label>
            <x-input value="{{ $depo->user->email }}" name="user_email" type="email" readonly />
          </div>

          <div class="inline-flex justify-between w-full mt-3">
            <input type="submit" form="deleteForm" value="Hapus"
              class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 bg-rose-600 hover:bg-rose-500" />
            <input type="submit" form="editForm" value="Simpan"
              class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 bg-indigo-600 hover:bg-indigo-500" />
          </div>
        </form>
        <form id="deleteForm" action="{{ route('depo.destroy', $depo->id) }}" method="POST">
          @method('DELETE')
          @csrf
        </form>
      </div>
    </div>
  </div>

  @push('scripts')
    <script>
      Alpine.data('depo', () => ({
        depo: {{ Js::from($depo) }},
        init() {
          const map = new mapboxgl.Map({
            container: 'map', // container ID
            style: 'mapbox://styles/mapbox/streets-v11', // style URL
            center: this.depo.lokasi.coordinates, // starting position [lng, lat]
            zoom: 16, // starting zoom
          });

          const marker = new mapboxgl.Marker()
            .setLngLat(this.depo.lokasi.coordinates)
            .addTo(map);

          map.on('load', () => {
            map.resize();
          });
        }
      }));

      Alpine.start();
    </script>
  @endpush
</x-app-layout>
