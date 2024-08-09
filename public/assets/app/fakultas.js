let table;

showData();

function showData() {
    table = $("#datatable").DataTable({
        processing: true,
        serverSide: true,
        ajax: "/api/fakultas/data",
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
                data: "nama_fakultas", // Change to the appropriate field for Fakultas
                name: "nama_fakultas",
                orderable: true,
                searchable: true,
            },
            {
                data: "dekan", // Include Dekan
                name: "dekan",
                orderable: true,
                searchable: true,
            },
            {
                data: "contact_number", // Include Contact Number
                name: "contact_number",
                orderable: true,
                searchable: true,
            },
            {
                data: "email", // Include Email
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
                                <a onclick="deleteModal('${row.id}', '${row.nama_fakultas}')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> </a>
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
    const modalHeader = "Tambah Fakultas";
    const modalBody = `
        <form class="form form-horizontal">
            <div class="form-body"> 
                <div class="row">
                    <div class="col-6 form-group">
                        <label for="nama_fakultas">Nama Fakultas <i class="text-danger">*</i></label>
                        <input type="text" id="nama_fakultas" class="form-control" autocomplete="one-time-code">
                    </div>
                    <div class="col-6 form-group">
                        <label for="dekan">Dekan <i class="text-danger">*</i></label>
                        <input type="text" id="dekan" class="form-control" autocomplete="one-time-code">
                    </div>
                    <div class="col-6 form-group">
                        <label for="contact_number">Contact Number </label>
                        <input type="text" id="contact_number" class="form-control" autocomplete="one-time-code">
                    </div>
                    <div class="col-6 form-group">
                        <label for="email">Email </label>
                        <input type="email" id="email" class="form-control" autocomplete="one-time-code">
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
        url: `/api/fakultas/${id}`, // Update the API endpoint for Fakultas
        success: function (response) {
            let { id, nama_fakultas, dekan, contact_number, email } =
                response.data;
            const modalHeader = "Ubah Fakultas";
            const modalBody = `
                <form class="form form-horizontal">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-6 form-group">
                                <label for="nama_fakultas">Nama Fakultas <i class="text-danger">*</i></label>
                                <input type="text" id="nama_fakultas" value="${nama_fakultas}" class="form-control" autocomplete="one-time-code">
                            </div>
                            <div class="col-6 form-group">
                                <label for="dekan">Dekan <i class="text-danger">*</i></label>
                                <input type="text" id="dekan" value="${dekan}" class="form-control" autocomplete="one-time-code">
                            </div>
                            <div class="col-6 form-group">
                                <label for="contact_number">Contact Number </label>
                                <input type="text" id="contact_number" value="${
                                    contact_number || ""
                                }" class="form-control" autocomplete="one-time-code">
                            </div>
                            <div class="col-6 form-group">
                                <label for="email">Email </label>
                                <input type="email" id="email" value="${
                                    email || ""
                                }" class="form-control" autocomplete="one-time-code">
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

function deleteModal(id, nama_fakultas) {
    const modalHeader = "Hapus Fakultas";
    const modalBody = `Apakah Anda Yakin Menghapus Fakultas ${nama_fakultas} Ini?`;
    const modalFooter = `<a class="btn btn-danger btn-lg" onclick="deleteItem('${id}')">Hapus</a>`;
    showModal(modalHeader, modalBody, modalFooter);
}

// API
function save() {
    const nama_fakultas = $("#nama_fakultas").val();
    const dekan = $("#dekan").val();
    const contact_number = $("#contact_number").val();
    const email = $("#email").val();

    let data = {
        nama_fakultas: nama_fakultas,
        dekan: dekan,
        contact_number: contact_number,
        email: email,
    };

    $.ajax({
        type: "POST",
        url: `/api/fakultas`, // Update the API endpoint for Fakultas
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
    const nama_fakultas = $("#nama_fakultas").val();
    const dekan = $("#dekan").val();
    const contact_number = $("#contact_number").val();
    const email = $("#email").val();

    let data = {
        nama_fakultas: nama_fakultas,
        dekan: dekan,
        contact_number: contact_number,
        email: email,
    };

    $.ajax({
        type: "PATCH",
        url: `/api/fakultas/${id}`, // Update the API endpoint for Fakultas
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
        url: `/api/fakultas/${id}`, // Update the API endpoint for Fakultas
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
