let table;
let pond;

FilePond.registerPlugin(
    FilePondPluginImagePreview,
    FilePondPluginImageResize,
    FilePondPluginFileValidateSize,
    FilePondPluginFileValidateType
);

showData();

function showData() {
    table = $("#datatable").DataTable({
        processing: true,
        serverSide: true,
        ajax: `/api/karya-ilmiah/fakultas/${idFakultas}`,
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
                data: "judul",
                name: "judul",
                orderable: true,
                searchable: true,
            },
            {
                data: "status",
                name: "status",
                orderable: true,
                searchable: true,
                render: function (data) {
                    return data === "pending"
                        ? "<span class='text-warning'>Pending<span/>"
                        : data === "ditolak"
                        ? "<span class='text-danger'>Ditolak<span/>"
                        : "<span class='text-success'>Diterima<span/>";
                },
            },
            {
                data: null,
                className: "text-center",
                render: function (data, type, row) {
                    return `
                        <div class="row g-2 text-center">
                            <div class="col">
                                <a title="Preview file" onclick="previewFile('${row.id}')" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i> </a>
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

function previewFile(id) {
    $.ajax({
        type: "GET",
        url: `/api/karya-ilmiah/${id}`,
        success: function (response) {
            let { dokumen_url, status } = response.data;
            const path = `/storage/karya_ilmiah/${dokumen_url}`;
            const modalHeader = "Preview File PDF";
            const modalBody = `
                <div>
                    <embed src="${path}" type="application/pdf" width="100%" height="600px" />
                </div>
            `;
            let statusButton = "";
            if (status == "pending") {
                statusButton = `<label for="skor">Skor <i class="text-danger">*</i></label>
                 <input id="skor_fakultas" type="number" step="0.01" class="form-control" />
                 <a class="btn btn-danger btn-lg" onclick="changeStatus('${id}', 'ditolak')">
                    <i class="fa fa-save me-2"></i> Tolak
                 </a>
                 <a class="btn btn-success btn-lg" onclick="changeStatus('${id}', 'diterima')">
                    <i class="fa fa-save me-2"></i> Terima
                 </a>
                `;
            } else if (status == "diterima") {
                statusButton = `<a class="btn btn-danger btn-lg" onclick="changeStatus('${id}','ditolak')"><i class="fa fa-save me-2"> </i> Tolak</a>
                `;
            } else if (status == "ditolak") {
                statusButton = `<label for="skor">Skor <i class="text-danger">*</i></label><input id="skor_fakultas" type="number" step="0.01" class="form-control" />
                <a class="btn btn-success btn-lg" onclick="changeStatus('${id}','diterima')"><i class="fa fa-save me-2"> </i> Terima</a>`;
            }
            const modalFooter = statusButton;

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

function changeStatus(id, status) {
    const skor_fakultas = $("#skor_fakultas").val();
    if (status == "diterima" && !skor_fakultas) {
        return showToastErrorAlert("Skor wajib diisi");
    }
    let data = {
        status: status,
        skor_fakultas: skor_fakultas,
    };
    $.ajax({
        type: "PATCH",
        url: `/api/karya-ilmiah/status/${id}`,
        contentType: "application/json",
        data: JSON.stringify(data),
        headers: {
            "X-CSRF-TOKEN": csrfToken,
        },
        success: function (response) {
            showToastSuccessAlert(response.message);
            closeLargeModal();
            return reloadData();
        },
        error: function (err) {
            console.log(err);
            let errorResponse = err.responseJSON;
            const errorMessage = errorResponse.message;
            const errorData = errorResponse.data;
            showToastErrorAlert(errorMessage + `<br>(${errorData})`);
        },
    });
}
