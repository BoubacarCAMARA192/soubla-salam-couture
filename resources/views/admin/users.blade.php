@extends('layouts.app')

@section('title', 'Gestion des comptes')
@section('page_title', 'Gestion des comptes (ADM-02)')

@section('content')
<div class="d-flex flex-wrap align-items-center gap-2 mb-3">
    <form method="GET" action="{{ route('admin.users.index') }}" class="d-flex gap-2 flex-grow-1" style="max-width: 400px;">
        <input type="hidden" name="role" value="{{ $roleFilter }}">
        <input name="q" value="{{ $q }}" class="form-control" placeholder="Nom, e-mail, téléphone…">
        <button class="btn btn-bordeaux"><i class="bi bi-search"></i></button>
    </form>
    <div class="d-flex gap-2 flex-wrap ms-auto">
        <a class="btn btn-sm {{ empty($roleFilter) ? 'btn-bordeaux' : 'btn-outline-bordeaux' }} rounded-pill" href="{{ route('admin.users.index') }}">Tous</a>
        @foreach (\App\Models\User::ROLES as $r)
            <a class="btn btn-sm {{ $roleFilter === $r ? 'btn-bordeaux' : 'btn-outline-bordeaux' }} rounded-pill" href="{{ route('admin.users.index', array_filter(['role' => $r, 'q' => $q])) }}">{{ $r }}</a>
        @endforeach
        <a href="{{ route('admin.users.create') }}" class="btn btn-bordeaux rounded-pill"><i class="bi bi-person-plus"></i> Créer un compte</a>
    </div>
</div>

@if ($users->isEmpty())
    <div class="stat-card text-center py-5 small text-muted">Aucun compte trouvé.</div>
@else
    <div class="table-responsive table-cards">
        <table class="table align-middle ssc-table">
            <thead><tr><th>Utilisateur</th><th>Email</th><th>Rôle</th><th>Statut</th><th class="text-end">Actions</th></tr></thead>
            <tbody>
                @foreach ($users as $u)
                    <tr>
                        <td>
                            <strong>{{ $u->last_name }} {{ $u->first_name }}</strong>
                            <span class="small text-muted d-block">{{ $u->phone ?? '' }}</span>
                        </td>
                        <td class="small">{{ $u->email }}</td>
                        <td>
                            @if ($u->isAdmin()) <span class="badge bg-bordeaux">admin</span>
                            @elseif ($u->isTailleur()) <span class="badge bg-or text-dark">tailleur</span>
                            @else <span class="badge text-bg-light border">client</span> @endif
                        </td>
                        <td>
                            @if ($u->isActive())
                                <span class="badge bg-success">actif</span>
                            @else
                                <span class="badge bg-secondary">désactivé</span>
                            @endif
                        </td>
                        <td class="text-nowrap text-end">
                            <a class="btn btn-sm btn-outline-bordeaux" href="{{ route('admin.users.edit', $u) }}" title="Modifier"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('admin.users.toggle', $u) }}" class="d-inline">
                                @csrf
                                <button class="btn btn-sm {{ $u->isActive() ? 'btn-outline-secondary' : 'btn-outline-success' }}" title="{{ $u->isActive() ? 'Désactiver' : 'Réactiver' }}">
                                    <i class="bi {{ $u->isActive() ? 'bi-person-slash' : 'bi-person-check' }}"></i>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.users.password', $u) }}" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-outline-bordeaux" title="Réinitialiser le mot de passe"><i class="bi bi-key"></i></button>
                            </form>
                            @if ($u->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $u) }}" class="d-inline" onsubmit="return confirm('Supprimer {{ $u->full_name }} ? Action irréversible.')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Supprimer"><i class="bi bi-trash"></i></button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $users->links() }}
@endif

{{-- Affichage du mot de passe généré (fiche créée ou réinitialisé) --}}
@if (session('generated_password'))
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-4">
                <h3 class="h6 font-decor text-bordeaux"><i class="bi bi-key"></i> Nouveau mot de passe</h3>
                <p class="small">Pour <strong>{{ session('generated_for') }}</strong> — à lui transmettre de façon sécurisée, il ne sera plus affiché :</p>
                <div class="bg-creme rounded-3 p-3 text-center">
                    <code class="fs-5">{{ session('generated_password') }}</code>
                </div>
                <div class="text-end mt-3">
                    <button class="btn btn-bordeaux rounded-pill" onclick="this.closest('.modal').remove()">C'est noté</button>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection
