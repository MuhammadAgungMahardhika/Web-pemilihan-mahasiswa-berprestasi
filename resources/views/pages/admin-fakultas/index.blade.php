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
                    <a onclick="addModal()" class="btn btn-primary" title="Tambah User"><i class="fa fa-plus"></i></a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Fakultas</th>
                                    <th>Username</th>
                                    <th>Nama</th>
                                    <th>Status Akun</th>
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
    <script src="{{ asset('assets/app/admin_fakultas.js') }}"></script>
@endsection
