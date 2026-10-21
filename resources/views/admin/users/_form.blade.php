<form method="POST"
      action="{{ $editUser->exists ? route('admin.users.update', $editUser) : route('admin.users.store') }}">
    @csrf
    @if ($editUser->exists) @method('PUT') @endif

    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label" for="last_name">Nom <span class="text-danger">*</span></label>
            <input id="last_name" name="last_name" required class="form-control" value="{{ old('last_name', $editUser->last_name) }}">
        </div>
        <div class="col-md-6">
            <label class="form-label" for="first_name">Prénom <span class="text-danger">*</span></label>
            <input id="first_name" name="first_name" required class="form-control" value="{{ old('first_name', $editUser->first_name) }}">
        </div>
        <div class="col-md-6">
            <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
            <input id="email" name="email" type="email" required class="form-control" value="{{ old('email', $editUser->email) }}">
        </div>
        <div class="col-md-6">
            <label class="form-label" for="phone">Téléphone</label>
            <input id="phone" name="phone" type="tel" class="form-control" value="{{ old('phone', $editUser->phone) }}">
        </div>
        <div class="col-md-6">
            <label class="form-label" for="role">Rôle <span class="text-danger">*</span></label>
            <select id="role" name="role" required class="form-select" @if($editUser->exists && $editUser->id === auth()->id()) disabled @endif>
                <option value="client" @if(old('role', $editUser->role) === 'client') selected @endif>Client (espace personnel)</option>
                <option value="tailleur" @if(old('role', $editUser->role) === 'tailleur') selected @endif>Tailleur (atelier)</option>
                <option value="admin" @if(old('role', $editUser->role) === 'admin') selected @endif>Administrateur (gestion)</option>
            </select>
            <div class="form-text">Le rôle détermine les accès : atelier ⇒ clients/commandes/statistiques ; admin ⇒ tout (comptes, modération, répertoires…).</div>
        </div>
        <div class="col-md-6">
            <label class="form-label" for="password">Mot de passe {{ $editUser->exists ? '(laisser vide pour conserver)' : '' }} <span class="{{ $editUser->exists ? '' : 'text-danger' }}">@if (!$editUser->exists)*@endif</span></label>
            <div class="input-group">
                <input id="password" name="password" type="password" class="form-control"
                       autocomplete="new-password" @unless ($editUser->exists) minlength="8" @endunless>
                <button type="button" class="btn btn-outline-bordeaux" onclick="const f=document.getElementById('password'); f.type = f.type === 'password' ? 'text' : 'password';"><i class="bi bi-eye"></i></button>
            </div>
            @if (!$editUser->exists) <div class="form-text">Minimum 8 caractères. Ceci est le mot de passe initial du compte.</div> @endif
        </div>
    </div>

    <div class="d-flex gap-2 mt-4">
        <button class="btn btn-bordeaux rounded-pill"><i class="bi bi-check2"></i> {{ $editUser->exists ? 'Enregistrer' : 'Créer le compte' }}</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-bordeaux rounded-pill">Annuler</a>
    </div>
</form>
