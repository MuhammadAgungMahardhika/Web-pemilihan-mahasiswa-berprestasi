let table;

showData();

function showData() {
    table = $("#datatable").DataTable({
        processing: true,
        serverSide: true,
        ajax: "/api/bahasa-inggris/fakultas/data",
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
                data: "mahasiswa.departmen.nama_departmen",
                name: "mahasiswa.departmen.nama_departmen",
                orderable: true,
                searchable: true,
            },
            {
                data: "mahasiswa.nim",
                name: "mahasiswa.nim",
                orderable: true,
                searchable: true,
            },
            {
                data: "mahasiswa.nama",
                name: "mahasiswa.nama",
                orderable: true,
                searchable: true,
            },
            {
                data: "listening",
                name: "listening",
                orderable: true,
                searchable: true,
            },
            {
                data: "speaking",
                name: "speaking",
                orderable: true,
                searchable: true,
            },
            {
                data: "writing",
                name: "writing",
                orderable: true,
                searchable: true,
            },
            {
                data: null,
                className: "text-center",
                render: function (data, type, row) {
                    return `
                        <div class="row g-2 text-center">
                            <div class="col">
                                <a onclick="editModal('${row.id}')" class="btn btn-primary btn-sm"><i class="fa fa-info"></i> </a>
                            </div>
                            <div class="col">
                                <a onclick="deleteModal('${row.id}')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> </a>
                            </div>
                        </div>
                    `;
                },
            },
        ],
        order: [[0, "desc"]],
    });
}

function reloadData() {
    if (table) {
        table.ajax.reload();
    }
}
function addModal() {
    const modalHeader = "Tambah Bahasa Inggris";
    const modalBody = `
        <form class="form form-horizontal">
            <div class="form-body">
               <input type="hidden" value="${periode}" id="periode" class="form-control">
                <div class="row">
                    <div class="col-8 form-group">
                        <label for="id_mahasiswa">Mahasiswa <i class="text-danger">*</i></label>
                         <select class="form-select" id="id_mahasiswa" onclick="selectMahasiswa()">
                           
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4 form-group">
                        <label for="listening">Listening <i class="text-danger">*</i></label>
                        <input type="number" step="0.01" id="listening" class="form-control">
                    </div>
                    <div class="col-4 form-group">
                        <label for="speaking">Speaking <i class="text-danger">*</i></label>
                        <input type="number" step="0.01" id="speaking" class="form-control">
                    </div>
                    <div class="col-4 form-group">
                        <label for="writing">Writing <i class="text-danger">*</i></label>
                        <input type="number" step="0.01" id="writing" class="form-control">
                    </div>
                </div>
            </div>
        </form>
    `;
    const modalFooter = `<a class="btn btn-success btn-lg" onclick="save()"><i class="fa fa-save"> </i> Simpan</a>`;
    showLargeModal(modalHeader, modalBody, modalFooter);
}

