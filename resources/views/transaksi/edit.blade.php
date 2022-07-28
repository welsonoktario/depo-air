<x-app-layout>
  <x-slot name="header">
    <div class="inline-flex items-center w-full gap-4">
      <x-button type="button" @click="window.history.back()">Kembali</x-button>

      <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-white">
        {{ __('Edit Transaksi') }}
      </h2>
    </div>
  </x-slot>

  <div x-data="detail" class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
      <div class="overflow-hidden bg-white shadow-sm dark:bg-zinc-700 sm:rounded-lg">
        <div class="p-6 bg-white dark:bg-zinc-800">
          <h6 class="mb-4 text-xl font-bold">Detail</h6>
          <div class="inline-flex justify-start w-full">
            <span>ID Transaksi:</span>
            <span class="ml-2" x-text="transaksi.id"></span>
          </div>
          <div class="inline-flex justify-start w-full">
            <span>Tanggal Transaksi:</span>
            <span class="ml-2" x-text="tanggal()"></span>
          </div>
          <div class="inline-flex items-center justify-start w-full">
            <span>Kurir:</span>
            <template x-if="transaksi.kurir">
              <span class="ml-2" x-text="transaksi.kurir.user.nama"></span>
            </template>
            <template x-if="!transaksi.kurir">
              <x-select class="w-auto ml-4" x-model="kurir" name="kurir" required>
                <option value="null" selected disabled>Pilih kurir</option>
                <template x-for="kurir in kurirs">
                  <option :value="kurir.id" x-text="kurir.user.nama"></option>
                </template>
              </x-select>
            </template>
          </div>
          <div class="inline-flex justify-start w-full">
            <span>Status:</span>
            <span class="ml-2" x-text="transaksi.status"
              :class="{
                  'text-yellow-500': transaksi.status == 'Menunggu Pembayaran',
                  'text-indigo-500': transaksi.status == 'Diproses',
                  'text-blue-500': transaksi.status == 'Dikirim',
                  'text-green-500': transaksi.status == 'Selesai',
                  'text-rose-500': transaksi.status == 'Batal',
              }"></span>
          </div>
          <template x-if="transaksi.status == 'Selesai'">
            <div class="inline-flex justify-start w-full">
              <span>Ulasan Transaksi:</span>
              <span class="ml-2" x-text="ulasan()"></span>
            </div>
          </template>
          <div class="inline-flex justify-start w-full mt-2">
            <p>Bukti Pembayaran:</span>
              <template x-if="transaksi.bukti_pembayaran">
                <img class="w-1/4 rounded-md" :src="bukti()" />
              </template>
              <template x-if="!transaksi.bukti_pembayaran">
                <span>Customer belum mengirim bukti pembayaran</span>
              </template>
          </div>

          <h6 class="mt-6 mb-4 text-xl font-bold">Barang</h6>
          <div class="flex flex-col mx-auto container-sm">
            <template x-for="(barang, index) in transaksi.barangs">
              <div class="inline-flex border" :key="index">
                <span x-text="index+1" class="p-2 border-r"></span>
                <span x-text="barang.nama" class="flex-grow p-2 border-r"></span>
                <span class="flex-shrink p-2" x-text="subtotal(barang)"></span>
              </div>
            </template>
            <p class="mt-4">
              Total:
              <span x-text="total()"></span>
            </p>
          </div>

          <template x-if="transaksi.status == 'Menunggu Konfirmasi'">
            <div class="inline-flex w-full mt-6">
              <x-button class="mr-2 bg-rose-600" type="button" @click="proses('Batal')">Batal</x-button>
              <x-button type="button" @click="proses('Diproses')">Proses</x-button>
            </div>
          </template>
        </div>
      </div>
    </div>
  </div>

  @push('scripts')
    <script>
      Alpine.data("detail", () => ({
        transaksi: {{ Js::from($transaksi) }},
        kurirs: {{ Js::from($kurirs) }},
        kurir: null,
        async proses(aksi) {
          if (aksi != 'Batal') {
            if (!this.kurir || this.kurir == 'null') {
              alert('Pilih kurir');
              return;
            }
          }

          const url = route("transaksi.update", {
            transaksi: this.transaksi.id,
          });
          const res = await fetch(url, {
            headers: {
              Accept: "application/json",
              "Content-Type": "application/json",
              "X-Requested-With": "XMLHttpRequest",
              "X-CSRF-Token": "{{ csrf_token() }}",
            },
            method: "PATCH",
            body: JSON.stringify({
              aksi,
              ...(this.kurir && this.kurir != 'null' ? {
                kurir: this.kurir
              } : null)
            }),
          });
          const {
            status
          } = await res.json();

          if (status == "OK") {
            this.transaksi.status = aksi;
          }

          if (aksi == 'Diproses') {
            const k = this.kurirs.find(ku => ku.id == this.kurir);
            this.transaksi.kurir = k
          }
        },
        tanggal() {
          const date = new Date(this.transaksi.tanggal);
          return date.toLocaleString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
          });
        },
        subtotal(barang) {
          const sub = (barang.harga * barang.pivot.jumlah).toLocaleString('id-ID');

          return `x${barang.pivot.jumlah} @ Rp ${sub}`;
        },
        total() {
          const tot = this.transaksi.barangs.reduce((prev, next) => prev + (next.harga * next.pivot.jumlah), 0);

          return 'Rp ' + tot.toLocaleString('id-ID');
        },
        bukti() {
          return '/storage/' + this.transaksi.bukti_pembayaran;
        },
        ulasan() {
          return this.transaksi.ulasan ? this.transaksi.ulasan : '-';
        }
      }));

      Alpine.start();
    </script>
  @endpush
</x-app-layout>
