let table;

showData();

function showData() {
    table = $("#datatable").DataTable({
        processing: true,
        serverSide: true,
        ajax: `/api/mahasiswa/departmen/${idDepartmen}`,
        autoWidth: false,
        columnDefs: [
            {
                targets: -1,
                width: "150px",
            },
        ],
        columns: [
            {
                data: null,
                name: "id",
                render: function (data, type, row, meta) {
                    return meta.row + 1;
                },
                orderable: true,
                searchable: false,
            },
            {
                data: "nim",
                name: "nim",
                orderable: true,
                searchable: true,
            },
            {
                data: "nama",
                name: "nama",
                orderable: true,
                searchable: true,
            },
            {
                data: "semester",
                name: "semester",
                orderable: true,
                searchable: true,
            },
            {
                data: "jenis_kelamin",
                name: "jenis_kelamin",
                orderable: true,
                searchable: true,
            },
            {
                data: "user.status",
                name: "user.status",
                orderable: false,
                searchable: true,
            },
            {
                data: null,
                className: "text-center",
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    console.log(row);
                    const activateUserButton =
                        row.user.status == "nonaktif"
                            ? `<a onclick="activateUser('${row.user.id}')" title="aktivasi akun" class="btn btn-primary btn-sm"><i class="fa fa-key"></i> </a>`
                            : `<a onclick="deactivateUser('${row.user.id}')" title="nonaktifkan akun" class="btn btn-primary btn-sm"><i class="fa fa-lock"></i> </a>`;
                    return `
                        <div class="row g-2 text-center">
                            <div class="col">
                                ${activateUserButton}
                            </div>
                            <div class="col">
                                <a onclick="editModal('${row.id}')" title="ubah data" class="btn btn-primary btn-sm"><i class="fa fa-info"></i> </a>
                            </div>
                            <div class="col">
                                <a onclick="deleteModal('${row.id}', '${row.nama}')" title="hapus data" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> </a>
                            </div>
                        </div>
                    `;
                },
            },
        ],
        order: [[0, "desc"]],
    });
}

function activateUser(id) {
    let data = {
        id: id,
    };
    $.ajax({
        type: "POST",
        url: `/api/user/activate`,
        contentType: "application/json",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
        },
        data: JSON.stringify(data),
        success: function (response) {
            const message = response.message;
            showToastSuccessAlert(message);
            closeLargeModal();
            return reloadData();
        },
        error: function (err) {
            let errorResponse = err.responseJSON;
            const errorMessage = errorResponse.message;
            const errorData = errorResponse.data;
            showToastErrorAlert(errorMessage + `<br>(${errorData})`);
        },
    });
}
function deactivateUser(id) {
    let data = {
        id: id,
    };
    $.ajax({
        type: "POST",
        url: `/api/user/deactivate`,
        contentType: "application/json",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
        },
        data: JSON.stringify(data),
        success: function (response) {
            const message = response.message;
            showToastSuccessAlert(message);
            closeLargeModal();
            return reloadData();
        },
        error: function (err) {
            let errorResponse = err.responseJSON;
            const errorMessage = errorResponse.message;
            const errorData = errorResponse.data;
            showToastErrorAlert(errorMessage + `<br>(${errorData})`);
        },
    });
}
function reloadData() {
    if (table) {
        table.ajax.reload();
    }
}

function addModal() {
    const modalHeader = "Tambah Mahasiswa";
    const modalBody = `
        <form class="form form-horizontal">
            <div class="form-body"> 
                <div class="row">
                    <div class="col-4 form-group">
                        <label for="nik">NIK </label>
                        <input type="text" id="nik" class="form-control" autocomplete="one-time-code">
                    </div>
                    <div class="col-4 form-group">
                        <label for="nim">NIM <i class="text-danger">*</i></label>
                        <input type="text" id="nim" class="form-control" autocomplete="one-time-code">
                    </div>
                    <div class="col-4 form-group">
                        <label for="nama">Nama <i class="text-danger">*</i></label>
                        <input type="text" id="nama" class="form-control" autocomplete="one-time-code">
                    </div>
                    <div class="col-4 form-group">
                        <label for="jenis_kelamin">Jenis Kelamin <i class="text-danger">*</i></label>
                        <select id="jenis_kelamin" class="form-control">
                            <option value="perempuan">Perempuan</option>
                            <option value="laki-laki">Laki-laki</option>
                        </select>
                    </div>
                    <div class="col-4 form-group">
                        <label for="semester">Semester <i class="text-danger">*</i></label>
                        <select id="semester" class="form-control">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                        </select>
                    </div>
                    <div class="col-4 form-group">
                        <label for="ipk">Ipk</label>
                        <input type="number"  step="0.01" id="ipk" class="form-control">
                    </div>
                    <div class="col-4 form-group">
                        <label for="no_hp">No HP</label>
                        <input type="text" id="no_hp" class="form-control">
                    </div>
                    
                    <div class="col-4 form-group">
                        <label for="nama_ayah">Nama Ayah</label>
                        <input type="text" id="nama_ayah" class="form-control">
                    </div>
                    <div class="col-4 form-group">
                        <label for="no_hp_ayah">No HP Ayah</label>
                        <input type="text" id="no_hp_ayah" class="form-control">
                    </div>
                    <div class="col-4 form-group">
                        <label for="nama_ibu">Nama Ibu</label>
                        <input type="text" id="nama_ibu" class="form-control">
                    </div>
                    <div class="col-4 form-group">
                        <label for="alamat">Alamat</label>
                        <textarea id="alamat" class="form-control"></textarea>
                    </div>
                </div>
            </div>
        </form>
    `;
    const modalFooter = `<a class="btn btn-success btn-lg" onclick="save()"><i class="fa fa-save"> </i> Simpan</a>`;
    showLargeModal(modalHeader, modalBody, modalFooter);
}

