let table;

showData();

function showData() {
    table = $("#datatable").DataTable({
        processing: true,
        serverSide: true,
        ajax: "/api/portal/data",
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
                data: "periode",
                name: "periode",
                orderable: true,
                searchable: true,
            },
            {
                data: "tanggal_tutup_departmen",
                name: "tanggal_tutup_departmen",
                orderable: true,
                searchable: true,
            },
            {
                data: "tanggal_tutup_fakultas",
                name: "tanggal_tutup_fakultas",
                orderable: true,
                searchable: true,
            },

            {
                data: "status",
                name: "status",
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
                                <a onclick="deleteModal('${row.id}', '${row.periode}')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> </a>
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
    const modalHeader = "Tambah Portal";
    const modalBody = `
        <form class="form form-horizontal">
            <div class="form-body"> 
                <div class="row">
                    <div class="col-6 form-group">
                        <label for="periode">Periode <i class="text-danger">*</i></label>
                        <input type="number" id="periode" value="${new Date().getFullYear()}" class="form-control" autocomplete="one-time-code">
                    </div>
                     <div class="col-6 form-group">
                        <label for="status">Status <i class="text-danger">*</i></label>
                        <select id="status" class="form-control">
                            <option value="buka">Buka</option>
                            <option value="tutup">Tutup</option>
                        </select>
                    </div>
                     <div class="col-6 form-group">
                        <label for="tanggal_tutup_departmen">Tanggal Tutup Portal Departemen <i class="text-danger">*</i></label>
                        <input type="date" id="tanggal_tutup_departmen" class="form-control" autocomplete="one-time-code">
                    </div>
                    <div class="col-6 form-group">
                        <label for="tanggal_tutup_fakultas">Tanggal Tutup Portal Fakultas <i class="text-danger">*</i></label>
                        <input type="date" id="tanggal_tutup_fakultas" class="form-control" autocomplete="one-time-code">
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
        url: `/api/portal/${id}`,
        success: function (response) {
            let {
                id,
                periode,
                tanggal_tutup_fakultas,
                tanggal_tutup_departmen,
                status,
            } = response.data;
            const modalHeader = "Ubah Portal";
            const modalBody = `
                <form class="form form-horizontal">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-6 form-group">
                                <label for="periode">Periode <i class="text-danger">*</i></label>
                                <input type="number" id="periode" value="${periode}" class="form-control" autocomplete="one-time-code">
                            </div>
                             <div class="col-6 form-group">
                                <label for="status">Status <i class="text-danger">*</i></label>
                                <select id="status" class="form-control">
                                    <option value="buka" ${
                                        status === "buka" ? "selected" : ""
                                    }>Buka</option>
                                    <option value="tutup" ${
                                        status === "tutup" ? "selected" : ""
                                    }>Tutup</option>
                                </select>
                            </div>
                            <div class="col-6 form-group">
                                <label for="tanggal_tutup_departmen">Tanggal Tutup Portal Departemen <i class="text-danger">*</i></label>
                                <input type="date" id="tanggal_tutup_departmen" value="${tanggal_tutup_departmen}" class="form-control" autocomplete="one-time-code">
                            </div>
                            <div class="col-6 form-group">
                                <label for="tanggal_tutup_fakultas">Tanggal Tutup Portal Fakultas <i class="text-danger">*</i></label>
                                <input type="date" id="tanggal_tutup_fakultas" value="${tanggal_tutup_fakultas}" class="form-control" autocomplete="one-time-code">
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

function deleteModal(id, periode) {
    const modalHeader = "Hapus Portal";
    const modalBody = `Apakah Anda Yakin Menghapus Periode ${periode} Ini?`;
    const modalFooter = `<a class="btn btn-danger btn-lg" onclick="deleteItem('${id}')">Hapus</a>`;
    showModal(modalHeader, modalBody, modalFooter);
}

// API
function save() {
    const periode = $("#periode").val();
    const tanggal_tutup_fakultas = $("#tanggal_tutup_fakultas").val();
    const tanggal_tutup_departmen = $("#tanggal_tutup_departmen").val();
    const status = $("#status").val();

    let data = {
        periode: periode,
        tanggal_tutup_fakultas: tanggal_tutup_fakultas,
        tanggal_tutup_departmen: tanggal_tutup_departmen,
        status: status,
    };

    $.ajax({
        type: "POST",
        url: `/api/portal`,
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
    const periode = $("#periode").val();
    const tanggal_tutup_fakultas = $("#tanggal_tutup_fakultas").val();
    const tanggal_tutup_departmen = $("#tanggal_tutup_departmen").val();
    const status = $("#status").val();

    let data = {
        periode: periode,
        tanggal_tutup_fakultas: tanggal_tutup_fakultas,
        tanggal_tutup_departmen: tanggal_tutup_departmen,
        status: status,
    };

    $.ajax({
        type: "PATCH",
        url: `/api/portal/${id}`,
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
        url: `/api/portal/${id}`,
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
