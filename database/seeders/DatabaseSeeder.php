<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Appointment;
use App\Models\CatalogModel;
use App\Models\Client;
use App\Models\ClothingType;
use App\Models\Expense;
use App\Models\Fabric;
use App\Models\Message;
use App\Models\ModelPhoto;
use App\Models\Payment;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\Review;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Seeding Soubla Salam Couture demo data…');

        // Paramètres du portail (ADM-05/08)
        $this->seedSettings();

        // Comptes de démonstration
        $admin = User::create([
            'first_name' => 'Aminata',
            'last_name' => 'DIOP',
            'email' => 'admin@demo.sn',
            'phone' => '770000001',
            'role' => 'admin',
            'status' => 'active',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        $tailleurA = User::create([
            'first_name' => 'Moussa',
            'last_name' => 'Ndiaye',
            'email' => 'tailleur@demo.sn',
            'phone' => '770000002',
            'role' => 'tailleur',
            'status' => 'active',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        $tailleurB = User::create([
            'first_name' => 'Khady',
            'last_name' => 'Sarr',
            'email' => 'tailleur2@demo.sn',
            'phone' => '770000003',
            'role' => 'tailleur',
            'status' => 'active',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        $clientA = $this->seedClientWithUser('Fatou', 'Ba', 'client@demo.sn', '770000004', ['address' => 'Fass Delorme, rue 45']);
        $clientB = $this->seedClientWithUser('Bineta', 'Fall', 'client2@demo.sn', '770000005', ['address' => 'Sicap Baobab 2']);
        $passage1 = $this->seedPassageClient('Astou', 'Gueye', '770000006', 'Liberté 6 ext');
        $passage2 = $this->seedPassageClient('Awa', 'Camara', '770000007', 'Grand Yoff');

        // Types de vêtements (grilles paramétrables)
        foreach (['boubou homme', 'boubou femme', 'robe', 'ensemble', 'jupe', 'chemise', 'tissu'] as $type) {
            ClothingType::create(['name' => $type, 'is_active' => true]);
        }

        // Tissus du stock (TAI-08)
        $fabrics = [
            ['name' => 'Bazin getzner bleu', 'color' => 'bleu marine', 'quantity' => 12, 'unit' => 'm', 'alert_threshold' => 5, 'notes' => 'Getzner original'],
            ['name' => 'Bazin getzner bordeaux', 'color' => 'bordeaux', 'quantity' => 4, 'unit' => 'm', 'alert_threshold' => 5, 'notes' => 'quasi épuisé'],
            ['name' => 'Wax afro doré', 'color' => 'or / brun', 'quantity' => 9, 'unit' => 'm', 'alert_threshold' => 4],
            ['name' => 'Rips satin ivoire', 'color' => 'ivoire', 'quantity' => 3, 'unit' => 'm', 'alert_threshold' => 4],
            ['name' => 'Percale brodée verte', 'color' => 'vert foncé', 'quantity' => 7, 'unit' => 'm', 'alert_threshold' => 4],
        ];
        foreach ($fabrics as $f) {
            Fabric::create($f);
        }

        // Catalogue : modèles avec vignettes SVG générées (CAT-xx)
        $models = [
            ['title' => 'Robe bazin brodée or', 'category' => 'femme', 'type' => 'traditionnel', 'occasion' => 'Korité', 'price' => 35000, 'delay' => 7, 'fabric' => 'Bazin getzner bleu or', 'colors' => 'bleu, or, ivoire', 'featured' => true],
            ['title' => 'Boubou homme premier roi', 'category' => 'homme', 'type' => 'traditionnel', 'occasion' => 'Tabaski', 'price' => 50000, 'delay' => 10, 'fabric' => 'Bazin riche rips', 'colors' => 'blanc, doré', 'featured' => true],
            ['title' => 'Ensemble wax cérémonie', 'category' => 'femme', 'type' => 'traditionnel', 'occasion' => 'mariage', 'price' => 28000, 'delay' => 6, 'fabric' => 'Wax afro', 'colors' => 'rouge, multicolore'],
            ['title' => 'Robe soirette ville', 'category' => 'femme', 'type' => 'moderne', 'occasion' => 'ville', 'price' => 22000, 'delay' => 5, 'fabric' => 'Rips satin', 'colors' => 'noir, bordeaux', 'featured' => true],
            ['title' => 'Suit 3 pièces marié', 'category' => 'homme', 'type' => 'moderne', 'occasion' => 'mariage', 'price' => 75000, 'delay' => 14, 'fabric' => 'Laine mélangée', 'colors' => 'gris perle, marine'],
            ['title' => 'Peau Toyota brodée', 'category' => 'enfant', 'type' => 'traditionnel', 'occasion' => 'Tabaski', 'price' => 15000, 'delay' => 5, 'fabric' => 'Percale brodée', 'colors' => 'vert, blanc, or'],
            ['title' => 'Grand boubou baptême', 'category' => 'enfant', 'type' => 'traditionnel', 'occasion' => 'baptême', 'price' => 18000, 'delay' => 7, 'fabric' => 'Voile bazin', 'colors' => 'blanc'],
            ['title' => 'Robe española moderne', 'category' => 'femme', 'type' => 'moderne', 'occasion' => 'baptême', 'price' => 20000, 'delay' => 5, 'fabric' => 'Dentelle', 'colors' => 'poudré, saumon'],
            ['title' => 'Djellaba homme zat rouge', 'category' => 'homme', 'type' => 'traditionnel', 'occasion' => 'Korité', 'price' => 30000, 'delay' => 8, 'fabric' => 'Bazin getzner', 'colors' => 'rouge démon'],
            ['title' => 'Jupe + débardeur duo', 'category' => 'femme', 'type' => 'moderne', 'occasion' => 'ville', 'price' => 16000, 'delay' => 4, 'fabric' => 'Kente coton', 'colors' => 'jaune Emperor'],
            ['title' => 'Tailleur fusion mariage', 'category' => 'femme', 'type' => 'moderne', 'occasion' => 'mariage', 'price' => 65000, 'delay' => 12, 'fabric' => 'Organza brodé', 'colors' => 'champagne'],
            ['title' => 'Grand mbubb, 3 waars', 'category' => 'femme', 'type' => 'traditionnel', 'occasion' => 'Tabaski', 'price' => 60000, 'delay' => 11, 'fabric' => 'Bazin getzner bordeaux', 'colors' => 'bordeaux, vert'],
        ];

        $createdModels = [];
        $position = 0;
        foreach ($models as $m) {
            $model = CatalogModel::create([
                'title' => $m['title'],
                'slug' => \Illuminate\Support\Str::slug($m['title']),
                'description' => 'Tenue '.$m['type'].' pour '.$m['occasion'].($m['category'] === 'enfant' ? ' (taille enfant au-delà de 4 ans).' : '. Coupe ajustée, finitions main.')
                    .' Tissus conseillés : '.$m['fabric'].'. Délai indicatif sans période de fête : '.$m['delay'].' jours.',
                'category' => $m['category'],
                'type' => $m['type'],
                'occasion' => $m['occasion'],
                'suggested_fabric' => $m['fabric'],
                'suggested_colors' => $m['colors'],
                'indicative_price' => $m['price'],
                'delay_days' => $m['delay'],
                'status' => 'published',
                'is_featured' => $m['featured'] ?? false,
                'created_by' => $tailleurA->id,
            ]);

            $this->seedModelPhotos($model, $m, $position);

            $createdModels[] = $model;
            $position++;
        }

        // Un modèle en brouillon proposé par la couturière Khady (TAI-07)
        $draft = CatalogModel::create([
            'title' => 'Robe veilée brodée ivoire',
            'slug' => 'robe-veilee-brodee-ivoire',
            'description' => 'Proposition : robe bazin ivoire avec broderie chargée pour soirée mariage.',
            'category' => 'femme',
            'type' => 'traditionnel',
            'occasion' => 'mariage',
            'suggested_fabric' => 'Voile bazin',
            'indicative_price' => 42000,
            'status' => 'draft',
            'created_by' => $tailleurB->id,
        ]);

        // Mensurations pour Fatou (TAI-03)
        $this->seedMeasurements($clientA, $tailleurA);

        // Fabrication de commandes avec parcours complets
        $this->seedOrderDelivered($clientA, $tailleurA, $createdModels[0], $passage1);
        $orderActive = $this->seedOrderActive($clientA, $tailleurA, $createdModels[1]);
        $this->seedOrderActive($clientB, $tailleurB, $createdModels[5]);
        $this->seedOrderRequested($clientB, $tailleurA, $createdModels[7]);
        $quote = $this->seedQuote($passage1, $tailleurA, $createdModels[11]);
        $this->seedOrderCancelled($passage2, $tailleurB);
        $this->seedOrderReady($clientB, $tailleurA, $createdModels[8]);

        // Favoris de Fatou
        foreach ([$createdModels[1]->id, $createdModels[3]->id] as $modelId) {
            \App\Models\Favorite::create([
                'user_id' => $clientA->user_id,
                'catalog_model_id' => $modelId,
            ]);
        }

        // Rendez-vous du mois (planning)
        $this->seedAppointments($clientA, $clientB, $orderActive, $quote, $tailleurA, $tailleurB);

        // Dépenses du mois (TAI-10)
        $expenses = [
            ['Rouleaux de fil (12 tons)', 'fournitures', 14500],
            ['Machines : entretien annuel', 'divers', 25000],
            ['Transport livraisons Fass', 'transport', 4500],
            ['Publicité page Facebook', 'publicité', 10000],
            ['Kontour ciseaux pro', 'fournitures', 12000],
        ];
        foreach ($expenses as [$label, $category, $amount]) {
            Expense::create([
                'label' => $label,
                'category' => $category,
                'amount' => $amount,
                'spent_at' => now()->subDays(random_int(1, 12)),
                'created_by' => $admin->id,
            ]);
        }

        ActivityLog::record('system.seed', null, null, ['jeu' => 'données de démonstration']);
        $this->command->info('Done. Comptes : admin@demo.sn / tailleur@demo.sn / client@demo.sn (mot de passe : password123)');
    }

    private function seedClientWithUser(string $first, string $last, string $email, string $phone, array $extra = []): Client
    {
        $user = User::create([
            'first_name' => $first,
            'last_name' => $last,
            'email' => $email,
            'phone' => $phone,
            'role' => 'client',
            'status' => 'active',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        return Client::create([
            'user_id' => $user->id,
            'full_name' => $user->full_name,
            'phone' => $phone,
            'first_visit_at' => now()->subMonths(2),
            'last_visit_at' => now()->subDays(3),
            'address' => $extra['address'] ?? null,
            'notes' => 'Préfère les finitions main, coupe ajustée.',
        ]);
    }

    private function seedPassageClient(string $first, string $last, string $phone, string $address): Client
    {
        return Client::create([
            'full_name' => $first.' '.$last,
            'phone' => $phone,
            'address' => $address,
            'first_visit_at' => now()->subDays(20),
        ]);
    }

    private function seedSettings(): void
    {
        $defaults = [
            'atelier_name' => 'Soubla Salam Couture',
            'atelier_slogan' => 'L\'élégance confectionnée à la main',
            'atelier_phone' => '+221 77 555 88 90',
            'atelier_whatsapp' => '+221775558890',
            'atelier_email' => 'contact@soublasalam.sn',
            'atelier_address' => 'Sacré-Cœur 3 n°5415, Dakar',
            'atelier_hours' => "Lun — Sam : 9 h — 19 h\nDimanche : sur devis",
            'atelier_map_url' => 'https://maps.google.com/?q=Sacre-Coeur-3-Dakar',
            'banner_text' => 'Commandes Korité : tout dépôt avant le 15 du mois — dans la limite de la charge atelier.',
            'hero_title' => 'Vos tenues de fête, taillées sur mesure',
            'hero_subtitle' => 'Catalogue, commandes & suivi confie mesures — codza lis à Dakar depuis 2018.',
            'hero_cta' => 'Découvrir le catalogue',
            'about_text' => 'Depuis 2018, l\'atelier Soubla Salam Couture accompagne les familles de Dakar pour toutes leurs tenues d\'événement : Korité, Tabaski, mariages, baptêmes. Chaque confection suit un circuit maîtrisé : mesures, coupe, couture, essayage, livraison — avec des photos de progression prises à chaque étape pour un suivi serein.',
            'default_deposit_rate' => '50',
            'receipt_footer' => 'Merci de votre confiance — un acompte versé n\'est pas remboursable si la commande est annulée après la coupe.',
            'payment_wave_number' => '77 555 88 90',
            'payment_om_number' => '78 555 88 90',
            'facebook_url' => '',
            'instagram_url' => '',
        ];

        foreach ($defaults as $key => $value) {
            Setting::set($key, $value);
        }
    }

    private function seedModelPhotos(CatalogModel $model, array $data, int $offset): void
    {
        // Fond par catégories : alternances sobres pour les vignettes
        $palettes = [
            ['#7b1e3a', '#d9a441'],
            ['#2c3653', '#d9a441'],
            ['#3f2a49', '#c98f2e'],
            ['#59422c', '#e3c98f'],
        ];
        [$bg, $fg] = $palettes[$offset % count($palettes)];

        $dir = storage_path('app/public/models');
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $name = $model->slug.'.svg';
        $path = $dir.'/'.$name;

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="640" height="480" viewBox="0 0 640 480">'
            .'<rect width="640" height="480" fill="'.$bg.'"/>'
            .'<g stroke="'.$fg.'" stroke-width="7" fill="none" stroke-linecap="round">'
            .'<path d="M170 110 Q 150 150 172 185 L 205 420 L 435 420 L 468 185 Q 490 150 470 110"/>'
            .'<path d="M172 185 Q 320 150 468 185" stroke-opacity=".65"/>'
            .'<circle cx="120" cy="430" r="16" fill="'.$fg.'" stroke="none"/>'
            .'<circle cx="520" cy="60" r="13" fill="'.$fg.'" stroke="none" opacity=".8"/>'
            .'</g>'
            .'<text x="320" y="458" text-anchor="middle" font-family="sans-serif" font-size="21" fill="'.$fg.'" opacity="0.9">'.htmlspecialchars($model->title).'</text>'
            .'</svg>';

        file_put_contents($path, $svg);

        ModelPhoto::create([
            'catalog_model_id' => $model->id,
            'path' => 'models/'.$name,
            'position' => 0,
        ]);
    }

    private function seedMeasurements(Client $client, User $tailleur): void
    {
        $grids = [
            'robe' => ['chest' => 92, 'waist' => 74, 'hips' => 98, 'shoulders' => 38, 'sleeve_length' => 56, 'top_length' => 108, 'pants_length' => null, 'thigh' => null],
            'boubou femme' => ['chest' => 94, 'waist' => 75, 'hips' => 99, 'shoulders' => 39, 'sleeve_length' => 58, 'top_length' => 128, 'pants_length' => null, 'thigh' => null],
            'jupe' => ['chest' => null, 'waist' => 73, 'hips' => 98, 'shoulders' => null, 'sleeve_length' => null, 'top_length' => 58, 'pants_length' => null, 'thigh' => 57],
        ];

        foreach ($grids as $type => $values) {
            \App\Models\Measurement::create(array_merge($values, [
                'client_id' => $client->id,
                'clothing_type' => $type,
                'extras' => null,
                'notes' => $type === 'jupe' ? 'Longueur du bas au ¾ des mollets' : null,
                'taken_by' => $tailleur->id,
                'created_at' => now()->subDays(12),
            ], ['updated_at' => now()->subDays(12)]));
        }
    }

    private function seedOrderDelivered(Client $client, User $tailleur, CatalogModel $model, Client $passage): Order
    {
        $order = Order::create([
            'reference' => Order::generateReference(),
            'client_id' => $client->id,
            'tailleur_id' => $tailleur->id,
            'catalog_model_id' => $model->id,
            'type' => 'order',
            'description' => 'Robe bazin brodée or (Korité), coupe ajustée, manches longues.',
            'fabric' => 'Bazin getzner bleu',
            'delivery_date' => now()->subDays(8)->toDateString(),
            'status' => Order::DELIVERED,
            'amount' => 35000,
            'created_at' => now()->subDays(21),
            'updated_at' => now()->subDays(8),
        ]);
        $order->statusHistory()->createMany([
            ['status' => Order::VALIDATED, 'comment' => 'Devis validé, création de la commande.', 'changed_by' => $tailleur->id, 'created_at' => now()->subDays(21)],
            ['status' => Order::MEASURES_TAKEN, 'comment' => 'Mensurations relevées à l\'atelier.', 'changed_by' => $tailleur->id, 'created_at' => now()->subDays(20)],
            ['status' => Order::CUTTING, 'changed_by' => $tailleur->id, 'created_at' => now()->subDays(17)],
            ['status' => Order::SEWING, 'comment' => 'Machine thread 03 ; broderie sous-traitée', 'changed_by' => $tailleur->id, 'created_at' => now()->subDays(14)],
            ['status' => Order::READY, 'comment' => 'Terminée — contrôle des finitions.', 'changed_by' => $tailleur->id, 'created_at' => now()->subDays(9)],
            ['status' => Order::DELIVERED, 'comment' => 'Livraison à domicile (Fass).', 'changed_by' => $tailleur->id, 'created_at' => now()->subDays(8)],
        ]);

        Payment::create([
            'order_id' => $order->id,
            'receipt_number' => \App\Models\Payment::generateReceiptNumber(),
            'amount' => 17500,
            'method' => 'cash',
            'type' => 'deposit',
            'paid_at' => now()->subDays(21)->setTime(15, 20),
            'recorded_by' => $tailleur->id,
        ]);
        Payment::create([
            'order_id' => $order->id,
            'receipt_number' => \App\Models\Payment::generateReceiptNumber(),
            'amount' => 17500,
            'method' => 'wave',
            'type' => 'balance',
            'paid_at' => now()->subDays(8)->setTime(11, 5),
            'recorded_by' => $tailleur->id,
        ]);

        // Avis publié + un avis en attente
        \App\Models\Review::create([
            'client_id' => $client->id,
            'user_id' => $client->user_id,
            'author_name' => $client->full_name,
            'catalog_model_id' => $model->id,
            'order_id' => $order->id,
            'rating' => 5,
            'comment' => 'Couture très propre, délai respecté, essayage nickel.',
            'status' => 'published',
        ]);

        return $order;
    }

    private function seedOrderActive(Client $client, User $tailleur, CatalogModel $model): Order
    {
        $order = Order::create([
            'reference' => Order::generateReference(),
            'client_id' => $client->id,
            'tailleur_id' => $tailleur->id,
            'catalog_model_id' => $model->id,
            'type' => 'order',
            'description' => 'Boubou premier roi blanc/doré pour Tabaski, col rond, 2 poches appliquées.',
            'fabric' => 'Bazin riche rips blanc',
            'event_date' => now()->addDays(16)->toDateString(),
            'delivery_date' => now()->addDays(12)->toDateString(),
            'status' => Order::SEWING,
            'amount' => 50000,
            'created_at' => now()->subDays(10),
            'updated_at' => now()->subDays(2),
        ]);
        $order->statusHistory()->createMany([
            ['status' => Order::VALIDATED, 'comment' => 'Acompte reçu.', 'changed_by' => $tailleur->id, 'created_at' => now()->subDays(10)],
            ['status' => Order::MEASURES_TAKEN, 'changed_by' => $tailleur->id, 'created_at' => now()->subDays(9)],
            ['status' => Order::CUTTING, 'comment' => 'Coupe du 2e pain de bazin', 'changed_by' => $tailleur->id, 'created_at' => now()->subDays(5)],
            ['status' => Order::SEWING, 'changed_by' => $tailleur->id, 'created_at' => now()->subDays(2)],
        ]);
        Payment::create([
            'order_id' => $order->id,
            'receipt_number' => \App\Models\Payment::generateReceiptNumber(),
            'amount' => 25000,
            'method' => 'orange_money',
            'type' => 'deposit',
            'paid_at' => now()->subDays(10)->setTime(10, 30),
            'recorded_by' => $tailleur->id,
        ]);

        return $order;
    }

    private function seedOrderRequested(Client $client, User $tailleur, CatalogModel $model): Order
    {
        $order = Order::create([
            'reference' => Order::generateReference(),
            'client_id' => $client->id,
            'catalog_model_id' => $model->id,
            'type' => 'order',
            'description' => 'Robe espagnole pour baptême, dans tons saumon, taille dorsal ajustée.',
            'event_date' => now()->addDays(22)->toDateString(),
            'status' => Order::REQUESTED,
            'amount' => 0,
            'created_at' => now()->subDays(2),
        ]);
        return $order;
    }

    private function seedQuote(Client $passage, User $tailleur, CatalogModel $model): Order
    {
        $order = Order::create([
            'reference' => Order::generateReference(),
            'client_id' => $passage->id,
            'tailleur_id' => $tailleur->id,
            'catalog_model_id' => $model->id,
            'type' => 'quote',
            'description' => 'Grand mbubb 3 waars pour Tabaski, tissu fourni (bazin bordeaux), broderie esperada col.',
            'event_date' => now()->addDays(17)->toDateString(),
            'status' => Order::REQUESTED,
            'amount' => 0,
            'created_at' => now()->subDays(3),
        ]);
        return $order;
    }

    private function seedOrderCancelled(Client $client, User $tailleur): void
    {
        $order = Order::create([
            'reference' => Order::generateReference(),
            'client_id' => $client->id,
            'tailleur_id' => $tailleur->id,
            'type' => 'order',
            'description' => 'Robe du soir dentelle poudré pour mariage fin du mois.',
            'status' => Order::CANCELLED,
            'amount' => 68000,
            'created_at' => now()->subDays(14),
            'updated_at' => now()->subDays(11),
        ]);
        $order->statusHistory()->createMany([
            ['status' => Order::VALIDATED, 'changed_by' => $tailleur->id, 'created_at' => now()->subDays(14)],
            ['status' => Order::CUTTING, 'changed_by' => $tailleur->id, 'created_at' => now()->subDays(12)],
            ['status' => Order::CANCELLED, 'comment' => 'MARIAGE REPORTE PAR LE CLIENT — tissu de réserve en stock.', 'changed_by' => $tailleur->id, 'created_at' => now()->subDays(11)],
        ]);
    }

    private function seedOrderReady(Client $client, User $tailleur, CatalogModel $model): void
    {
        $order = Order::create([
            'reference' => Order::generateReference(),
            'client_id' => $client->id,
            'tailleur_id' => $tailleur->id,
            'catalog_model_id' => $model->id,
            'type' => 'order',
            'description' => 'Djellaba zat rouge pour Korité — terminé, en attente d\'encaissement.',
            'fabric' => 'Bazin getzner bordeaux',
            'delivery_date' => now()->addDays(2)->toDateString(),
            'status' => Order::READY,
            'amount' => 30000,
            'created_at' => now()->subDays(13),
            'updated_at' => now()->subDay(),
        ]);
        $order->statusHistory()->createMany([
            ['status' => Order::VALIDATED, 'changed_by' => $tailleur->id, 'created_at' => now()->subDays(13)],
            ['status' => Order::MEASURES_TAKEN, 'changed_by' => $tailleur->id, 'created_at' => now()->subDays(12)],
            ['status' => Order::READY, 'comment' => 'Repassé + housse.', 'changed_by' => $tailleur->id, 'created_at' => now()->subDay()],
        ]);
    }

    private function seedAppointments(Client $clientA, Client $clientB, Order $orderActive, Order $quote, User $tailleurA, User $tailleurB): void
    {
        Appointment::create([
            'client_id' => $clientB->id,
            'order_id' => $orderActive->id,
            'tailleur_id' => $tailleurB->id,
            'type' => 'fitting',
            'scheduled_at' => now()->addDays(3)->setTime(16, 0),
            'status' => 'planned',
            'notes' => 'Proposer le lien exhaustif de finitions.',
        ]);
        Appointment::create([
            'client_id' => $clientA->id,
            'tailleur_id' => $tailleurA->id,
            'type' => 'measurements',
            'scheduled_at' => now()->setTime(11, 30),
            'status' => 'planned',
            'notes' => 'Nouvelle proposition corot : robe marron clair.',
        ]);
        Appointment::create([
            'client_id' => $clientA->id,
            'tailleur_id' => $tailleurA->id,
            'type' => 'measurements',
            'scheduled_at' => now()->subDays(20)->setTime(10, 45),
            'status' => 'done',
        ]);
        Appointment::create([
            'client_id' => $quote->client_id,
            'tailleur_id' => $tailleurA->id,
            'type' => 'measurements',
            'scheduled_at' => now()->addDays(5)->setTime(9, 30),
            'status' => 'planned',
            'notes' => 'Vérifier l\'épaule du mokar.',
        ]);
    }
}



