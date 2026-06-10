@extends('layouts.admin')

@section('title', 'Tambah Banner')
@section('page-title', 'Tambah Banner')

@section('content')
    <div class="card admin-card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.banner.store') }}" enctype="multipart/form-data">
                @csrf
                @include('admin.banner._form')
            </form>
        </div>
    </div>
@endsection
