<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadNotificationEmail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class LeadController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        // ── Validación reCAPTCHA ──────────────────────────────────────────
        $request->validate([
            'g-recaptcha-response' => 'required|string',
        ]);

        $recaptchaResponse = $request->input('g-recaptcha-response');
        $secretKey = config('services.recaptcha.secret');

        $response = \Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $secretKey,
            'response' => $recaptchaResponse,
        ]);

        $result = $response->json();
        if (!$result['success'] || $result['score'] < 0.5) {
            return response()->json(['error' => 'reCAPTCHA validation failed'], 422);
        }

        // ── Validación de campos ──────────────────────────────────────────
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'nullable|email|max:255',
            'phone'   => 'nullable|string|max:50',
            'company' => 'nullable|string|max:255',
            'message' => 'nullable|string|max:2000',
            'source'  => 'nullable|string|max:100',
        ]);

        // ── 4. Guardar en DB (siempre, antes del correo) ──────────────────
        $lead = Lead::create($data);

        // ── 5. Notificación por correo (falla silenciosa) ─────────────────
        $this->sendNotificationEmails($lead);

        return response()->json(['ok' => true], 201);
    }

    private function sendNotificationEmails(Lead $lead): void
    {
        $emails = LeadNotificationEmail::where('active', true)->pluck('email')->toArray();

        if (empty($emails)) {
            Log::warning('No hay correos configurados para notificaciones de leads', ['lead_id' => $lead->id]);
            return;
        }

        try {
            Mail::html($this->buildEmailHtml($lead), function ($msg) use ($lead, $emails) {
                $msg->to($emails[0])
                    ->cc(array_slice($emails, 1))
                    ->subject('Nuevo lead: ' . $lead->name);
            });
        } catch (\Throwable $e) {
            Log::warning('Lead guardado, correo falló: ' . $e->getMessage(), ['lead_id' => $lead->id]);
        }
    }

    private function buildEmailHtml(Lead $lead): string
    {
        $rows = [
            'Nombre'   => $lead->name,
            'Email'    => $lead->email   ?: '—',
            'Teléfono' => $lead->phone   ?: '—',
            'Empresa'  => $lead->company ?: '—',
            'Mensaje'  => $lead->message ?: '—',
            'Origen'   => $lead->source  ?: '—',
            'Fecha'    => $lead->created_at->format('d/m/Y H:i'),
        ];

        $html  = '<div style="font-family:system-ui,sans-serif;max-width:560px;margin:0 auto;padding:24px">';
        $html .= '<h2 style="color:#7c3aed;margin-bottom:20px">Nuevo lead recibido</h2>';
        $html .= '<table style="width:100%;border-collapse:collapse">';
        foreach ($rows as $label => $value) {
            $html .= '<tr style="border-bottom:1px solid #e5e7eb">';
            $html .= '<td style="padding:10px 8px;color:#6b7280;font-size:13px;width:120px">' . e($label) . '</td>';
            $html .= '<td style="padding:10px 8px;font-size:14px;color:#111827">' . nl2br(e($value)) . '</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';
        $html .= '<p style="margin-top:20px;font-size:12px;color:#9ca3af">CyT Comunicaciones CMS</p>';
        $html .= '</div>';

        return $html;
    }
}
