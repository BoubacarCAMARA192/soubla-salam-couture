<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /** Valeurs par défaut de la configuration de l'atelier et du portail */
    public static function defaults(): array
    {
        return [
            'atelier_name' => 'Soubla Salam Couture',
            'atelier_slogan' => 'L\'élégance sénégalaise, taillée pour vous',
            'atelier_phone' => '+221 77 000 00 00',
            'atelier_whatsapp' => '+221 77 000 00 00',
            'atelier_email' => 'contact@soublasalamcouture.sn',
            'atelier_address' => 'Sacré-Cœur 3, Villa 250, Dakar, Sénégal',
            'atelier_hours' => "Lundi - Samedi : 9h00 - 20h00\nDimanche : sur rendez-vous",
            'atelier_map_url' => 'https://maps.google.com/?q=Sacre-Coeur-3+Dakar',
            'hero_title' => 'Soubla Salam Couture',
            'hero_subtitle' => 'Tenues traditionnelles et modernes confectionnées à la main, pour la Korité, la Tabaski, les mariages et vos grandes cérémonies.',
            'hero_cta' => 'Parcourir le catalogue',
            'about_text' => "Soubla Salam Couture est un atelier familial de Dakar spécialisé dans la confection sur mesure de tenues traditionnelles et modernes. De l'apprivoisement du tissu aux finitions à la main, chaque pièce est préparée avec soin : bazin riche brodé, wax, rips, boubou, caftan, chemises tailleur. Forts d'une fidèle clientèle et d'un savoir-faire transmis de génération en génération, nous vous accompagnons de la prise de mesures jusqu'à l'essayage final.",
            'receipt_footer' => "Merci de votre confiance.\nUn reçu non signé ne vaut pas après-vente — conservez-le.",
            'default_deposit_rate' => '50',
            'payment_wave_number' => '+221 77 000 00 00',
            'payment_om_number' => '+221 78 000 00 00',
            'banner_text' => 'Commandes spéciales Korité & Tabaski : pensez à passer vos commandes à l\'avance !',
            'facebook_url' => '',
            'instagram_url' => '',
        ];
    }

    public static function get(string $key, ?string $default = null): string
    {
        $settings = static::allCached();

        return $settings[$key] ?? static::defaults()[$key] ?? $default ?? '';
    }

    public static function set(string $key, string $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget('settings.all');
    }

    protected static function allCached(): array
    {
        return Cache::remember('settings.all', 300, function () {
            return static::query()->pluck('value', 'key')->all();
        });
    }
}
