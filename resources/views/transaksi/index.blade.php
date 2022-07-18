<x-app-layout>
  <x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800">
      {{ __('Transaksi') }}
    </h2>
  </x-slot>

  <div class="py-12" x-data="transaksi">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
      <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          <div class="grid grid-cols-4 gap-8">
            <template x-if="transaksis.length">
              <template x-for="(transaksi, index) in transaksis" :key="index">
                <div class="p-4 bg-indigo-200 shadow-sm sm:rounded-lg">
                  <p class="mb-4" x-text="createdAt(transaksi)"></p>
                  <p class="text-lg font-semibold tracking-tighter" x-text="transaksi.customer.user.nama"></p>
                  <div class="flex flex-row justify-start gap-2 mt-4">
                    <x-button-link class="text-xs" x-bind:href="route('transaksi.show', transaksi.id)">
                      Detail
                    </x-button-link>
                  </div>
                </div>
              </template>
            </template>

            <template x-if="!transaksis.length">
              <p>Belum ada transaksi</p>
            </template>
          </div>
        </div>
      </div>
    </div>
  </div>

  @push('scripts')
    <script>
      Alpine.data("transaksi", () => ({
        transaksis: [],
        init() {
          const id = {{ auth()->user()->depo->id }};
          this.transaksis = {{ Js::from($transaksis) }};
          Echo.channel(`depo.${id}`)
            .listen("TransaksiCreated", (data) => this.transaksis.unshift(data.transaksi));
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
        }
      }));

      Alpine.start();
    </script>
  @endpush

</x-app-layout>
