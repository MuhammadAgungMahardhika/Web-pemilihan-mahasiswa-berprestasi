@extends('template.layout-vertical.main')

@section('header')
    <!-- FilePond core JS & CSS -->
    <script src="{{ asset('assets/extensions/filepond/filepond.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/extensions/filepond/filepond.css') }}">

    <!-- FilePond Image Preview plugin JS & CSS -->
    <script src="{{ asset('assets/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.min.js') }}">
    </script>
    <link rel="stylesheet"
        href="{{ asset('assets/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.css') }}">

    <!-- FilePond Image Resize plugin JavaScript -->
    <script src="{{ asset('assets/extensions/filepond-plugin-image-resize/filepond-plugin-image-resize.min.js') }}">
    </script>

    <!-- FilePond Image validate size-->
    <script
        src="{{ asset('assets/extensions/filepond-plugin-file-validate-size/filepond-plugin-file-validate-size.min.js') }}">
    </script>
    <!-- FilePond Image validate type-->
    <script
        src="{{ asset('assets/extensions/filepond-plugin-file-validate-type/filepond-plugin-file-validate-type.min.js') }}">
    </script>
@endsection

@section('container')
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3 class="mb-3">{{ $title }} </h3>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="page-content">
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <a onclick="addModal()" class="btn btn-primary" title="Tambah Dokumen Prestasi"><i
                            class="fa fa-plus"></i></a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal upload</th>
                                    <th>Judul</th>
                                    <th>Capaian Unggulan</th>
                                    <th>Status</th>
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
    <script>
        console.log('{{ Auth()->user()->id_mahasiswa }}')
        const id_mahasiswa = '{{ Auth()->user()->id_mahasiswa }}'
    </script>

    <script src="{{ asset('assets/extensions/datatable/dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/app/dokumen_prestasi.js') }}"></script>
@endsection
