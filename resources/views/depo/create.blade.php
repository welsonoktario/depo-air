<x-app-layout>
  <x-slot name="header">
    <div class="inline-flex items-center gap-4 w-full">
      <x-button type="button" @click="window.history.back()">Kembali</x-button>

      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Tambah Depo') }}
      </h2>
    </div>
  </x-slot>

  <div class="py-12">
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
          <div class="mb-3 w-full">
            <x-label for="depo_lokasi_lat">Lokasi Depo (Lat)</x-label>
            <x-input name="depo_lokasi_lat" type="text" required />
          </div>
          <div class="mb-6 w-full">
            <x-label for="depo_lokasi_long">Lokasi Depo (Long)</x-label>
            <x-input name="depo_lokasi_long" type="text" required />
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
</x-app-layout>
