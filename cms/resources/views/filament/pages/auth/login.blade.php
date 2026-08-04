@extends('filament::components.page')

@section('content')
<div class="fi-page">
    <div class="grid gap-y-12 md:grid-cols-1 md:gap-x-6 md:gap-y-0 lg:grid-cols-1">
        <div class="fi-section-content mx-auto w-full max-w-md space-y-4 md:space-y-6">
            <div class="text-center">
                @if (config('filament.brand_logo_url'))
                    <img src="{{ config('filament.brand_logo_url') }}" alt="{{ config('app.name') }}" class="mx-auto h-16 w-auto">
                @endif
                <h1 class="text-2xl font-bold mt-4">{{ config('filament.brand_name', config('app.name')) }}</h1>
            </div>

            <form method="post" action="{{ route('filament.admin.auth.login') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        {{ __('Email') }}
                    </label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm px-4 py-2 border"
                        value="{{ old('email') }}"
                        required
                    />
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        {{ __('Password') }}
                    </label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm px-4 py-2 border"
                        required
                    />
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <input type="hidden" id="recaptcha-token" name="g-recaptcha-response">

                <div>
                    <button
                        type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                    >
                        {{ __('Login') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site') }}"></script>
<script>
    document.querySelector('form').addEventListener('submit', async function (e) {
        e.preventDefault();

        const token = await grecaptcha.execute('{{ config('services.recaptcha.site') }}', { action: 'login' });
        document.getElementById('recaptcha-token').value = token;

        this.submit();
    });
</script>
@endsection
