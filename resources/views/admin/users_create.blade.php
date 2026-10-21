@extends('layouts.app')

@section('title', 'Créer un compte')
@section('page_title', 'Créer un compte utilisateur')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="stat-card p-4">
            @include('partials.errors')
            @include('admin.users._form', ['editUser' => new \App\Models\User])
        </div>
    </div>
</div>
@endsection
