@if ($errors->any())
    <div class="alert alert-danger alert-dismissible" role="alert">
        <strong><i class="bi bi-exclamation-triangle"></i> Merci de corriger :</strong>
        <ul class="mb-0">
            @foreach ($errors->all() as $errorMsg)
                <li>{{ $errorMsg }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div>
@endif
