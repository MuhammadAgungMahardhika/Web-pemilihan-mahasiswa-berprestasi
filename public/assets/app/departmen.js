let table;

showData();

function showData() {
    table = $("#datatable").DataTable({
        processing: true,
        serverSide: true,
        ajax: "/api/departmen/data",
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
                data: "nama_departmen",
                name: "nama_departmen",
                orderable: true,
                searchable: true,
            },
            {
                data: "kepala_departmen",
                name: "kepala_departmen",
                orderable: true,
                searchable: true,
            },
            {
                data: "contact_number",
                name: "contact_number",
                orderable: true,
                searchable: true,
            },
            {
                data: "email",
                name: "email",
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
                                <a onclick="deleteModal('${row.id}', '${row.nama_departmen}')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> </a>
                            </div>
                        </div>
                    `;
                },
            },
        ],
    });
}

function reloadData() {
    if (table) {
        table.ajax.reload();
    }
}

function addModal() {
    const modalHeader = "Tambah Departmen";
    const modalBody = `
        <form class="form form-horizontal">
            <div class="form-body"> 
                <div class="row">
                    <div class="col-4 form-group">
                        <label for="nama_departmen">Nama Departmen <i class="text-danger">*</i></label>
                        <input type="text" id="nama_departmen" class="form-control" autocomplete="one-time-code">
                    </div>
                    <div class="col-4 form-group">
                        <label for="kepala_departmen">Kepala Departmen <i class="text-danger">*</i></label>
                        <input type="text" id="kepala_departmen" class="form-control">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="id_fakultas">Fakultas <i class="text-danger">*</i></label>
                        <select class="form-select" id="id_fakultas">
                           
                        </select>
                    </div>
                    <div class="col-4 form-group">
                        <label for="contact_number">Contact Number</label>
                        <input type="text" id="contact_number" class="form-control">
                    </div>
                    <div class="col-4 form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" class="form-control">
                    </div>
                </div>
            </div>
        </form>
    `;
    const modalFooter = `<a class="btn btn-success btn-lg" onclick="save()"><i class="fa fa-save"> </i> Simpan</a>`;
    showLargeModal(modalHeader, modalBody, modalFooter);
    selectFakultas();
}
function selectFakultas(selectedId = null) {
    $.ajax({
        type: "GET",
        url: `/api/fakultas`,
        dataType: "json",
        success: function (response) {
            const responseData = response.data;
            let dataOption = `<option value=""></option>`;
            responseData.forEach((r) => {
                dataOption += `<option value="${r.id}" ${
                    selectedId == r.id ? "selected" : ""
                }>${r.nama_fakultas}</option>`;
            });
            $("#id_fakultas").html(dataOption);
        },
    });
}

function editModal(id) {
    $.ajax({
        type: "GET",
        url: `/api/departmen/${id}`,
        success: function (response) {
            let {
                id,
                nama_departmen,
                kepala_departmen,
                id_fakultas,
                contact_number,
                email,
            } = response.data;
            const modalHeader = "Ubah Departmen";
            const modalBody = `
                <form class="form form-horizontal">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-4 form-group">
                                <label for="nama_departmen">Nama Departmen <i class="text-danger">*</i></label>
                                <input type="text" id="nama_departmen" value="${nama_departmen}" class="form-control" autocomplete="one-time-code">
                            </div>
                            <div class="col-4 form-group">
                                <label for="kepala_departmen">Kepala Departmen</label>
                                <input type="text" id="kepala_departmen" value="${kepala_departmen}" class="form-control">
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="id_fakultas">Fakultas <i class="text-danger">*</i></label>
                                <select class="form-select" id="id_fakultas">
                                
                                </select>
                            </div>
                            <div class="col-4 form-group">
                                <label for="contact_number">Contact Number</label>
                                <input type="text" id="contact_number" value="${
                                    contact_number || ""
                                }" class="form-control">
                            </div>
                            <div class="col-4 form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" value="${
                                    email || ""
                                }" class="form-control">
                            </div>
                        </div>
                    </div>
                </form>
            `;
            const modalFooter = `<a class="btn btn-success btn-lg" onclick="update('${id}')"><i class="fa fa-save"> </i> Simpan Perubahan</a>`;
            showLargeModal(modalHeader, modalBody, modalFooter);
            selectFakultas(id_fakultas);
        },
        error: function (err) {
            let errorResponse = err.responseJSON;
            const errorMessage = errorResponse.message;
            const errorData = errorResponse.data;
            showToastErrorAlert(errorMessage + `<br>(${errorData})`);
        },
    });
}

function deleteModal(id, nama_departmen) {
    const modalHeader = "Hapus Departmen";
    const modalBody = `Apakah Anda Yakin Menghapus Departmen ${nama_departmen} Ini?`;
    const modalFooter = `<a class="btn btn-danger btn-lg" onclick="deleteItem('${id}')">Hapus</a>`;
    showModal(modalHeader, modalBody, modalFooter);
}

// API
function save() {
    const nama_departmen = $("#nama_departmen").val();
    const kepala_departmen = $("#kepala_departmen").val();
    const id_fakultas = $("#id_fakultas").val();
    const contact_number = $("#contact_number").val();
    const email = $("#email").val();

    let data = {
        nama_departmen,
        kepala_departmen,
        id_fakultas,
        contact_number,
        email,
    };

    $.ajax({
        type: "POST",
        url: `/api/departmen`,
        contentType: "application/json",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
        },
        data: JSON.stringify(data),
        success: function (response) {
            const message = response.message;
            showToastSuccessAlert(message);
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
    const nama_departmen = $("#nama_departmen").val();
    const id_fakultas = $("#id_fakultas").val();
    const kepala_departmen = $("#kepala_departmen").val();
    const contact_number = $("#contact_number").val();
    const email = $("#email").val();

    let data = {
        nama_departmen,
        id_fakultas,
        kepala_departmen,
        contact_number,
        email,
    };

    $.ajax({
        type: "PUT",
        url: `/api/departmen/${id}`,
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

function deleteItem(id) {
    $.ajax({
        type: "DELETE",
        url: `/api/departmen/${id}`,
        headers: {
            "X-CSRF-TOKEN": csrfToken,
        },
        success: function (response) {
            const message = response.message;
            showToastSuccessAlert(message);
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
