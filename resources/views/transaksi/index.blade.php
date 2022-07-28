<x-app-layout>
  <x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-white">
      {{ __('Transaksi') }}
    </h2>
  </x-slot>

  <div class="py-12" x-data="transaksi">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
      <div class="overflow-hidden bg-white shadow-sm dark:bg-zinc-700 sm:rounded-lg">
        <div class="p-6 bg-white dark:bg-zinc-800">
          <div
            class="text-sm font-medium text-center text-gray-500 border-gray-200 dark:text-gray-400 dark:border-gray-700">
            <ul class="flex w-full -mb-px justify-evenly">
              <li class="w-full">
                <button @click="setSelected('Menunggu Pembayaran')"
                  class="inline-block w-full p-4 border-b-2 border-transparent rounded-t-lg"
                  :class="{
                      'text-indigo-500 border-b-2 border-indigo-500 rounded-t-lg active dark:text-indigo-400 dark:border-indigo-400': selectedStatus ==
                          'Menunggu Pembayaran',
                      'hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300': selectedStatus !=
                          'Menunggu Pembayaran'
                  }">Menunggu
                  Pembayaran</button>
              </li>
              <li class="w-full">
                <button @click="setSelected('Menunggu Konfirmasi')"
                  class="inline-block w-full p-4 border-b-2 border-transparent rounded-t-lg"
                  :class="{
                      'text-indigo-500 border-b-2 border-indigo-500 rounded-t-lg active dark:text-indigo-400 dark:border-indigo-400': selectedStatus ==
                          'Menunggu Konfirmasi',
                      'hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300': selectedStatus !=
                          'Menunggu Konfirmasi'
                  }">Menunggu
                  Konfirmasi</button>
              </li>
              <li class="w-full">
                <button @click="setSelected('Diproses')"
                  class="inline-block w-full p-4 border-b-2 border-transparent rounded-t-lg"
                  :class="{
                      'text-indigo-500 border-b-2 border-indigo-500 rounded-t-lg active dark:text-indigo-400 dark:border-indigo-400': selectedStatus ==
                          'Diproses',
                      'hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300': selectedStatus != 'Diproses'
                  }">Diproses</button>
              </li>
              <li class="w-full">
                <button @click="setSelected('Dikirim')"
                  class="inline-block w-full p-4 border-b-2 border-transparent rounded-t-lg"
                  :class="{
                      'text-indigo-500 border-b-2 border-indigo-500 rounded-t-lg active dark:text-indigo-400 dark:border-indigo-400': selectedStatus ==
                          'Dikirim',
                      'hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300': selectedStatus != 'Dikirim'
                  }">Dikirim</button>
              </li>
              <li class="w-full">
                <button @click="setSelected('Selesai')"
                  class="inline-block w-full p-4 border-b-2 border-transparent rounded-t-lg"
                  :class="{
                      'text-indigo-500 border-b-2 border-indigo-500 rounded-t-lg active dark:text-indigo-400 dark:border-indigo-400': selectedStatus ==
                          'Selesai',
                      'hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300': selectedStatus != 'Selesai'
                  }">Selesai</button>
              </li>
              <li class="w-full">
                <button @click="setSelected('Batal')"
                  class="inline-block w-full p-4 border-b-2 border-transparent rounded-t-lg"
                  :class="{
                      'text-indigo-500 border-b-2 border-indigo-500 rounded-t-lg active dark:text-indigo-400 dark:border-indigo-400': selectedStatus ==
                          'Batal',
                      'hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300': selectedStatus != 'Batal'
                  }">Batal</button>
              </li>
            </ul>

            <template x-if="!selected.length">
              <p class="mt-6">Belum ada transaksi</p>
            </template>

            <template x-if="selected.length">
              <div class="relative mt-6 overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left">
                  <thead class="text-xs text-gray-700 uppercase dark:text-white bg-gray-50 dark:bg-zinc-600">
                    <th scope="col" class="px-6 py-3">No</th>
                    <th scope="col" class="px-6 py-3">Tanggal</th>
                    <th scope="col" class="px-6 py-3">Nama Customer</th>
                    <th scope="col" class="px-6 py-3">Telepon</th>
                    <th scope="col" class="px-6 py-3">Email</th>
                    <th scope="col" class="px-6 py-3"></th>
                  </thead>
                  <tbody>
                    <template x-for="(transaksi, index) in selected">
                      <tr class="bg-white border-b last:border-b-0 dark:bg-zinc-700 dark:border-zinc-600">
                        <td scope="row" class="px-6 py-4 font-medium whitespace-nowrap" x-text="index + 1"></td>
                        <td class="px-6 py-4" x-text="tanggal(transaksi.created_at)"></td>
                        <td class="px-6 py-4" x-text="transaksi.customer.user.nama"></td>
                        <td class="px-6 py-4" x-text="transaksi.customer.user.telepon"></td>
                        <td class="px-6 py-4" x-text="transaksi.customer.user.email"></td>
                        <td>
                          <a class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-gray-800 border border-transparent rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25"
                            :href="route('transaksi.show', { transaksi: transaksi.id })">Detail</a>
                        </td>
                      </tr>
                    </template>
                  </tbody>
                </table>
              </div>
            </template>
          </div>
        </div>
      </div>
    </div>
  </div>

  @push('scripts')
    <script>
      Alpine.data("transaksi", () => ({
        transaksis: {
          "Menunggu Pembayaran": [],
          "Menunggu Konfirmasi": [],
          "Diproses": [],
          "Dikirim": [],
          "Selesai": [],
          "Batal": [],
        },
        selected: [],
        selectedStatus: "Menunggu Pembayaran",
        init() {
          const id = {{ auth()->user()->depo->id }};
          this.transaksis = {{ Js::from($transaksis) }};
          this.selected = this.transaksis['Menunggu Pembayaran'] || [];
          Echo.channel(`depo.${id}`)
            .listen("TransaksiCreated", (data) => this.transaksis['Menunggu Pembayaran'].unshift(data.transaksi));
        },
        createdAt(transaksi) {
          const date = new Date(transaksi.created_at);

          return date.toLocaleString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: 'numeric',
            minute: 'numeric',
            second: 'numeric',
            hour12: false
          });
        },
        setSelected(status) {
          this.selectedStatus = status;

          if (this.transaksis[status]) {
            this.selected = this.transaksis[status];
          } else {
            this.selected = [];
          }
        },
        tanggal(createdAt) {
          const date = createdAt.split('T')[0];
          const time = createdAt.split('T')[1].split('.')[0];

          return `${date} ${time}`;
        },
      }));

      Alpine.start();
    </script>
  @endpush

</x-app-layout>
