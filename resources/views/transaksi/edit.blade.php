<x-app-layout>
  <x-slot name="header">
    <div class="inline-flex w-full items-center gap-4">
      <x-button type="button" @click="window.history.back()">Kembali</x-button>

      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        {{ __('Edit Transaksi') }}
      </h2>
    </div>
  </x-slot>

  <div x-data="detail" class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
      <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
        <div class="border-b border-gray-200 bg-white p-6">
          <h6 class="mb-4 text-xl font-bold">Detail</h6>
          <div class="inline-flex w-full justify-start">
            <span>ID Transaksi:</span>
            <span class="ml-2" x-text="transaksi.id"></span>
          </div>
          <div class="inline-flex w-full justify-start">
            <span>Tanggal Transaksi:</span>
            <span class="ml-2" x-text="tanggal()"></span>
          </div>
          <div class="inline-flex w-full items-center justify-start">
            <span>Kurir:</span>
            <template x-if="transaksi.kurir">
              <span class="ml-2" x-text="transaksi.kurir.user.nama"></span>
            </template>
            <template x-if="!transaksi.kurir">
              <x-select class="ml-4 w-auto" x-model="kurir" name="kurir" required>
                <option value="null" selected disabled>Pilih kurir</option>
                <template x-for="kurir in kurirs">
                  <option :value="kurir.id" x-text="kurir.user.nama"></option>
                </template>
              </x-select>
            </template>
          </div>
          <div class="inline-flex w-full justify-start">
            <span>Status:</span>
            <span class="ml-2" x-text="transaksi.status"
              :class="{
                  'text-yellow-500': transaksi.status == 'Menunggu Pembayaran',
                  'text-indigo-500': transaksi.status == 'Diproses',
                  'text-blue-500': transaksi.status == 'Dikirim',
                  'text-green-500': transaksi.status == 'Selesai',
              }"></span>
          </div>
          <template x-if="transaksi.status == 'Selesai'">
            <div class="inline-flex w-full justify-start">
              <span>Ulasan Transaksi:</span>
              <span class="ml-2" x-text="ulasan()"></span>
            </div>
          </template>
          <div class="mt-2 inline-flex w-full justify-start">
            <p>Bukti Pembayaran:</span>
              <template x-if="transaksi.bukti_pembayaran">
                <img class="w-1/4 rounded-md" :src="bukti()" />
              </template>
              <template x-if="!transaksi.bukti_pembayaran">
                <span>Customer belum mengirim bukti pembayaran</span>
              </template>
          </div>

          <h6 class="mt-6 mb-4 text-xl font-bold">Barang</h6>
          <div class="container-sm mx-auto flex flex-col">
            <template x-for="(barang, index) in transaksi.barangs">
              <div class="inline-flex border" :key="index">
                <span x-text="index+1" class="border-r p-2"></span>
                <span x-text="barang.nama" class="flex-grow border-r p-2"></span>
                <span class="flex-shrink p-2" x-text="subtotal(barang)"></span>
              </div>
            </template>
            <p class="mt-4">
              Total:
              <span x-text="total()"></span>
            </p>
          </div>

          <template x-if="transaksi.status == 'Menunggu Pembayaran'">
            <div class="mt-6 inline-flex w-full">
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
          if (!this.kurir || this.kurir == 'null') {
            alert('Pilih kurir');
            return;
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
