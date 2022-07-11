<x-app-layout>
  @push('styles')
    <link rel="stylesheet" href="{{ mix('css/mapbox.css') }}">
  @endpush

  <x-slot name="header">
    <div class="inline-flex items-center gap-4 w-full">
      <x-button type="button" @click="window.history.back()">Kembali</x-button>

      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Tambah Depo') }}
      </h2>
    </div>
  </x-slot>

  <div x-data="depo" class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <form class="p-6 bg-white border-b border-gray-200" action="{{ route('depo.store') }}" method="POST">
          @csrf
          <p class="font-semibold text-lg mb-3">Detail Depo</p>
          <div class="mb-3 w-full">
            <x-label for="depo_nama">Nama Depo</x-label>
            <x-input name="depo_nama" type="text" required />
          </div>
          <div class="mb-3 w-full">
            <x-label for="depo_alamat">Alamat Depo</x-label>
            <x-input name="depo_alamat" type="text" autocomplete="street-address" required />
          </div>
          <div class="flex flex-col h-96 mb-3 w-full">
            <x-label for="depo_alamat">Lokasi Depo</x-label>
            <div id="map" class="w-full h-full rounded-md"></div>
          </div>
          <div class="hidden d-none">
            <input type="text" name="depo_lokasi_lat" :value="lokasi.lat">
            <input type="text" name="depo_lokasi_long" :value="lokasi.lng">
          </div>
          <div class="mb-6 w-full">
            <x-label for="depo_tipe">Tipe Cabang Depo</x-label>
            <x-select name="depo_tipe" required>
              <option value="Cabang Utama">Cabang Utama</option>
              <option value="Cabang Pembantu" selected>Cabang Pembantu
              </option>
            </x-select>
          </div>

          <p class="font-semibold text-lg mb-3">Detail Admin</p>
          <div class="mb-3 w-full">
            <x-label for="user_nama">Nama Admin</x-label>
            <x-input name="user_nama" type="text" required />
          </div>
          <div class="mb-3 w-full">
            <x-label for="user_email">Email Admin</x-label>
            <x-input name="user_email" type="email" required />
          </div>
          <div class="mb-3 w-full">
            <x-label for="user_telepon">Telepon Depo</x-label>
            <x-input name="user_telepon" type="tel" required />
          </div>
          <div class="mb-3 w-full">
            <x-label for="user_password">Password Admin</x-label>
            <x-input name="user_password" type="password" required />
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
        map: null,
        geocoder: null,
        marker: null,
        lokasi: {
          lat: 0,
          lng: 0
        },
        init() {
          this.map = new mapboxgl.Map({
            container: 'map', // container ID
            style: 'mapbox://styles/mapbox/streets-v11', // style URL
            center: [-74.5, 40], // starting position [lng, lat]
            zoom: 9, // starting zoom
          });

          this.geocoder = new MapboxGeocoder({
            accessToken: mapboxgl.accessToken,
            marker: false
          });

          this.marker = new mapboxgl.Marker();

          this.map.on('load', () => {
            this.map.resize();
          });

          this.map.addControl(this.geocoder);

          this.map.on('click', (e) => {
            this.map.flyTo({
              center: e.lngLat,
              essential: true,
              zoom: 16,
            });
            this.lokasi.lat = e.lngLat.lat;
            this.lokasi.lng = e.lngLat.lng;

            if (this.marker.getLngLat()) {
              this.marker.setLngLat(e.lngLat);
            } else {
              this.marker.setLngLat(e.lngLat)
                .addTo(this.map);
            }
          });

          this.geocoder.on('result', (e) => {
            const latLng = e.result.center;
            this.lokasi.lat = latLng[1];
            this.lokasi.lng = latLng[0];
            if (this.marker.getLngLat()) {
              this.marker.setLngLat(latLng);
            } else {
              this.marker.setLngLat(latLng)
                .addTo(this.map);
            }
          });
        }
      }));

      Alpine.start();
    </script>
  @endpush
</x-app-layout>
