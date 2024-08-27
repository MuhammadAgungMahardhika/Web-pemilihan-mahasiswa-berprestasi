@extends('template.layout-vertical.main')


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


                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <p>Status</p>
                            @if ($data)
                                <p class="text-success">Sudah Penilaian</p>
                            @else
                                <p class="text-warning">Belum Penilaian</p>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 form-group">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="bg-primary">
                                        <th class="text-center text-white" colspan="3">
                                            Tingkat Fakultas
                                        </th>

                                    </tr>
                                    <tr>
                                        <th>Listening</th>
                                        <th>Speaking</th>
                                        <th>Writing</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $data ? $data->listening : '-' }}</td>
                                        <td>{{ $data ? $data->speaking : '-' }}</td>
                                        <td>{{ $data ? $data->writing : '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        @if ($data->listening_universitas)
                            <div class="col-6">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="bg-primary">
                                            <th class="text-center text-white" colspan="3">
                                                Tingkat Universitas
                                            </th>

                                        </tr>
                                        <tr>
                                            <th>Listening</th>
                                            <th>Speaking</th>
                                            <th>Writing</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ $data ? $data->listening_universitas : '-' }}</td>
                                            <td>{{ $data ? $data->speaking_universitas : '-' }}</td>
                                            <td>{{ $data ? $data->writing_universitas : '-' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </section>
    </div>
    <script>
        const id_mahasiswa = '{{ Auth()->user()->id_mahasiswa }}'
    </script>
@endsection
