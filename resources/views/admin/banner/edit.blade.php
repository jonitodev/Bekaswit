@extends('layouts.admin')

@section('title', 'Edit Banner')
@section('page-title', 'Edit Banner')

@section('content')
    <div class="card admin-card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.banner.update', $banner) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('admin.banner._form')
            </form>
        </div>
    </div>
@endsection
