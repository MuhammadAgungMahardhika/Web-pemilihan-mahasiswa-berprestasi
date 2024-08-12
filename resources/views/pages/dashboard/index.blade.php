@extends('template.layout-vertical.main')
@section('container')
    <style>
        .daftarMenu {
            transition: transform 0.3s ease;
        }

        .daftarMenu:hover {
            transform: scale(1.1);
            opacity: 0.9;
        }
    </style>
    <section class="section">
        <div class="card">
            <div class="card-header text-center">
                <h3 class="card-title">Sistem Informasi Pemilihan Mahasiswa Berprestasi</h3>
            </div>
            <div class="card-body mt-4">
                <div class="row justify-content-center text-center">
                    @if (session('portalSession'))
                        <div class="alert alert-warning text-white">
                            {{ session('portalSession') }}
                        </div>
                    @endif
                    {{-- Dashboard untuk mahasiswa --}}
                    @if (Auth::user()->role->nama == 'mahasiswa')
                        <div class="col-12 col-md-6 col-lg-4">
                            <a href="{{ url('/dokumen-prestasi') }}">
                                <div class="card shadow daftarMenu">
                                    <div class="card-header bg-primary">
                                        <h5 class="card-title mt-4 text-white">Status Dokumen Prestasi</h5>
                                    </div>
                                    <div class="card-body d-flex justify-content-between align-items-center p-2">
                                        <div class="text-center flex-grow-1">
                                            <p class="text-warning">Menunggu</p>
                                            <h2>{{ $menunggu }}</h2>
                                        </div>
                                        <div class="border-start border-secondary mx-3" style="height: 80px;"></div>
                                        <div class="text-center flex-grow-1">
                                            <p class="text-success">Diterima</p>
                                            <h2>{{ $diterima }}</h2>
                                        </div>
                                        <div class="border-start border-secondary mx-3" style="height: 80px;"></div>
                                        <div class="text-center flex-grow-1">
                                            <p class="text-danger">Ditolak</p>
                                            <h2>{{ $ditolak }}</h2>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif

                    {{-- Dashboard untuk admin departmen --}}
                    @if (Auth::user()->role->nama == 'admin_departmen')
                        <div class="col-12 col-md-6 col-lg-4">
                            <a href="{{ url('/verifikasi-dokumen') }}">
                                <div class="card shadow daftarMenu">
                                    <div class="card-header bg-primary">
                                        <h5 class="card-title mt-4 text-white">Status Verifikasi</h5>
                                    </div>
                                    <div class="card-body d-flex justify-content-between align-items-center p-2">
                                        <div class="text-center flex-grow-1">
                                            <p class="text-warning">Menunggu</p>
                                            <h2>{{ $menunggu }}</h2>
                                        </div>
                                        <div class="border-start border-secondary mx-3" style="height: 80px;"></div>
                                        <div class="text-center flex-grow-1">
                                            <p class="text-success">Diterima</p>
                                            <h2>{{ $diterima }}</h2>
                                        </div>
                                        <div class="border-start border-secondary mx-3" style="height: 80px;"></div>
                                        <div class="text-center flex-grow-1">
                                            <p class="text-danger">Ditolak</p>
                                            <h2>{{ $ditolak }}</h2>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-12 col-md-3 col-lg-4">
                            <a href="{{ url('/mahasiswa') }}">
                                <div class="card shadow daftarMenu">
                                    <div class="card-header bg-primary">
                                        <h5 class="card-title mt-4 text-white"> Mahasiswa </h5>
                                    </div>

                                    <div class="card-body d-flex justify-content-center align-items-center">
                                        <h1 class="m-3">{{ $mahasiswa }}</h1>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-12 col-md-3 col-lg-4">
                            <a href="{{ url('/utusan-departmen') }}">
                                <div class="card shadow daftarMenu">
                                    <div class="card-header bg-primary">
                                        <h5 class="card-title mt-4 text-white"> Utusan Departemen
                                        </h5>
                                    </div>

                                    <div class="card-body d-flex justify-content-center align-items-center">
                                        <h1 class="m-3">{{ $utusan }}</h1>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif

                    {{-- Dashboard untuk admin fakultas --}}
                    @if (Auth::user()->role->nama == 'admin_fakultas')
                        <div class="col-12 col-md-3 col-lg-4">
                            <a href="{{ url('/admin-departmen') }}">
                                <div class="card shadow daftarMenu">
                                    <div class="card-header bg-primary">
                                        <h5 class="card-title mt-4 text-white"> Admin Departemen
                                        </h5>
                                    </div>

                                    <div class="card-body d-flex justify-content-center align-items-center">
                                        <h1 class="m-3">{{ $admin_departmen }}</h1>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-12 col-md-3 col-lg-4">
                            <a href="{{ url('/utusan-fakultas') }}">
                                <div class="card shadow daftarMenu">
                                    <div class="card-header bg-primary">
                                        <h5 class="card-title mt-4 text-white"> Utusan Fakultas
                                        </h5>
                                    </div>

                                    <div class="card-body d-flex justify-content-center align-items-center">
                                        <h1 class="m-3">{{ $utusan_fakultas }}</h1>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif

                    {{-- Dashboard untuk admin universitas --}}
                    @if (Auth::user()->role->nama == 'admin_universitas')
                        <div class="col-12 col-md-3 col-lg-4">
                            <a href="{{ url('/admin-departmen') }}">
                                <div class="card shadow daftarMenu">
                                    <div class="card-header bg-primary">
                                        <h5 class="card-title mt-4 text-white"> Admin Fakultas
                                        </h5>
                                    </div>

                                    <div class="card-body d-flex justify-content-center align-items-center">
                                        <h1 class="m-3">{{ $admin_fakultas }}</h1>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-12 col-md-3 col-lg-4">
                            <a href="{{ url('/utusan-fakultas') }}">
                                <div class="card shadow daftarMenu">
                                    <div class="card-header bg-primary">
                                        <h5 class="card-title mt-4 text-white"> Utusan Kampus
                                        </h5>
                                    </div>

                                    <div class="card-body d-flex justify-content-center align-items-center">
                                        {{-- <h1 class="m-3">{{ $utusan_kampus }}</h1> --}}
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </section>
@endsection
