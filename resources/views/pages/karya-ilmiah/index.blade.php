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
                    @if ($data)
                        @php
                            $penilaianKaryaIlmiah = $data->penilaian_karya_ilmiah;
                            $isEvaluatedByUser = [];
                            foreach ($penilaianKaryaIlmiah as $penilaian) {
                                if ($penilaian->skor_fakultas || $penilaian->skor_universitas) {
                                    $isEvaluatedByUser[] = [
                                        'user' => $penilaian->user->name ?? '-',
                                        'created_at' => $penilaian->created_at->format('d-m-Y H:i:s'),
                                    ];
                                }
                            }
                        @endphp

                        @if ($penilaianKaryaIlmiah->isNotEmpty())
                            <a class="btn btn-success" title="Dokumen Diterima" disabled>
                                <i class="fa fa-check"></i> Karya Ilmiah Sudah dinilai
                            </a>
                            <p class="fst-italic"> Dinilai oleh:
                                @if (count($isEvaluatedByUser) > 0)
                                    <ul>
                                        @foreach ($isEvaluatedByUser as $evaluation)
                                            <li>{{ $evaluation['user'] }} pada {{ $evaluation['created_at'] }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    -
                                @endif
                            </p>
                        @else
                            <a onclick="editModal('{{ $data->id }}')" class="btn btn-primary"
                                title="Perbarui Karya Ilmiah ">
                                <i class="fa fa-plus"></i> Perbarui Karya Ilmiah
                            </a>
                        @endif
                    @else
                        <a onclick="addModal()" class="btn btn-primary" title="Upload Karya Ilmiah ">
                            <i class="fa fa-plus"></i> Upload Karya Ilmiah
                        </a>
                    @endif
                </div>
                <div class="card-body">
                    <form class="form form-horizontal">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-6 form-group">
                                    <label for="status">Status</label>
                                    @if ($data)
                                        @if ($data->penilaian_karya_ilmiah->isNotEmpty())
                                            <p class="text-success">Karya Ilmiah anda sudah dinilai</p>
                                        @else
                                            <p class="text-warning">Karya Ilmiah sudah diupload, Menunggu Penilaian Juri</p>
                                        @endif
                                    @else
                                        <p>Karya Ilmiah belum diupload, silahkan upload Karya Ilmiah</p>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 form-group">
                                    <label for="judul">Judul Karya Ilmiah </label>
                                    @if ($data)
                                        <P>{{ $data->judul }}</P>
                                    @else
                                        <p>-</p>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 form-group">
                                    <label for="dokumen_url">File Karya Ilmiah</label>
                                    @if ($data && $data->dokumen_url)
                                        <div>
                                            <embed src="{{ asset('storage/karya_ilmiah/' . $data->dokumen_url) }}"
                                                type="application/pdf" width="100%" height="600px" />
                                        </div>
                                    @else
                                        <p>Belum ada karya ilmiah</p>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
    <script>
        const periode = "{{ session('portal')->periode }}"
        const id_mahasiswa = '{{ Auth()->user()->id_mahasiswa }}'
    </script>

    <script src="{{ asset('assets/app/karya_ilmiah.js') }}"></script>
@endsection
