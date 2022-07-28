<div @keydown.window.escape="{{ $id }} = false" x-show="{{ $id }}" class="relative z-10"
  aria-labelledby="modal-title" x-ref="dialog" aria-modal="true" tabindex="-1">

  <div x-show="{{ $id }}" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-60 dark:bg-opacity-60 dark:bg-zinc-900"></div>

  <div class="fixed inset-0 z-10 overflow-y-auto">
    <div class="flex items-end justify-center min-h-full p-4 text-center sm:items-center sm:p-0">

      <div x-show="{{ $id }}" x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-description="Modal panel, show/hide based on modal state."
        class="relative overflow-hidden text-left transition-all transform bg-white rounded-lg shadow-xl dark:bg-zinc-900 sm:my-8 sm:max-w-lg sm:w-full"
        @click.away="{{ $id }} = false">

        <div class="px-4 pt-5 pb-4 bg-white dark:bg-zinc-800 sm:p-6 sm:pb-4">
          <h2 class="mb-4 text-xl font-bold">{{ $title }}</h2>
          {{ $content }}
        </div>
        @if (isset($footer))
          <div class="px-4 py-3 bg-gray-50 dark:bg-zinc-900 sm:px-6 sm:flex sm:flex-row-reverse sm:gap-2">
            {{ $footer }}
          </div>
        @endif
      </div>
    </div>
  </div>
</div>
