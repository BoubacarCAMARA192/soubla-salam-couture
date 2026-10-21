<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\Client\ClientSpaceController;
use App\Http\Controllers\Client\ClientOrderController;
use App\Http\Controllers\Client\ClientAppointmentController;
use App\Http\Controllers\Tailleur\DashboardController;
use App\Http\Controllers\Tailleur\ClientsController;
use App\Http\Controllers\Tailleur\MeasurementsController;
use App\Http\Controllers\Tailleur\OrdersController;
use App\Http\Controllers\Tailleur\AppointmentsController;
use App\Http\Controllers\Tailleur\PaymentsController;
use App\Http\Controllers\Tailleur\FabricsController;
use App\Http\Controllers\Tailleur\TailleurModelsController;
use App\Http\Controllers\Tailleur\MessagesController;
use App\Http\Controllers\Tailleur\ExpensesController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUsersController;
use App\Http\Controllers\Admin\AdminCatalogController;
use App\Http\Controllers\Admin\AdminOrdersController;
use App\Http\Controllers\Admin\AdminReviewsController;
use App\Http\Controllers\Admin\AdminStatsController;
use App\Http\Controllers\Admin\AdminSettingsController;
use App\Http\Controllers\Admin\AdminLogsController;
use App\Http\Controllers\Admin\AdminExportController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| Portail public (visiteurs et clients)
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/catalogue', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/a-propos', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'submitContact'])->name('contact.submit');

Route::get('/catalogue/{catalogModel:slug}', [CatalogController::class, 'show'])->name('catalog.show');

