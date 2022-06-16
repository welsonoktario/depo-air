<div @keydown.window.escape="open = false" x-show="open"
  class="relative z-10" aria-labelledby="modal-title" x-ref="dialog" aria-modal="true">

  <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>


  <div class="fixed z-10 inset-0 overflow-y-auto">
    <div class="flex items-end sm:items-center justify-center min-h-full p-4 text-center sm:p-0">

      <div x-show="open" x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-description="Modal panel, show/hide based on modal state."
        class="relative bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full"
        @click.away="open = false">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
          {{ $content }}
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse sm:gap-2">
          {{ $footer }}
        </div>
      </div>
    </div>
  </div>
</div>
