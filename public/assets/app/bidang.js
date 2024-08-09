let table;

showData();

function showData() {
    table = $("#datatable").DataTable({
        processing: true,
        serverSide: true,
        ajax: "/api/bidang/data",
        autoWidth: false, // Enable auto-width adjustment
        columnDefs: [
            {
                targets: -1, // Apply to action
                width: "150px", // Automatically adjust width based on content
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
                data: "nama",
                name: "nama",
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
                                <a onclick="deleteModal('${row.id}', '${row.nama}')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> </a>
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
    const modalHeader = "Tambah Bidang";
    const modalBody = `
        <form class="form form-horizontal">
            <div class="form-body"> 
                <div class="row">
                    <div class="col form-group">
                        <label for="nama">Nama Bidang <i class="text-danger">*</i></label>
                        <input type="text" id="nama" class="form-control" autocomplete="one-time-code">
                    </div>
                </div>
            </div>
        </form>
    `;
    const modalFooter = `<a class="btn btn-success btn-lg" onclick="save()"><i class="fa fa-save"> </i> Simpan</a>`;
    showModal(modalHeader, modalBody, modalFooter);
}

function editModal(id) {
    $.ajax({
        type: "GET",
        url: `/api/bidang/${id}`,
        success: function (response) {
            let { id, nama } = response.data;
            const modalHeader = "Ubah Bidang";
            const modalBody = `
                <form class="form form-horizontal">
                    <div class="form-body">
                        <div class="row">
                            <div class="col form-group">
                                <label for="nama">Nama Bidang <i class="text-danger">*</i></label>
                                <input type="text" id="nama" value="${nama}" class="form-control" autocomplete="one-time-code">
                            </div>
                        </div>
                    </div>
                </form>
            `;
            const modalFooter = `<a class="btn btn-success btn-lg" onclick="update('${id}')"><i class="fa fa-save"> </i> Simpan Perubahan</a>`;
            showModal(modalHeader, modalBody, modalFooter);
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
    const modalHeader = "Hapus Bidang";
    const modalBody = `Apakah Anda Yakin Menghapus Bidang ${nama} Ini?`;
    const modalFooter = `<a class="btn btn-danger btn-lg" onclick="deleteItem('${id}')">Hapus</a>`;
    showModal(modalHeader, modalBody, modalFooter);
}

// API
function save() {
    const nama = $("#nama").val();

    let data = {
        nama: nama,
    };

    $.ajax({
        type: "POST",
        url: `/api/bidang`,
        contentType: "application/json",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
        },
        data: JSON.stringify(data),
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

function update(id) {
    const nama = $("#nama").val();

    let data = {
        nama: nama,
    };

    $.ajax({
        type: "PATCH",
        url: `/api/bidang/${id}`,
        data: JSON.stringify(data),
        contentType: "application/json",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
        },
        success: function (response) {
            const successMessage = response.message;
            showToastSuccessAlert(successMessage);
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

function deleteItem(id) {
    $.ajax({
        type: "DELETE",
        url: `/api/bidang/${id}`,
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
