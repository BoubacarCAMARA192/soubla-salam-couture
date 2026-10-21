@php $flashTypes = ['success' => 'check-circle-fill', 'error' => 'exclamation-triangle-fill', 'warning' => 'exclamation-triangle-fill', 'info' => 'info-circle-fill']; @endphp
@if (session('generated_password'))
    <div class="alert alert-warning d-flex justify-content-between align-items-center">
        <div>
            <strong>Nouveau mot de passe pour {{ session('generated_for') }} :</strong>
            <code class="fs-6 ms-1">{{ session('generated_password') }}</code>
        </div>
        <button class="btn btn-sm btn-bordeaux" title="Copier" data-copy="{{ session('generated_password') }}"><i class="bi bi-clipboard"></i></button>
    </div>
@endif
@foreach ($flashTypes as $key => $icon)
    @if (session($key))
        <div class="alert alert-{{ $key === 'error' ? 'danger' : ($key === 'warning' ? 'warning' : ($key === 'success' ? 'success' : 'info')) }} d-flex align-items-center gap-2 shadow-sm alert-dismissible" role="alert">
            <i class="bi bi-{{ $icon }}"></i>
            <div>{{ session($key) }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif
@endforeach
@if ($errors->any())
    <div class="alert alert-danger shadow-sm alert-dismissible" role="alert">
        <strong><i class="bi bi-exclamation-triangle"></i> Merci de corriger :</strong>
        <ul class="mb-0">
            @foreach ($errors->all() as $errorMsg)
                <li>{{ $errorMsg }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div>
@endif