function selectMahasiswa(mahasiswaId = null) {
    const mahasiswaOptionLenght = $("#id_mahasiswa option").length;
    if (mahasiswaOptionLenght != 0) {
        return;
    }
    $.ajax({
        type: "GET",
        url: `/api/mahasiswa`,
        dataType: "json",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
        },
        success: function (response) {
            const responseData = response.data;
            let dataOption = `<option value=""></option>`;
            responseData.forEach((r) => {
                dataOption += `<option value="${r.id}" ${
                    mahasiswaId != null && mahasiswaId == r.id ? "selected" : ""
                }>${r.nim} | ${r.nama}</option>`;
            });
            $("#id_mahasiswa").html(dataOption);
        },
        error: function (err) {
            result = null;
            let errorResponse = err.responseJSON;
            const errorMessage = errorResponse.message;
            const errorData = errorResponse.data;
            showToastErrorAlert(errorMessage + `<br>(${errorData})`);
        },
    });
}
function editModal(id) {
    $.ajax({
        type: "GET",
        url: `/api/bahasa-inggris/${id}`,
        success: function (response) {
            const { periode, id_mahasiswa, listening, speaking, writing } =
                response.data;
            const modalHeader = "Ubah Bahasa Inggris";
            const modalBody = `
                <form class="form form-horizontal">
                    <div class="form-body">
                        <input type="hidden" id="periode" value="${periode}" class="form-control">
                        <div class="row">
                            <div class="col-8 form-group">
                                <label for="id_mahasiswa">Mahasiswa <i class="text-danger">*</i></label>
                                <select class="form-select" id="id_mahasiswa" >
                                
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4 form-group">
                                <label for="listening">Listening <i class="text-danger">*</i></label>
                                <input type="number" step="0.01" id="listening" value="${listening}" class="form-control">
                            </div>
                            <div class="col-4 form-group">
                                <label for="speaking">Speaking <i class="text-danger">*</i></label>
                                <input type="number" step="0.01" id="speaking" value="${speaking}" class="form-control">
                            </div>
                            <div class="col-4 form-group">
                                <label for="writing">Writing <i class="text-danger">*</i></label>
                                <input type="number" step="0.01" id="writing" value="${writing}" class="form-control">
                            </div>
                        </div>
                    </div>
                </form>
            `;
            const modalFooter = `<a class="btn btn-success btn-lg" onclick="update('${id}')"><i class="fa fa-save"> </i> Simpan Perubahan</a>`;
            showLargeModal(modalHeader, modalBody, modalFooter);
            selectMahasiswa(id_mahasiswa);
        },
        error: function (err) {
            const errorMessage = err.responseJSON.message;
            showToastErrorAlert(errorMessage);
        },
    });
}

function deleteModal(id) {
    const modalHeader = "Hapus Data Bahasa Inggris";
    const modalBody = `Apakah Anda Yakin Menghapus Data Ini?`;
    const modalFooter = `<a class="btn btn-danger btn-lg" onclick="deleteItem('${id}')">Hapus</a>`;
    showModal(modalHeader, modalBody, modalFooter);
}

// API
function save() {
    const periode = $("#periode").val();
    const id_mahasiswa = $("#id_mahasiswa").val();
    const listening = $("#listening").val();
    const speaking = $("#speaking").val();
    const writing = $("#writing").val();

    let data = { periode, id_mahasiswa, listening, speaking, writing };

    $.ajax({
        type: "POST",
        url: `/api/bahasa-inggris`,
        contentType: "application/json",
        headers: { "X-CSRF-TOKEN": csrfToken },
        data: JSON.stringify(data),
        success: function (response) {
            showToastSuccessAlert(response.message);
            closeLargeModal();
            reloadData();
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
    const periode = $("#periode").val();
    const id_mahasiswa = $("#id_mahasiswa").val();
    const listening = $("#listening").val();
    const speaking = $("#speaking").val();
    const writing = $("#writing").val();

    let data = { periode, id_mahasiswa, listening, speaking, writing };

    $.ajax({
        type: "PATCH",
        url: `/api/bahasa-inggris/${id}`,
        data: JSON.stringify(data),
        contentType: "application/json",
        headers: { "X-CSRF-TOKEN": csrfToken },
        success: function (response) {
            showToastSuccessAlert(response.message);
            closeLargeModal();
            reloadData();
        },
        error: function (err) {
            showToastErrorAlert(err.responseJSON.message);
        },
    });
}

function deleteItem(id) {
    $.ajax({
        type: "DELETE",
        url: `/api/bahasa-inggris/${id}`,
        contentType: "application/json",
        headers: { "X-CSRF-TOKEN": csrfToken },
        success: function (response) {
            showToastSuccessAlert(response.message);
            closeModal();
            reloadData();
        },
        error: function (err) {
            let errorResponse = err.responseJSON;
            const errorMessage = errorResponse.message;
            const errorData = errorResponse.data;
            showToastErrorAlert(errorMessage + `<br>(${errorData})`);
        },
    });
}
