<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 dark:border-zinc-700 dark:bg-zinc-800">
  <!-- Primary Navigation Menu -->
  <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <div class="flex justify-between h-16">
      <div class="flex">
        <!-- Logo -->
        <div class="flex items-center shrink-0">
          <a href="{{ route('dashboard') }}">
            <x-application-logo class="block w-auto h-10 text-gray-600 fill-current dark:text-gray-500" />
          </a>
        </div>

        <!-- Navigation Links -->
        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
          <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            {{ __('Dashboard') }}
          </x-nav-link>

          <x-nav-link :href="route('customer.index')" :active="request()->routeIs('customer.index')">
            {{ __('Customer') }}
          </x-nav-link>

          @if (auth()->user()->role === 'Super Admin')
            <x-nav-link :href="route('kategori.index')" :active="request()->routeIs('kategori.index')">
              {{ __('Kategori') }}
            </x-nav-link>

            <x-nav-link :href="route('barang.index')" :active="request()->routeIs('barang.index')">
              {{ __('Barang') }}
            </x-nav-link>

            <x-nav-link :href="route('depo.index')" :active="request()->routeIs('depo.index')">
              {{ __('Depo') }}
            </x-nav-link>
          @endif

          @if (auth()->user()->role === 'Admin')
            <x-nav-link :href="route('kurir.index')" :active="request()->routeIs('kurir.index')">
              {{ __('Kurir') }}
            </x-nav-link>

            <x-nav-link :href="route('inventori.index')" :active="request()->routeIs('inventori.index')">
              {{ __('Inventori') }}
            </x-nav-link>

            <x-nav-link :href="route('transaksi.index')" :active="request()->routeIs('transaksi.index')">
              {{ __('Transaksi') }}
            </x-nav-link>
          @endif
        </div>
      </div>

      <!-- Settings Dropdown -->
      <div class="hidden sm:ml-6 sm:flex sm:items-center">
        <x-dropdown align="right" width="48">
          <x-slot name="trigger">
            <button
              class="flex items-center text-sm font-medium text-gray-500 transition duration-150 ease-in-out dark:text-white hover:border-gray-300 hover:text-gray-400 focus:border-gray-300 focus:text-gray-700 focus:outline-none">
              <div>{{ Auth::user()->name }}</div>

              <div class="ml-1">
                <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                  <path fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd" />
                </svg>
              </div>
            </button>
          </x-slot>

          <x-slot name="content">
            <!-- Authentication -->
            <form method="POST" action="{{ route('logout') }}">
              @csrf

              <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                {{ __('Log Out') }}
              </x-dropdown-link>
            </form>
          </x-slot>
        </x-dropdown>
      </div>

      <!-- Hamburger -->
      <div class="flex items-center -mr-2 sm:hidden">
        <button @click="open = ! open"
          class="inline-flex items-center justify-center p-2 text-gray-400 transition duration-150 ease-in-out rounded-md hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none">
          <svg class="w-6 h-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
            <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round"
              stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
              stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>
  </div>

  <!-- Responsive Navigation Menu -->
  <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
    <div class="pt-2 pb-3 space-y-1 dark:bg-zinc-800">
      <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
        {{ __('Dashboard') }}
      </x-responsive-nav-link>

      <x-responsive-nav-link :href="route('customer.index')" :active="request()->routeIs('customer.index')">
        {{ __('Customer') }}
      </x-responsive-nav-link>

      @if (auth()->user()->role === 'Super Admin')
        <x-responsive-nav-link :href="route('kategori.index')" :active="request()->routeIs('kategori.index')">
          {{ __('Kategori') }}
        </x-responsive-nav-link>

        <x-responsive-nav-link :href="route('barang.index')" :active="request()->routeIs('barang.index')">
          {{ __('Barang') }}
        </x-responsive-nav-link>

        <x-responsive-nav-link :href="route('depo.index')" :active="request()->routeIs('depo.index')">
          {{ __('Depo') }}
        </x-responsive-nav-link>
      @endif

      @if (auth()->user()->role === 'Admin')
        <x-responsive-nav-link :href="route('kurir.index')" :active="request()->routeIs('kurir.index')">
          {{ __('Kurir') }}
        </x-responsive-nav-link>

        <x-responsive-nav-link :href="route('inventori.index')" :active="request()->routeIs('inventori.index')">
          {{ __('Inventori') }}
        </x-responsive-nav-link>

        <x-responsive-nav-link :href="route('transaksi.index')" :active="request()->routeIs('transaksi.index')">
          {{ __('Transaksi') }}
        </x-responsive-nav-link>
      @endif
    </div>

    <!-- Responsive Settings Options -->
    <div class="pt-4 pb-1 border-t border-gray-200">
      <div class="px-4">
        <div class="text-base font-medium text-gray-800 dark:text-white">{{ Auth::user()->nama }}</div>
        <div class="text-sm font-medium text-gray-500">{{ Auth::user()->email }}</div>
      </div>

      <div class="mt-3 space-y-1">
        <!-- Authentication -->
        <form method="POST" action="{{ route('logout') }}">
          @csrf

          <x-responsive-nav-link :href="route('logout')"
            onclick="event.preventDefault();
                                        this.closest('form').submit();">
            {{ __('Log Out') }}
          </x-responsive-nav-link>
        </form>
      </div>
    </div>
  </div>
</nav>
