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
                    <p class=" p-2"><i class="text-danger">*</i> Ranking yang muncul adalah Mahasiswa yang
                        sudah dinilai ( <i class="text-success fw-bold">Karya Ilmiah, Bahasa Inggris, Dokumen Prestasi</i>
                        )</p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nim</th>
                                    <th>Nama</th>
                                    <th>Departemen</th>
                                    <th>Ipk</th>
                                    <th>Karya Ilmiah (a)</th>
                                    <th>Bahasa Inggris (b)</th>
                                    <th>Dokumen Prestasi (c)</th>
                                    <th>Total Skor (a+b+c)</th>
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
        const idFakultas = '{{ Auth::user()->id_fakultas }}'
    </script>
    <script src="{{ asset('assets/extensions/datatable/dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/app/ranking_fakultas.js') }}"></script>
@endsection
