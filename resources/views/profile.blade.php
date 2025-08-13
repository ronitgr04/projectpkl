

@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-person-circle me-2"></i>Profil Pengguna
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <i class="bi bi-person-circle display-1 text-primary"></i>
                        <h5 class="mt-2">{{ auth()->user()->username }}</h5>
                        <span class="badge bg-{{ auth()->user()->level == 'Admin' ? 'danger' : 'primary' }} fs-6">
                            {{ auth()->user()->level }}
                        </span>
                    </div>
                    <div class="col-md-8">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>ID User:</strong></td>
                                <td>{{ auth()->user()->id_user }}</td>
                            </tr>
                            <tr>
                                <td><strong>Kode Pengguna:</strong></td>
                                <td>{{ auth()->user()->kode_pengguna }}</td>
                            </tr>
                            <tr>
                            <td><strong>Username:</strong></td>
                                <td>{{ auth()->user()->username }}</td>
                            </tr>
                            <tr>
                                <td><strong>Level Akses:</strong></td>
                                <td>
                                    <span class="badge bg-{{ auth()->user()->level == 'Admin' ? 'danger' : 'primary' }}">
                                        {{ auth()->user()->level }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Terdaftar:</strong></td>
                                <td>{{ auth()->user()->created_at->format('d F Y, H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Terakhir Update:</strong></td>
                                <td>{{ auth()->user()->updated_at->format('d F Y, H:i') }}</td>
                            </tr>
                        </table>

                                    <div class="mt-3">
                                        <button class="btn btn-primary me-2">
                                            <i class="bi bi-pencil me-1"></i>Edit Profil
                                        </button>
                                        <button class="btn btn-warning">
                                            <i class="bi bi-key me-1"></i>Ganti Password
                                        </button>
                                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection