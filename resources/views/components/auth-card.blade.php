<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
    @if (session()->has('success'))
        <x-alert type="success" class="w-full sm:max-w-md">{{ session('success') }}</x-alert>
    @endif

    @if (session()->has('danger'))
        <x-alert type="danger" class="w-full sm:max-w-md">{{ session('danger') }}</x-alert>
    @endif

    <div>
        {{ $logo }}
    </div>

    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        {{ $slot }}
    </div>
</div>
