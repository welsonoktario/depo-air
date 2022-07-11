<x-app-layout>
  @push('styles')
    <link rel="stylesheet" href="{{ mix('css/mapbox.css') }}">
  @endpush

  <x-slot name="header">
    <div class="inline-flex items-center gap-4 w-full">
      <x-button type="button" @click="window.history.back()">Kembali</x-button>

      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Edit Depo') }}
      </h2>
    </div>
  </x-slot>

  <div x-data="depo" class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <form class="p-6 bg-white border-b border-gray-200" action="{{ route('depo.update', $depo->id) }}"
          method="POST">
          @csrf
          @method('PATCH')
          <p class="font-semibold text-lg mb-3">Detail Depo</p>
          <div class="mb-3 w-full">
            <x-label for="depo_nama">Nama Depo</x-label>
            <x-input value="{{ $depo->nama }}" name="depo_nama" type="text" required />
          </div>
          <div class="mb-3 w-full">
            <x-label for="depo_alamat">Alamat Depo</x-label>
            <x-input value="{{ $depo->alamat }}" name="depo_alamat" type="text" autocomplete="street-address"
              required />
          </div>
          <div class="flex flex-col h-96 mb-3 w-full">
            <x-label for="depo_alamat">Lokasi Depo</x-label>
            <div id="map" class="w-full h-full rounded-md"></div>
          </div>
          <div class="mb-6 w-full">
            <x-label for="depo_tipe">Tipe Cabang Depo</x-label>
            <x-select name="depo_tipe" required>
              <option value="Cabang Utama" @if ($depo->tipe_cabang == 'Cabang Utama') selected @endif>Cabang Utama</option>
              <option value="Cabang Pembantu" @if ($depo->tipe_cabang == 'Cabang Pembantu') selected @endif>Cabang Pembantu
              </option>
            </x-select>
          </div>

          <p class="font-semibold text-lg mb-3">Detail Admin</p>
          <div class="mb-3 w-full">
            <x-label for="user_nama">Nama Admin</x-label>
            <x-input value="{{ $depo->user->nama }}" name="user_nama" type="text" readonly />
          </div>
          <div class="mb-3 w-full">
            <x-label for="user_telepon">Telepon Depo</x-label>
            <x-input value="{{ $depo->user->telepon }}" name="user_telepon" type="tel" readonly />
          </div>
          <div class="mb-3 w-full">
            <x-label for="user_email">Email Admin</x-label>
            <x-input value="{{ $depo->user->email }}" name="user_email" type="email" readonly />
          </div>

          <div class="inline-flex justify-end w-full mt-3">
            <x-button class="bg-indigo-600 hover:bg-indigo-500">Simpan</x-button>
          </div>
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
