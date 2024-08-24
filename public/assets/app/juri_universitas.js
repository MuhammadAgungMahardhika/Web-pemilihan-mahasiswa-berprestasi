let table;

showData();

function showData() {
    table = $("#datatable").DataTable({
        processing: true,
        serverSide: true,
        ajax: "/api/user/juri-universitas/data",
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
                data: "username",
                name: "username",
                orderable: true,
                searchable: true,
            },
            {
                data: "name",
                name: "name",
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
                    const activateUserButton =
                        row.status == "nonaktif"
                            ? `<a onclick="activateUser('${row.id}')" title="Aktifkan User" class="btn btn-primary btn-sm"><i class="fa fa-key"></i> </a>`
                            : `<a onclick="deactivateUser('${row.id}')" title="Non Aktifkan User" class="btn btn-primary btn-sm"><i class="fa fa-lock"></i> </a>`;
                    return `
                        <div class="row g-2 text-center">
                            <div class="col">
                                ${activateUserButton}
                            </div>
                            <div class="col">
                                <a onclick="editModal('${row.id}')" title="Ubah User" class="btn btn-primary btn-sm"><i class="fa fa-info"></i> </a>
                            </div>
                            <div class="col">
                                <a onclick="deleteModal('${row.id}', '${row.name}')" title="Hapus User" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> </a>
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
    const modalHeader = "Tambah Juri Universitas";
    const modalBody = `
        <form class="form form-horizontal">
            <div class="form-body">
                <div class="row">
                   <input type="hidden" id="id_role" value="6">
                    <div class="col-4 form-group">
                        <label for="username">Username <i class="text-danger">*</i></label>
                        <input type="text" id="username" class="form-control" autocomplete="one-time-code">
                    </div>
                    <div class="col-4 form-group">
                        <label for="name">Nama <i class="text-danger">*</i></label>
                        <input type="text" id="name" class="form-control" autocomplete="one-time-code">
                    </div>
                    <div class="col-4 form-group">
                        <label for="password">Password <i class="text-danger">*</i></label>
                        <input type="password" id="password" class="form-control" autocomplete="one-time-code">
                    </div>
                    <div class="col-4 form-group">
                        <label for="password_confirm">Konfirmasi Password <i class="text-danger">*</i></label>
                        <input type="password" id="password_confirm" class="form-control" autocomplete="one-time-code">
                    </div>
                    <div class="col-4 form-group">
                        <label for="status">Status <i class="text-danger">*</i></label>
                        <select id="status" class="form-control">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Tidak Aktif</option>
                        </select>
                    </div>
                </div>
            </div>
        </form>
    `;
    const modalFooter = `<a class="btn btn-success btn-lg" onclick="save()"><i class="fa fa-save"> </i> Save</a>`;
    showLargeModal(modalHeader, modalBody, modalFooter);
}

function editModal(id) {
    $.ajax({
        type: "GET",
        url: `/api/user/${id}`,
        success: function (response) {
            let { id, id_role, name, username, status } = response.data;
            const modalHeader = "Ubah Data Juri Universitas";
            const modalBody = `
                <form class="form form-horizontal">
                    <div class="form-body">
                        <div class="row">
                            <input type="hidden" id="id_role" value="${id_role}">
                            <div class="col-4 form-group">
                                <label for="username">Username <i class="text-danger">*</i></label>
                                <input type="text" id="username" value="${username}" class="form-control" autocomplete="one-time-code">
                            </div>
                            <div class="col-4 form-group">
                                <label for="name">Name <i class="text-danger">*</i></label>
                                <input type="text" id="name" value="${name}" class="form-control" autocomplete="one-time-code">
                            </div>
                    
                            <div class="col-4 form-group">
                                <label for="status">Status <i class="text-danger">*</i></label>
                                <select id="status" class="form-control">
                                    <option value="aktif" ${
                                        status === "aktif" ? "selected" : ""
                                    }>Aktif</option>
                                    <option value="nonaktif" ${
                                        status === "nonaktif" ? "selected" : ""
                                    }>Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            `;
            const modalFooter = `<a class="btn btn-success btn-lg" onclick="update('${id}')"><i class="fa fa-save"> </i> Save Changes</a>`;
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

function deleteModal(id, name) {
    const modalHeader = "Hapus Juri Universitas";
    const modalBody = `Apakah anda yakin ingin menghapus juri ${name}?`;
    const modalFooter = `<a class="btn btn-danger btn-lg" onclick="deleteItem('${id}')">Iya, Hapus</a>`;
    showModal(modalHeader, modalBody, modalFooter);
}

// API
function save() {
    const name = $("#name").val();
    const username = $("#username").val();
    const id_role = $("#id_role").val();
    const password = $("#password").val();
    const password_confirm = $("#password_confirm").val();
    const status = $("#status").val();

    if (password != password_confirm) {
        return showToastErrorAlert(
            "Konfirmasi password tidak sama dengan password"
        );
    }

    let data = {
        id_role: id_role,
        name: name,
        username: username,
        password: password,
        password_confirm: password_confirm,
        status: status,
    };

    $.ajax({
        type: "POST",
        url: "/api/user",
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
    const username = $("#username").val();
    const name = $("#name").val();
    const id_role = $("#id_role").val();
    const status = $("#status").val();

    let data = {
        username: username,
        name: name,
        id_role: id_role,
        status: status,
    };

    $.ajax({
        type: "PUT",
        url: `/api/user/${id}`,
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
        url: `/api/user/${id}`,
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
