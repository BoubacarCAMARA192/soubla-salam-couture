<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
        'reference',
        'client_id',
        'tailleur_id',
        'catalog_model_id',
        'type',
        'description',
        'inspiration_photo',
        'fabric',
        'event_date',
        'delivery_date',
        'status',
        'comment',
        'amount',
        'tailleur_notes',
    ];

    public const REQUESTED = 'requested';
    public const VALIDATED = 'validated';
    public const MEASURES_TAKEN = 'measures_taken';
    public const CUTTING = 'cutting';
    public const SEWING = 'sewing';
    public const FITTING = 'fitting';
    public const READY = 'ready';
    public const DELIVERED = 'delivered';
    public const CANCELLED = 'cancelled';

    public const STATUSES = [
        self::REQUESTED => ['Demandée', null],
        self::VALIDATED => ['Validée', '#f59e0b'],
        self::MEASURES_TAKEN => ['Mesures prises', '#8b5cf6'],
        self::CUTTING => ['En coupe', '#3b82f6'],
        self::SEWING => ['En couture', '#0ea5e9'],
        self::FITTING => ['Essayage / retouches', '#6366f1'],
        self::READY => ['Prête', '#22c55e'],
        self::DELIVERED => ['Livrée', '#793005'],
        self::CANCELLED => ['Annulée', '#ef4444'],
    ];

    public const ACTIVE_STATUSES = [
        self::VALIDATED,
        self::MEASURES_TAKEN,
        self::CUTTING,
        self::SEWING,
        self::FITTING,
    ];

    public const NOTIFICATION_MESSAGES = [
        self::REQUESTED => 'Votre demande de confection a bien été reçue par l\'atelier.',
        self::VALIDATED => 'Votre commande a été validée par l\'atelier.',
        self::MEASURES_TAKEN => 'Vos mensurations ont été enregistrées sur votre commande.',
        self::CUTTING => 'La confection de votre tenue a commencé : le tissu est en coupe.',
        self::SEWING => 'Votre tenue est en couture.',
        self::FITTING => 'Un essayage est nécessaire : fixons un rendez-vous.',
        self::READY => 'Bonne nouvelle : votre tenue est prête ! Passez la récupérer.',
        self::DELIVERED => 'Votre tenue vous a été livrée. Merci de votre confiance !',
        self::CANCELLED => 'Votre commande a été annulée par l\'atelier.',
    ];

    protected $casts = [
        'event_date' => 'date',
        'delivery_date' => 'date',
        'amount' => 'float',
    ];

    public static function statusLabel(string $status): string
    {
        return self::STATUSES[$status][0] ?? $status;
    }

    public static function statusColor(string $status): ?string
    {
        return self::STATUSES[$status][1] ?? null;
    }

    public static function generateReference(): string
    {
        do {
            $ref = 'SSC-'.date('y').'-'.strtoupper(Str::random(5));
        } while (static::where('reference', $ref)->exists());

        return $ref;
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function tailleur()
    {
        return $this->belongsTo(User::class, 'tailleur_id');
    }

    public function catalogModel()
    {
        return $this->belongsTo(CatalogModel::class, 'catalog_model_id');
    }

    public function statusHistory()
    {
        return $this->hasMany(OrderStatusHistory::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getPaidAmountAttribute(): float
    {
        return (float) $this->payments()->sum('amount');
    }

    public function getRemainingAmountAttribute(): float
    {
        return max(0, (float) $this->amount - $this->paid_amount);
    }

    public function getIsLateAttribute(): bool
    {
        if (!in_array($this->status, self::ACTIVE_STATUSES, true) || !$this->delivery_date) {
            return false;
        }

        return $this->delivery_date->isPast();
    }

    /** Étapes de la frise de suivi : done | current | todo | cancelled */
    public function getTimelineAttribute(): array
    {
        $flow = [
            self::REQUESTED,
            self::VALIDATED,
            self::MEASURES_TAKEN,
            self::CUTTING,
            self::SEWING,
            self::FITTING,
            self::READY,
            self::DELIVERED,
        ];
        $done = $this->statusHistory->pluck('status')->unique()->all();

        if ($this->status === self::CANCELLED) {
            $done[] = self::CANCELLED;

            return [
                'flow' => $flow,
                'state' => array_fill_keys($flow, 'cancelled'),
                'done' => $done,
            ];
        }

        $currentIndex = array_search($this->status, $flow, true);
        $state = [];
        foreach ($flow as $index => $step) {
            if ($index < $currentIndex) {
                $state[$step] = in_array($step, $done, true) ? 'done' : 'skipped';
            } elseif ($index === $currentIndex) {
                $state[$step] = 'current';
            } else {
                $state[$step] = 'todo';
            }
        }

        return ['flow' => $flow, 'state' => $state, 'done' => $done];
    }
}
