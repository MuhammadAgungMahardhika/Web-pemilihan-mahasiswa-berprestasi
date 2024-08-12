let table;

showData();

function showData() {
    table = $("#datatable").DataTable({
        processing: true,
        serverSide: true,
        ajax: `/api/mahasiswa/ranking/universitas`,
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
                data: "nama_fakultas",
                name: "nama_fakultas",
                orderable: true,
                searchable: true,
            },
            {
                data: "nama_departmen",
                name: "nama_departmen",
                orderable: true,
                searchable: true,
            },
            {
                data: "total_skor",
                name: "total_skor",
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
                                <a onclick="sendModal('${row.id_utusan}','${row.nama}')" title="Utus mahasiswa" class="btn btn-success btn-sm"><i class="fa fa-paper-plane"></i> </a>
                            </div>
                        </div>
                    `;
                },
            },
        ],
        order: [[5, "desc"]],
    });
}
function sendModal(id, nama) {
    console.log(id);
    const modalHeader = "Kirim Utusan Kampus";
    const modalBody = `Apakah Anda Yakin Mengutus Mahasiswa (${nama}) Sebagai Utusan Kampus?`;
    const modalFooter = `<a class="btn btn-success btn-lg" onclick="updateTingkat('${id}')">Kirim</a>`;
    showModal(modalHeader, modalBody, modalFooter);
}

function reloadData() {
    if (table) {
        table.ajax.reload();
    }
}

// API
function updateTingkat(id) {
    let data = {
        tingkat: "universitas",
    };

    $.ajax({
        type: "PATCH",
        url: `/api/utusan/tingkat/${id}`,
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
