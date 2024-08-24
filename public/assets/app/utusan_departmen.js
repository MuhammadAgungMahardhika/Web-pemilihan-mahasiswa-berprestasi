let table;

showData();

function showData() {
    table = $("#datatable").DataTable({
        processing: true,
        serverSide: true,
        ajax: `/api/utusan/departmen/${idDepartmen}`,
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
                data: "nim_mahasiswa",
                name: "nim_mahasiswa",
                orderable: true,
                searchable: true,
            },
            {
                data: "nama_mahasiswa",
                name: "nama_mahasiswa",
                orderable: true,
                searchable: true,
            },
            {
                data: "total_skor", // Menampilkan total skor
                name: "total_skor",
                orderable: true,
                searchable: true,
            },
            {
                data: "tanggal_utus_departmen",
                name: "tanggal_utus_departmen",
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
                                <a onclick="deleteModal('${row.id}','${row.nama_mahasiswa}')" class="btn btn-danger btn-sm"><i class="fa fa-x"></i> </a>
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

function deleteModal(id, nama) {
    const modalHeader = "Batalkan Utusan";
    const modalBody = `Apakah Anda Yakin Menghapus (${nama}) dari Utusan Departemen ?`;
    const modalFooter = `<a class="btn btn-danger btn-lg" onclick="deleteItem('${id}')">Batalkan</a>`;
    showModal(modalHeader, modalBody, modalFooter);
}

// API

function deleteItem(id) {
    $.ajax({
        type: "DELETE",
        url: `/api/utusan/${id}`,
        headers: {
            "X-CSRF-TOKEN": csrfToken,
        },
        success: function (response) {
            showToastSuccessAlert(response.message);
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