function editModal(id) {
    $.ajax({
        type: "GET",
        url: `/api/mahasiswa/${id}`,
        success: function (response) {
            let {
                id,
                nik,
                nim,
                nama,
                ipk,
                semester,
                jenis_kelamin,
                no_hp,
                alamat,
                nama_ayah,
                no_hp_ayah,
                nama_ibu,
            } = response.data;
            const modalHeader = "Ubah Mahasiswa";
            const modalBody = `
                <form class="form form-horizontal">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-4 form-group">
                                <label for="nik">Nik</label>
                                <input type="text" id="nik" value="${
                                    nik || ""
                                }" class="form-control" autocomplete="one-time-code">
                            </div>
                            <div class="col-4 form-group">
                                <label for="nim">Nim <i class="text-danger">*</i></label>
                                <input type="text" id="nim" value="${nim}" class="form-control" autocomplete="one-time-code">
                            </div>
                            <div class="col-4 form-group">
                                <label for="nama">Nama <i class="text-danger">*</i></label>
                                <input type="text" id="nama" value="${nama}" class="form-control" autocomplete="one-time-code">
                            </div>
                            <div class="col-4 form-group">
                                <label for="jenis_kelamin">Jenis Kelamin <i class="text-danger">*</i></label>
                                <select id="jenis_kelamin" class="form-control">
                                    <option value="perempuan" ${
                                        jenis_kelamin === "perempuan"
                                            ? "selected"
                                            : ""
                                    }>Perempuan</option>
                                    <option value="laki-laki" ${
                                        jenis_kelamin === "laki-laki"
                                            ? "selected"
                                            : ""
                                    }>Laki-laki</option>
                                </select>
                            </div>
                            <div class="col-4 form-group">
                                <label for="semester">Semester <i class="text-danger">*</i></label>
                                <select id="semester" class="form-control">
                                    <option value="1" ${
                                        semester === "1" ? "selected" : ""
                                    }>1</option>
                                    <option value="2" ${
                                        semester === "2" ? "selected" : ""
                                    }>2</option>
                                    <option value="3" ${
                                        semester === "3" ? "selected" : ""
                                    }>3</option>
                                    <option value="4" ${
                                        semester === "4" ? "selected" : ""
                                    }>4</option>
                                    <option value="5" ${
                                        semester === "5" ? "selected" : ""
                                    }>5</option>
                                    <option value="6" ${
                                        semester === "6" ? "selected" : ""
                                    }>6</option>
                                    <option value="7" ${
                                        semester === "7" ? "selected" : ""
                                    }>7</option>
                                    <option value="8" ${
                                        semester === "8" ? "selected" : ""
                                    }>8</option>
                                </select>
                            </div>
                            <div class="col-4 form-group">
                                <label for="ipk">Ipk</label>
                                <input type="number" step="0.01" id="ipk" value="${
                                    ipk || ""
                                }" class="form-control">
                            </div>
                            <div class="col-4 form-group">
                                <label for="no_hp">No HP</label>
                                <input type="text" id="no_hp" value="${
                                    no_hp || ""
                                }" class="form-control">
                            </div>
                            <div class="col-4 form-group">
                                <label for="nama_ayah">Nama Ayah</label>
                                <input type="text" id="nama_ayah" value="${
                                    nama_ayah || ""
                                }" class="form-control">
                            </div>
                            <div class="col-4 form-group">
                                <label for="no_hp_ayah">No HP Ayah</label>
                                <input type="text" id="no_hp_ayah" value="${
                                    no_hp_ayah || ""
                                }" class="form-control">
                            </div>
                            <div class="col-4 form-group">
                                <label for="nama_ibu">Nama Ibu</label>
                                <input type="text" id="nama_ibu" value="${
                                    nama_ibu || ""
                                }" class="form-control">
                            </div>
                            <div class="col-4 form-group">
                                <label for="alamat">Alamat</label>
                                <textarea id="alamat" class="form-control">${
                                    alamat || ""
                                }</textarea>
                            </div>
                        </div>
                    </div>
                </form>
            `;
            const modalFooter = `<a class="btn btn-success btn-lg" onclick="update('${id}')"><i class="fa fa-save"> </i> Simpan Perubahan</a>`;
            showLargeModal(modalHeader, modalBody, modalFooter);
        },
        error: function (err) {
            let errorResponse = err.responseJSON;
            const errorMessage = errorResponse.message;
            const errorData = errorResponse.data;
            showToastErrorAlert(errorMessage + `<br>(${errorData})`);
        },
    });
}

