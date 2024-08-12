@extends('template.layout-1-column.main')
@section('container')
    <script>
        let checkTheme = localStorage.getItem("theme");
        checkTheme == "dark" ? localStorage.setItem("theme", "light") : ""
    </script>
    <div class="container">
        <div class="row">
            <div class="col-6">
                <input class="form-check-input  me-0" type="hidden" id="toggle-dark">
                <div id="auth-left" class="p-2">
                    <a href=""><img src="{{ asset('assets/static/images/logo/unand.png') }}" alt="Logo"
                            width="120"></a>
                    <h2 class="auth-title">Sistem Informasi Pemilihan Mahasiswa Berprestasi</h2>
                    <p class="auth-subtitle mb-5">Silahkan Login Dengan Menggunakan Akun Anda</p>
                    <div id="loginForm">
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input class="form-control form-control-xl" type="text" name="username"
                                placeholder="username" id="username">
                            <div class="form-control-icon">
                                <i class="bi bi-person"></i>
                            </div>
                        </div>
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="password" class="form-control form-control-xl" name="password"
                                placeholder="Password" placeholder="Password" id="password">
                            <div class="form-control-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                        </div>
                        <div class="form-check form-check-lg d-flex align-items-end">
                            <input class="form-check-input me-2" type="checkbox" name="remember" id="flexCheckDefault">
                            <label class="form-check-label text-gray-600" for="flexCheckDefault">
                                Keep me logged in
                            </label>
                        </div>
                        <button type="submit" onclick="login()" class="btn btn-primary btn-block btn-lg shadow-lg mt-5"
                            id="btnLogin">Log
                            in</button>
                        {{-- <a type="submit" href="{{ url('forgot-password') }}" class="mt-4" id="btnLogin">Forgot password
                            ?</a> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function login() {
            let username = $('#username').val()
            let password = $('#password').val()

            // validation 
            if (username.length <= 0) {
                return showToastWarningAlert('Please check your username address')
            }

            if (password.length <= 0) {
                return showToastWarningAlert('Please check your password')
            }

            let data = {
                username: username,
                password: password
            }
            console.log(data)
            $.ajax({
                type: "POST",
                url: '{{ url('login') }}',
                contentType: "application/json",
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: JSON.stringify(data),
                success: function(r) {
                    if (r) {
                        showSuccessAlert("Login success", window.location = "{{ url('dashboard') }}")
                    }
                },
                error: function(e) {
                    const response = e.responseJSON
                    console.log(response)
                    const status = e.status
                    const message = e.responseText
                    if (status == 401) {
                        showToastErrorAlert(message)
                    } else if (status == 422) {
                        showToastErrorAlert(e.responseJSON.message)
                    } else {
                        showToastErrorAlert("Internal Server error")
                    }

                }
            })
        }
    </script>
@endsection
