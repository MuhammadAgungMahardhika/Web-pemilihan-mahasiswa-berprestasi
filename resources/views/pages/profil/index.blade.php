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
                <h3 class="mb-3">{{ $title }}</h3>
            </div>
        </div>
    </div>
    {{-- Main Content --}}
    <div class="page-content">
        <section class="section">
            <div class="card shadow-sm">
                <div class="card-header">
                </div>

                <div class="card-body table-responsive">
                    <div class="row justify-content-center">
                        <div class="col-md-6 col-lg-6 col-12 shadow-sm  p-4">
                            <div class="row">
                                <input type="hidden" value="{{ Auth::user()->status }}" class="form-control"
                                    id="status">
                                <div class="col-md-6 form-group">
                                    <label for="username">Username</label>
                                    <input type="text" value="{{ Auth::user()->username }}" class="form-control"
                                        id="username" disabled>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="name">Nama Akun</label>
                                    <input type="text" value="{{ Auth::user()->name }}" class="form-control"
                                        id="name">
                                </div>

                                <div class="col-md-12 form-group">
                                    <label for="foto_url">Foto</label>
                                    <input type="file" id="foto_url" name="filepond" class="form-control"
                                        accept="image/*">
                                </div>
                                <div class="col-sm-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary me-1 mb-1"
                                        onclick="changePasswordModal()"><i class="fa fa-key"></i>
                                        Change
                                        password</button>
                                    <button type="submit" class="btn btn-success me-1 mb-1" onclick="update()"><i
                                            class="fa fa-save"></i>
                                        Save</button>
                                </div>
                            </div>
                        </div>
                        {{-- @if (Auth::user()->id_role == 1)
                            <div class="col-md-6 col-lg-6 col-12 shadow-sm  p-4">
                                <div class="row">
                                    <input type="hidden" value="{{ Auth::user()->mahasiswa->id_departmen }}"
                                        class="form-control" id="id_departmen">
                                    <div class="col-md-6 form-group">
                                        <label for="nim">Nim</label>
                                        <input type="text" value="{{ Auth::user()->mahasiswa->nim }}"
                                            class="form-control" id="nim" disabled>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="nama_mahasiswa">Nama</label>
                                        <input type="text" value="{{ Auth::user()->mahasiswa->nama }}"
                                            class="form-control" id="nama_mahasiswa" disabled>
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label for="nik">Nik</label>
                                        <input type="text" value="{{ Auth::user()->mahasiswa->nik }}"
                                            class="form-control" id="nik">
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label for="semester">Semester</label>
                                        <select name="semester" id="semester" class="form-control">
                                            <option value="1"
                                                {{ Auth::user()->mahasiswa->semester ? 'selected' : '' }}>1</option>
                                            <option value="2"
                                                {{ Auth::user()->mahasiswa->semester ? 'selected' : '' }}>2</option>
                                            <option value="3"
                                                {{ Auth::user()->mahasiswa->semester ? 'selected' : '' }}>3</option>
                                            <option value="4"
                                                {{ Auth::user()->mahasiswa->semester ? 'selected' : '' }}>4</option>
                                            <option value="5"
                                                {{ Auth::user()->mahasiswa->semester ? 'selected' : '' }}>5</option>
                                            <option value="6"
                                                {{ Auth::user()->mahasiswa->semester ? 'selected' : '' }}>6</option>
                                            <option value="7"
                                                {{ Auth::user()->mahasiswa->semester ? 'selected' : '' }}>7</option>
                                            <option value="8"
                                                {{ Auth::user()->mahasiswa->semester ? 'selected' : '' }}>8</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="jenis_kelamin">jenis kelamin</label>
                                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-control">
                                            <option value="perempuan"
                                                {{ Auth::user()->mahasiswa->jenis_kelamin == 'perempuan' ? 'selected' : '' }}>
                                                Perempuan</option>
                                            <option value="laki-laki"
                                                {{ Auth::user()->mahasiswa->jenis_kelamin == 'laki-laki' ? 'selected' : '' }}>
                                                Laki-laki</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="agama">Agama</label>
                                        <input type="text" value="{{ Auth::user()->mahasiswa->agama }}"
                                            class="form-control" name="agama" id="agama">
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label for="tempat_lahir">Tempat Lahir</label>
                                        <input type="text" value="{{ Auth::user()->mahasiswa->tempat_lahir }}"
                                            class="form-control" name="tempat_lahir" id="tempat_lahir">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="tanggal_lahir">Tanggal Lahir</label>
                                        <input type="date" value="{{ Auth::user()->mahasiswa->tgl_lahir }}"
                                            class="form-control" name="tanggal_lahir" id="tanggal_lahir">
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label for="no_hp">No Hp</label>
                                        <input type="number"
                                            value="{{ Auth::user()->mahasiswa->no_hp }}"class="form-control"
                                            name="no_hp" id="no_hp">
                                    </div>
                                    <div class="col-md-6 form-group ">
                                        <label for="alamat">Alamat</label>
                                        <input type="text" value="{{ Auth::user()->mahasiswa->alamat }}"
                                            class="form-control" name="alamat" id="alamat">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="nama_ayah">Nama Ayah</label>
                                        <input type="text" value="{{ Auth::user()->mahasiswa->nama_ayah }}"
                                            class="form-control" name="nama_ayah" id="nama_ayah">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="no_hp_ayah">No Hp Ayah</label>
                                        <input type="text" value="{{ Auth::user()->mahasiswa->no_hp_ayah }}"
                                            class="form-control" name="no_hp_ayah" id="no_hp_ayah">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="nama_ibu">Nama Ibu</label>
                                        <input type="text" value="{{ Auth::user()->mahasiswa->nama_ibu }}"
                                            class="form-control" name="nama_ibu" id="nama_ibu">
                                    </div>
                                </div>
                            </div>
                        @endif --}}
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script>
        const idUser = '{{ Auth::user()->id }}'
        const idMahasiswa = '{{ Auth::user()->id_mahasiswa }}'
        const idRole = '{{ Auth::user()->id_role }}'
        const fotoUrl = '{{ Auth::user()->foto_url }}'
    </script>

    <script src="{{ asset('assets/extensions/datatable/dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/app/profil.js') }}"></script>
@endsection
