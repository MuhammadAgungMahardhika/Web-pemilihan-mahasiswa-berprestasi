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
                        <div class="col-6 form-group">
                            <p>Status</p>
                            @if ($data)
                                <p class="text-success">Sudah Penilaian</p>
                                <p class="fst-italic"> Diinput oleh : {{ $data->user ? $data->user->name : '-' }}, pada
                                    {{ $data->updated_at }}</p>
                            @else
                                <p class="text-warning">Belum Penilaian</p>
                            @endif
                            <table class="table table-bordered">
                                <thead>
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
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script>
        const id_mahasiswa = '{{ Auth()->user()->id_mahasiswa }}'
    </script>
@endsection
