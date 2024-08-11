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
        ajax: `/api/dokumen-prestasi/admin-departmen/data`,
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
                data: "mahasiswa.nama",
                name: "mahasiswa.nama",
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
                data: "judul",
                name: "judul",
                orderable: true,
                searchable: true,
            },
            {
                data: "capaian_unggulan.nama",
                name: "capaian_unggulan.nama",
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
        url: `/api/dokumen-prestasi/${id}`,
        success: function (response) {
            let { dokumen_url,status } = response.data;
            const path = `/storage/dokumen_prestasi/${dokumen_url}`;
            const modalHeader = "Preview File PDF";
            const modalBody = `
                <div>
                    <embed src="${path}" type="application/pdf" width="100%" height="600px" />
                </div>
            `;
            let statusButton = ""
            if(status == "pending"){
                statusButton = 
                `<a class="btn btn-success btn-lg" onclick="changeStatus('${id}','diterima')"><i class="fa fa-save me-2"> </i> Terima</a>
                <a class="btn btn-danger btn-lg" onclick="changeStatus('${id}','ditolak')"><i class="fa fa-save me-2"> </i> Tolak</a>
                `;
            }else if(status == "diterima"){
                statusButton = 
                `<a class="btn btn-danger btn-lg" onclick="changeStatus('${id}','ditolak')"><i class="fa fa-save me-2"> </i> Tolak</a>
                `;
            }else if(status == "ditolak"){
                statusButton = 
                `<a class="btn btn-success btn-lg" onclick="changeStatus('${id}','diterima')"><i class="fa fa-save me-2"> </i> Terima</a>`
            }
            const modalFooter = statusButton
          
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

function selectCapaianUnggulan(capaianUnggulanId = null) {
    const capaianUnggulanOptionLenght = $("#id_capaian_unggulan option").length;
    if (capaianUnggulanOptionLenght != 0) {
        return;
    }
    $.ajax({
        type: "GET",
        url: `/api/capaian-unggulan`,
        dataType: "json",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
        },
        success: function (response) {
            const responseData = response.data;
            let dataOption = `<option value=""></option>`;
            responseData.forEach((r) => {
                dataOption += `<option value="${r.id}" ${
                    capaianUnggulanId != null && capaianUnggulanId == r.id
                        ? "selected"
                        : ""
                }>${r.nama}</option>`;
            });
            $("#id_capaian_unggulan").html(dataOption);
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

function changeStatus(id,status) {
    let data = {
        status : status
    }
    $.ajax({
        type: "PATCH",
        url: `/api/dokumen-prestasi/status/${id}`,
        contentType: "application/json",
        data: JSON.stringify(data),
        headers: {
            "X-CSRF-TOKEN": csrfToken,
        },
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

