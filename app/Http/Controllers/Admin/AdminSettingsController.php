<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\ClothingType;
use App\Models\Setting;
use Illuminate\Http\Request;

/** Paramétrage de la plateforme (ADM-05) et gestion du contenu du portail (ADM-08) */
class AdminSettingsController extends Controller
{
    public function index()
    {
        return view('admin.settings', [
            'settings' => Setting::all()->keyBy('key'),
            'groups' => $this->groups(),
            'clothingTypes' => ClothingType::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request)
    {
        $keys = collect($this->groups())->flatten(1)->all();

        foreach ($keys as $key) {
            if ($request->filled($key)) {
                Setting::set($key, trim($request->input($key)));
            }
        }

        // Types de vêtements (grilles de mesures paramétrables)
        if ($request->filled('new_clothing_type')) {
            foreach (preg_split('/[\r\n,;]+/', $request->input('new_clothing_type')) as $name) {
                $name = trim($name);
                if ($name !== '' && ClothingType::where('name', $name)->doesntExist()) {
                    ClothingType::create(['name' => $name, 'is_active' => true]);
                }
            }
        }

        foreach ((array) $request->input('clothing_type_id', []) as $id => $state) {
            $isActive = is_array($state) ? (bool) ($state['state'] ?? 0) : (bool) $state;

            ClothingType::where('id', (int) $id)->update(['is_active' => $isActive]);
        }

        ActivityLog::record('settings.update');

        return back()->with('success', 'Paramètres enregistrés.');
    }

    /** Contenu du portail public : bannière, accroche, témoignages… */
    public function portal()
    {
        return view('admin.portal', [
            'settings' => Setting::all()->keyBy('key'),
            'portalKeys' => $this->portalKeys(),
        ]);
    }

    public function updatePortal(Request $request)
    {
        $data = $request->validate([
            'hero_title' => ['nullable', 'string', 'max:60'],
            'hero_subtitle' => ['nullable', 'string', 'max:300'],
            'hero_cta' => ['nullable', 'string', 'max:40'],
            'banner_text' => ['nullable', 'string', 'max:140'],
            'atelier_name' => ['nullable', 'string', 'max:80'],
            'atelier_slogan' => ['nullable', 'string', 'max:140'],
        ]);

        foreach ($data as $key => $value) {
            if ($value !== null) {
                Setting::set($key, $value);
            }
        }

        ActivityLog::record('settings.update', null, null, ['section' => 'portail']);

        return back()->with('success', 'Contenu du portail mis à jour.');
    }

    protected function portalKeys(): array
    {
        return [
            'hero_title' => 'Titre principal (bannière)',
            'hero_subtitle' => 'Accroche',
            'hero_cta' => 'Bouton d\'appel à l\'action',
            'banner_text' => 'Bandeau d\'information (alerts, Korité/Tabaski)',
            'atelier_name' => 'Nom de l\'atelier (logo/texte)',
            'atelier_slogan' => 'Slogan',
        ];
    }

    protected function groups(): array
    {
        return [
            'Atelier (coordonnées)' => [
                'atelier_phone' => 'Téléphone',
                'atelier_whatsapp' => 'Numéro WhatsApp',
                'atelier_email' => 'E-mail de contact',
                'atelier_address' => 'Adresse physique',
                'atelier_hours' => 'Horaires d\'ouverture',
                'atelier_map_url' => 'Lien carte (Google Maps)',
            ],
            'Recommandations système' => [
                'default_deposit_rate' => 'Taux d\'acompte par défaut (%)',
                'receipt_footer' => 'Mention du reçu',
                'about_text' => 'À propos (texte vitrine)',
            ],
            'Paiement Wave / Orange Money' => [
                'payment_wave_number' => 'Numéro Wave',
                'payment_om_number' => 'Numéro Orange Money',
            ],
        ];
    }
}
