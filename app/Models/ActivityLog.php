<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'entity_type',
        'entity_id',
        'details',
        'ip_address',
    ];

    /** Journalise une action : qui a fait quoi, quand. */
    public static function record(string $action, string $entityType = null, $entityId = null, ?array $details = null): void
    {
        static::create([
            'user_id' => \Auth::id(),
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'details' => $details ? json_encode($details, JSON_UNESCAPED_UNICODE) : null,
        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getActionLabelAttribute(): string
    {
        return static::ACTION_LABELS[$this->action] ?? $this->action;
    }

    public const ACTION_LABELS = [
        'login' => 'Connexion',
        'logout' => 'Déconnexion',
        'client.create' => 'Création de client',
        'client.update' => 'Modification de client',
        'client.delete' => 'Suppression de client',
        'measurement.create' => 'Enregistrement de mensurations',
        'measurement.delete' => 'Suppression de mensurations',
        'order.create' => 'Création de commande',
        'order.update' => 'Modification de commande',
        'order.status' => 'Changement de statut',
        'order.reassign' => 'Réaffectation de commande',
        'order.delete' => 'Suppression de commande',
        'payment.create' => 'Enregistrement de paiement',
        'appointment.create' => 'Création de rendez-vous',
        'appointment.update' => 'Modification de rendez-vous',
        'fabric.create' => 'Création de tissu',
        'fabric.update' => 'Modification de tissu',
        'fabric.delete' => 'Suppression de tissu',
        'fabric.movement' => 'Mouvement de stock',
        'model.create' => 'Création de modèle',
        'model.update' => 'Modification de modèle',
        'model.publish' => 'Publication de modèle',
        'model.retire' => 'Retrait de modèle',
        'model.feature' => 'Modèle mis en avant',
        'model.delete' => 'Suppression de modèle',
        'user.create' => 'Création de compte',
        'user.update' => 'Modification de compte',
        'user.desactive' => 'Désactivation de compte',
        'user.activate' => 'Réactivation de compte',
        'user.password_reset' => 'Réinitialisation de mot de passe',
        'review.moderate' => 'Modération d\'avis',
        'review.delete' => 'Suppression d\'avis',
        'settings.update' => 'Paramétrage',
        'message.send' => 'Envoi de message',
        'expense.create' => 'Enregistrement de dépense',
        'expense.delete' => 'Suppression de dépense',
        'export.csv' => 'Export CSV',
    ];
}
