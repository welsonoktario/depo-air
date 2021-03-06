<x-app-layout>
  <x-slot name="header">
    <div class="inline-flex items-center gap-4 w-full">
      <x-button type="button" @click="window.history.back()">Kembali</x-button>

      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Detail Depo') }}
      </h2>
    </div>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          Detail disini
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
