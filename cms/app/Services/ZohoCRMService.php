<?php

namespace App\Services;

use App\Models\AppSetting;
use App\Models\Lead;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ZohoCRMService
{
    private string $apiUrl;
    private string $apiToken;
    private string $module;

    public function __construct()
    {
        $this->apiUrl = config('services.zoho.api_url');
        $this->apiToken = AppSetting::get('zoho_api_token') ?? config('services.zoho.api_token');
        $this->module = config('services.zoho.module', 'Leads');
    }

    public function sendLead(Lead $lead): bool
    {
        if (!$this->apiToken) {
            Log::warning('Zoho CRM token no configurado');
            return false;
        }

        try {
            $payload = $this->buildLeadPayload($lead);

            $response = Http::withHeaders([
                'Authorization' => 'Zoho-oauthtoken ' . $this->apiToken,
                'Content-Type' => 'application/json',
            ])->post("{$this->apiUrl}/{$this->module}", $payload);

            if ($response->successful()) {
                Log::info('Lead enviado a Zoho CRM', [
                    'lead_id' => $lead->id,
                    'zoho_id' => $response->json('data.0.id') ?? 'unknown',
                ]);
                return true;
            }

            Log::warning('Error al enviar lead a Zoho', [
                'lead_id' => $lead->id,
                'status' => $response->status(),
                'response' => $response->json(),
            ]);
            return false;

        } catch (\Throwable $e) {
            Log::error('Exception al enviar lead a Zoho: ' . $e->getMessage(), [
                'lead_id' => $lead->id,
            ]);
            return false;
        }
    }

    private function buildLeadPayload(Lead $lead): array
    {
        return [
            'data' => [
                [
                    'Last_Name' => $lead->name,
                    'Email' => $lead->email,
                    'Phone' => $lead->phone,
                    'Company' => $lead->company,
                    'Description' => $lead->message,
                    'Lead_Source' => $lead->source ?? 'Website',
                ]
            ]
        ];
    }
}
