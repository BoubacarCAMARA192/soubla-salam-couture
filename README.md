# Soubla Salam Couture — Portail + Espaces Client / Atelier / Administration

Application web complète pour l'atelier de couture **Soubla Salam Couture** (Sacre-Cœur 3, Dakar).
Développée avec **Laravel 13 (PHP 8.4)**, MVC, MySQL (XAMPP en local), dans le cadre d'un projet
de L3 CIM (ESTM).

## Fonctionnalités (cahier des charges)

- **Portail public** : accueil, catalogue public (recherche/filtres, fiches modèles), avis,
  à propos, contact (formulaire → fiche client de passage + messagerie), mentions de confidentialité.
- **Espace Client** (`/espace-client`) : tableau de bord, profil,
  — commandes/devis (suivi + historique de statuts + messagerie par commande),
  — rendez-vous, favoris, avis, notifications (suivi de statut sécurité OKV),
  — fiche PDF de la commande, publications PWA/manifeste installable.
- **Espace Atelier** (`/atelier`) : tableau de bord (livraisons du jour, demandes entrantes, RDV),
  clients (fiches, de passage ou compte), mensurations + fiche PDF,
  commandes (chiffrage/devis, statuts avec photo de progression, affectation),
  planning (RDV), stock tissus + mouvements + alertes CSV, catalogue atelier,
  messagerie, dépenses, recherche.
- **Espace Administration** (`/administration`) : comptes (création/désactivation/réinit mot de passe),
  catalogue (publication/retrait/mise en avant), supervision des commandes
  (réaffectation couturier, annulation justifiée), modération avis,
  statistiques (CA, par statut/période/catégorie, charge par couturier),
  paramètres atelier, portail public, journal d'activité,
  exports CSV (clients, commandes, paiements, modèles, dépenses, journal) + sauvegarde.
- **Paiements/PDF** : encaissements (espèces, Wave, Orange Money, virement), plus reçus A5 et facture A4 automatiques.
- **RÔLES** : client / tailleur / admin, middleware `role`.

## Comptes de démonstration (après seed)

| Rôle        | E-mail             | Mot de passe  |
|-------------|--------------------|---------------|
| Admin       | admin@demo.sn      | password123   |
| Tailleur    | tailleur@demo.sn   | password123   |
| Client      | client@demo.sn     | password123   |
| Client(2)   | client2@demo.sn    | password123   |

## Installation (Windows, XAMPP)

1. **Prérequis** : PHP ≥ 8.4 + Composer, XAMPP (Apache + MySQL) ou MySQL 8.
2. **Activer PDO MySQL** dans `php.ini` (décommenter `extension=pdo_mysql`).
3. Créer la base :
   ```sql
   CREATE DATABASE soubla_salam_couture CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```
4. Configurer `.env` :
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=soubla_salam_couture
   DB_USERNAME=root
   DB_PASSWORD=
   ```
5. Installer et préparer :
   ```bash
   composer install
   php artisan key:generate
   php artisan migrate:fresh --seed      # crée les 27 tables + données démo
   php artisan storage:link              # liens vers storage/app/public
   ```
6. Lancer :
   ```bash
   php artisan serve
   ```
   Ouvrir http://localhost:8000

### Alternative : import SQL direct

Une sauvegarde complète (structure + données de démo) est fournie :
`database/sauvegarde-mysql.sql`

```bash
mysql -u root < database\sauvegarde-mysql.sql
```

### Revenir au SQLite (dev rapide)

Mettre `DB_CONNECTION=sqlite` (compte inutile) et créer le fichier `database/database.sqlite`.

## Archives des sauvegardes (ADM-07)

- Exports CSV : `/administration/export` (clients, commandes, paiements, modèles, dépenses, journal).
- Sauvegarde base : bouton `/administration/export/sauvegarde-base` — rappelle la
  commande mysqldump sous MySQL :
  ```bash
  mysqldump -u root soubla_salam_couture > sauvegarde.sql
  ```

## Structure technique

- `app/Http/Controllers/Client|Tailleur|Admin` : un contrôleur par module.
- Middleware `role:client|tailleur` etc. (prefixe espaces).
- Modèles : User, Client, CatalogModel (+ photos), Order + OrderStatusHistory, Payment,
  Appointment, Measurement, Fabric + FabricMovement, Message, Review, Expense, Favorite,
  Setting, ActivityLog, ShopNotification (database + mail optionnel).
- PDF : barryvdh/dompdf — `resources/views/pdf/*` (reçu A5, facture A4, mensurations, fiche client).
- Notifications : table `notifications` (Laravel) + badge sur /notifications.
- PWA : `public/manifest.json` + icônes `public/images/icon-*`.
