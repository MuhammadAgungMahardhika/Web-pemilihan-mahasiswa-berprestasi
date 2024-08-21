let table;

showData();

function showData() {
    table = $("#datatable").DataTable({
        processing: true,
        serverSide: true,
        ajax: `/api/mahasiswa/ranking/fakultas/${idFakultas}`,
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
                data: "nama_departmen",
                name: "nama_departmen",
                orderable: true,
                searchable: true,
            },
            {
                data: "ipk",
                name: "ipk",
                orderable: true,
                searchable: true,
            },
            {
                data: "karya_ilmiah_skor",
                name: "karya_ilmiah_skor",
                orderable: true,
                searchable: true,
                class: "text-end",
            },
            {
                data: "bahasa_inggris_skor",
                name: "bahasa_inggris_skor",
                orderable: true,
                searchable: true,
                class: "text-end",
            },
            {
                data: "dokumen_prestasi_skor",
                name: "dokumen_prestasi_skor",
                orderable: true,
                searchable: true,
                class: "text-end",
            },
            {
                data: "total_skor",
                name: "total_skor",
                orderable: true,
                searchable: true,
                class: "text-end",
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
        order: [[8, "desc"]],
    });
}
function sendModal(id, nama) {
    console.log(id);
    const modalHeader = "Kirim Utusan Fakultas";
    const modalBody = `Apakah Anda Yakin Mengutus Mahasiswa (${nama}) Sebagai Utusan Fakultas?`;
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
        tingkat: "fakultas",
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
