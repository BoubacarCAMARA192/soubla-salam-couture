@extends('layouts.app')

@section('title', 'Modifier '.$editUser->full_name)
@section('page_title', 'Modifier le compte')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="stat-card p-4">
            @include('partials.errors')
            @include('admin.users._form', ['editUser' => $editUser])
        </div>
    </div>
</div>
@endsection