/*
|--------------------------------------------------------------------------
| Authentification
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/connexion', [LoginController::class, 'create'])->name('login');
    Route::post('/connexion', [LoginController::class, 'store'])->name('login.store');

    Route::get('/inscription', [RegisterController::class, 'create'])->name('register');
    Route::post('/inscription', [RegisterController::class, 'store'])->name('register.store');

    Route::get('/mot-de-passe-oublie', [PasswordController::class, 'requestForm'])->name('password.request');
    Route::post('/mot-de-passe-oublie', [PasswordController::class, 'email'])->name('password.email');
    Route::get('/mot-de-passe-reinitialisation/{token}', [PasswordController::class, 'resetForm'])->name('password.reset');
    Route::post('/mot-de-passe-reinitialisation', [PasswordController::class, 'update'])->name('password.update');
});

Route::post('/deconnexion', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::get('/confidentialite', fn () => view('pages.privacy'))->name('privacy');

/*
|--------------------------------------------------------------------------
| Notifications (client, tailleur, administrateur)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/lire-tout', [NotificationController::class, 'readAll'])->name('notifications.read_all');
    Route::post('/notifications/{id}/lu', [NotificationController::class, 'read'])->name('notifications.read');
});

/*
|--------------------------------------------------------------------------
| Espace Client
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:client'])->prefix('espace-client')->group(function () {
    Route::get('/', [ClientSpaceController::class, 'dashboard'])->name('client.dashboard');
    Route::get('/profil', [ClientSpaceController::class, 'profile'])->name('client.profile');
    Route::put('/profil', [ClientSpaceController::class, 'updateProfile'])->name('client.profile.update');

    Route::post('/favoris/{catalogModel:slug}', [ClientOrderController::class, 'toggleFavorite'])
        ->name('client.favorites.toggle');
    Route::get('/favoris', [ClientSpaceController::class, 'favorites'])->name('client.favorites');

    Route::get('/commandes/nouvelle', [ClientOrderController::class, 'create'])->name('client.orders.create');
    Route::post('/commandes', [ClientOrderController::class, 'store'])->name('client.orders.store');
    Route::get('/commandes', [ClientOrderController::class, 'index'])->name('client.orders.index');
    Route::get('/commandes/{order:reference}', [ClientOrderController::class, 'show'])->name('client.orders.show');
    Route::post('/commandes/{order:reference}/messages', [ClientOrderController::class, 'sendMessage'])
        ->name('client.orders.message');
    Route::post('/commandes/{order:reference}/avis', [ClientOrderController::class, 'review'])
        ->name('client.orders.review');
    Route::get('/commandes/{order:reference}/fiche-pdf', [ClientOrderController::class, 'clientPdf'])
        ->name('client.orders.pdf');

    Route::get('/rendez-vous', [ClientAppointmentController::class, 'index'])->name('client.appointments');
    Route::post('/rendez-vous', [ClientAppointmentController::class, 'store'])->name('client.appointments.store');
});

/*
|--------------------------------------------------------------------------
| Espace Tailleur
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:tailleur,admin'])->prefix('atelier')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('tailleur.dashboard');

    // Clients
    Route::get('/clients', [ClientsController::class, 'index'])->name('tailleur.clients.index');
    Route::get('/clients/creer', [ClientsController::class, 'create'])->name('tailleur.clients.create');
    Route::post('/clients', [ClientsController::class, 'store'])->name('tailleur.clients.store');
    Route::get('/clients/{client}', [ClientsController::class, 'show'])->name('tailleur.clients.show');
    Route::get('/clients/{client}/modifier', [ClientsController::class, 'edit'])->name('tailleur.clients.edit');
    Route::put('/clients/{client}', [ClientsController::class, 'update'])->name('tailleur.clients.update');
    Route::delete('/clients/{client}', [ClientsController::class, 'destroy'])->name('tailleur.clients.destroy');

    // Mensurations
    Route::post('/clients/{client}/mensurations', [MeasurementsController::class, 'store'])
        ->name('tailleur.measurements.store');
    Route::delete('/mensurations/{measurement}', [MeasurementsController::class, 'destroy'])
        ->name('tailleur.measurements.destroy');
    Route::get('/clients/{client}/mensurations-pdf', [MeasurementsController::class, 'pdf'])
        ->name('tailleur.measurements.pdf');

    // Commandes
    Route::get('/commandes', [OrdersController::class, 'index'])->name('tailleur.orders.index');
    Route::get('/commandes/creer', [OrdersController::class, 'create'])->name('tailleur.orders.create');
    Route::post('/commandes', [OrdersController::class, 'store'])->name('tailleur.orders.store');
    Route::get('/commandes/{order:reference}', [OrdersController::class, 'show'])->name('tailleur.orders.show');
    Route::put('/commandes/{order:reference}', [OrdersController::class, 'update'])->name('tailleur.orders.update');
    Route::post('/commandes/{order:reference}/statut', [OrdersController::class, 'updateStatus'])
        ->name('tailleur.orders.status');
    Route::post('/commandes/{order:reference}/messages', [OrdersController::class, 'sendMessage'])
        ->name('tailleur.orders.message');

    // Paiements et documents
    Route::post('/commandes/{order:reference}/paiements', [PaymentsController::class, 'store'])
        ->name('tailleur.payments.store');
    Route::get('/paiements/{payment}/recu', [PaymentsController::class, 'receipt'])->name('tailleur.receipt');
    Route::get('/commandes/{order:reference}/facture', [PaymentsController::class, 'invoice'])->name('tailleur.invoice');

    // Planning / rendez-vous
    Route::get('/planning', [AppointmentsController::class, 'index'])->name('tailleur.planning');
    Route::post('/planning', [AppointmentsController::class, 'store'])->name('tailleur.appointments.store');
    Route::put('/planning/{appointment}', [AppointmentsController::class, 'update'])->name('tailleur.appointments.update');
    Route::delete('/planning/{appointment}', [AppointmentsController::class, 'destroy'])->name('tailleur.appointments.destroy');

    // Stock de tissus
    Route::get('/stock', [FabricsController::class, 'index'])->name('tailleur.fabrics.index');
    Route::post('/stock', [FabricsController::class, 'store'])->name('tailleur.fabrics.store');
    Route::put('/stock/{fabric}', [FabricsController::class, 'update'])->name('tailleur.fabrics.update');
    Route::delete('/stock/{fabric}', [FabricsController::class, 'destroy'])->name('tailleur.fabrics.destroy');
    Route::post('/stock/{fabric}/mouvement', [FabricsController::class, 'movement'])->name('tailleur.fabrics.movement');
    Route::get('/stock/alertes', [FabricsController::class, 'alerts'])->name('tailleur.fabrics.alerts');

    // Modèles du catalogue
    Route::get('/modeles', [TailleurModelsController::class, 'index'])->name('tailleur.models.index');
    Route::get('/modeles/creer', [TailleurModelsController::class, 'create'])->name('tailleur.models.create');
    Route::post('/modeles', [TailleurModelsController::class, 'store'])->name('tailleur.models.store');
    Route::get('/modeles/{catalogModel}/modifier', [TailleurModelsController::class, 'edit'])->name('tailleur.models.edit');
    Route::put('/modeles/{catalogModel}', [TailleurModelsController::class, 'update'])->name('tailleur.models.update');
    Route::delete('/modeles/{catalogModel}', [TailleurModelsController::class, 'destroy'])->name('tailleur.models.destroy');

    // Messagerie
    Route::get('/messages', [MessagesController::class, 'index'])->name('tailleur.messages.index');
    Route::get('/messages/{client}', [MessagesController::class, 'show'])->name('tailleur.messages.show');
    Route::post('/messages/{client}', [MessagesController::class, 'reply'])->name('tailleur.messages.reply');

    // Dépenses
    Route::get('/depenses', [ExpensesController::class, 'index'])->name('tailleur.expenses.index');
    Route::post('/depenses', [ExpensesController::class, 'store'])->name('tailleur.expenses.store');
    Route::delete('/depenses/{expense}', [ExpensesController::class, 'destroy'])->name('tailleur.expenses.destroy');

    // Recherche globale
    Route::get('/recherche', [SearchController::class, 'index'])->name('tailleur.search');
});

/*
|--------------------------------------------------------------------------
| Espace Administrateur
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->prefix('administration')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Comptes et rôles
    Route::get('/comptes', [AdminUsersController::class, 'index'])->name('admin.users.index');
    Route::get('/comptes/creer', [AdminUsersController::class, 'create'])->name('admin.users.create');
    Route::post('/comptes', [AdminUsersController::class, 'store'])->name('admin.users.store');
    Route::get('/comptes/{user}/modifier', [AdminUsersController::class, 'edit'])->name('admin.users.edit');
    Route::put('/comptes/{user}', [AdminUsersController::class, 'update'])->name('admin.users.update');
    Route::delete('/comptes/{user}', [AdminUsersController::class, 'destroy'])->name('admin.users.destroy');
    Route::post('/comptes/{user}/desactiver', [AdminUsersController::class, 'toggleStatus'])->name('admin.users.toggle');
    Route::post('/comptes/{user}/mot-de-passe', [AdminUsersController::class, 'resetPassword'])->name('admin.users.password');

    // Catalogue et avis
    Route::get('/catalogue', [AdminCatalogController::class, 'index'])->name('admin.catalog.index');
    Route::get('/catalogue/creer', [AdminCatalogController::class, 'create'])->name('admin.catalog.create');
    Route::post('/catalogue', [AdminCatalogController::class, 'store'])->name('admin.catalog.store');
    Route::get('/catalogue/{catalogModel}/modifier', [AdminCatalogController::class, 'edit'])->name('admin.catalog.edit');
    Route::put('/catalogue/{catalogModel}', [AdminCatalogController::class, 'update'])->name('admin.catalog.update');
    Route::delete('/catalogue/{catalogModel}', [AdminCatalogController::class, 'destroy'])->name('admin.catalog.destroy');
    Route::post('/catalogue/{catalogModel}/publier', [AdminCatalogController::class, 'publish'])->name('admin.catalog.publish');
    Route::post('/catalogue/{catalogModel}/retirer', [AdminCatalogController::class, 'retire'])->name('admin.catalog.retire');
    Route::post('/catalogue/{catalogModel}/mise-en-avant', [AdminCatalogController::class, 'feature'])->name('admin.catalog.feature');
    Route::get('/avis', [AdminReviewsController::class, 'index'])->name('admin.reviews.index');
    Route::post('/avis/{review}', [AdminReviewsController::class, 'moderate'])->name('admin.reviews.moderate');
    Route::delete('/avis/{review}', [AdminReviewsController::class, 'destroy'])->name('admin.reviews.destroy');

    // Supervision des commandes
    Route::get('/commandes', [AdminOrdersController::class, 'index'])->name('admin.orders.index');
    Route::get('/commandes/{order:reference}', fn (\App\Models\Order $order) => app(\App\Http\Controllers\Tailleur\OrdersController::class)->show($order))
        ->name('admin.orders.show');
    Route::post('/commandes/{order:reference}/reassigner', [AdminOrdersController::class, 'reassign'])
        ->name('admin.orders.reassign');
    Route::post('/commandes/{order:reference}/annuler', [AdminOrdersController::class, 'cancel'])
        ->name('admin.orders.cancel');

    // Statistiques
    Route::get('/statistiques', [AdminStatsController::class, 'index'])->name('admin.stats');

    // Paramétrage et portail
    Route::get('/parametres', [AdminSettingsController::class, 'index'])->name('admin.settings');
    Route::post('/parametres', [AdminSettingsController::class, 'update'])->name('admin.settings.update');
    Route::get('/portail', [AdminSettingsController::class, 'portal'])->name('admin.portal');
    Route::post('/portail', [AdminSettingsController::class, 'updatePortal'])->name('admin.portal.update');
    Route::get('/journal', [AdminLogsController::class, 'index'])->name('admin.logs');

    // Sauvegarde et exports
    Route::get('/export', [AdminExportController::class, 'index'])->name('admin.export');
    Route::get('/export/sauvegarde-base', [AdminExportController::class, 'backup'])->name('admin.backup');
    Route::get('/export/{type}', [AdminExportController::class, 'download'])->name('admin.export.download');
});