function deleteModal(id, nama) {
    const modalHeader = "Hapus Mahasiswa";
    const modalBody = `Apakah Anda Yakin Menghapus Mahasiswa ${nama} Ini?`;
    const modalFooter = `<a class="btn btn-danger btn-lg" onclick="deleteItem('${id}')">Hapus</a>`;
    showModal(modalHeader, modalBody, modalFooter);
}

// API
function save() {
    const nik = $("#nik").val();
    const nim = $("#nim").val();
    const nama = $("#nama").val();
    const ipk = $("#ipk").val();
    const semester = $("#semester").val();
    const jenis_kelamin = $("#jenis_kelamin").val();
    const no_hp = $("#no_hp").val();
    const alamat = $("#alamat").val();
    const nama_ayah = $("#nama_ayah").val();
    const no_hp_ayah = $("#no_hp_ayah").val();
    const nama_ibu = $("#nama_ibu").val();

    let data = {
        nik: nik,
        id_departmen: idDepartmen,
        nim: nim,
        nama: nama,
        ipk: ipk,
        semester: semester,
        jenis_kelamin: jenis_kelamin,
        no_hp: no_hp,
        alamat: alamat,
        nama_ayah: nama_ayah,
        no_hp_ayah: no_hp_ayah,
        nama_ibu: nama_ibu,
    };

    $.ajax({
        type: "POST",
        url: `/api/mahasiswa`,
        contentType: "application/json",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
        },
        data: JSON.stringify(data),
        success: function (response) {
            const message = response.message;
            showToastSuccessAlert(message);
            closeLargeModal();
            return reloadData();
        },
        error: function (err) {
            let errorResponse = err.responseJSON;
            const errorMessage = errorResponse.message;
            const errorData = errorResponse.data;
            showToastErrorAlert(errorMessage + `<br>(${errorData})`);
        },
    });
}

function update(id) {
    const nik = $("#nik").val();
    const nim = $("#nim").val();
    const nama = $("#nama").val();
    const semester = $("#semester").val();
    const jenis_kelamin = $("#jenis_kelamin").val();
    const no_hp = $("#no_hp").val();
    const alamat = $("#alamat").val();
    const nama_ayah = $("#nama_ayah").val();
    const no_hp_ayah = $("#no_hp_ayah").val();
    const nama_ibu = $("#nama_ibu").val();

    let data = {
        nik: nik,
        nim: nim,
        id_departmen: idDepartmen,
        nama: nama,
        semester: semester,
        jenis_kelamin: jenis_kelamin,
        no_hp: no_hp,
        alamat: alamat,
        nama_ayah: nama_ayah,
        no_hp_ayah: no_hp_ayah,
        nama_ibu: nama_ibu,
    };

    $.ajax({
        type: "PATCH",
        url: `/api/mahasiswa/${id}`,
        data: JSON.stringify(data),
        contentType: "application/json",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
        },
        success: function (response) {
            const successMessage = response.message;
            showToastSuccessAlert(successMessage);
            closeLargeModal();
            return reloadData();
        },
        error: function (err) {
            let errorResponse = err.responseJSON;
            const errorMessage = errorResponse.message;
            const errorData = errorResponse.data;
            showToastErrorAlert(errorMessage + `<br>(${errorData})`);
        },
    });
}

function deleteItem(id) {
    $.ajax({
        type: "DELETE",
        url: `/api/mahasiswa/${id}`,
        contentType: "application/json",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
        },
        success: function (response) {
            const message = response.message;
            showToastSuccessAlert(message);
            closeModal();
            return reloadData();
        },
        error: function (err) {
            let errorResponse = err.responseJSON;
            const errorMessage = errorResponse.message;
            const errorData = errorResponse.data;
            showToastErrorAlert(errorMessage + `<br>(${errorData})`);
        },
    });
}
