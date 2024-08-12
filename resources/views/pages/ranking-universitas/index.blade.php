@extends('template.layout-vertical.main')

@section('container')
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3 class="mb-3">{{ $title }}</h3>
            </div>
        </div>
    </div>
    {{-- Main Content --}}
    <div class="page-content">
        <section class="section">
            <div class="card">
                <div class="card-header">
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nim</th>
                                    <th>Nama</th>
                                    <th>Fakultas</th>
                                    <th>Departemen</th>
                                    <th>Total Skor</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script src="{{ asset('assets/extensions/datatable/dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/app/ranking_universitas.js') }}"></script>
@endsection
