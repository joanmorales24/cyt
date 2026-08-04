<?php

namespace App\Filament\Pages;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Illuminate\Validation\ValidationException;

class CustomLogin extends BaseLogin
{
    public function authenticate(): ?LoginResponse
    {
        // Validar reCAPTCHA
        $recaptchaResponse = request()->input('g-recaptcha-response');

        if (!$recaptchaResponse) {
            throw ValidationException::withMessages([
                'data.email' => 'reCAPTCHA validation failed. Please try again.',
            ]);
        }

        $secretKey = config('services.recaptcha.secret');
        $response = \Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $secretKey,
            'response' => $recaptchaResponse,
        ]);

        $result = $response->json();
        if (!$result['success'] || $result['score'] < 0.5) {
            throw ValidationException::withMessages([
                'data.email' => 'reCAPTCHA validation failed. Please try again.',
            ]);
        }

        // Continuar con la autenticación normal
        return parent::authenticate();
    }
}
