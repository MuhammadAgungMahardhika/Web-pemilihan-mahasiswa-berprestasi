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
        ajax: `/api/dokumen-prestasi/data`,
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
                                <a title="Ubah Dokumen Prestasi" onclick="editModal('${row.id}')" class="btn btn-primary btn-sm"><i class="fa fa-info"></i> </a>
                            </div>
                            <div class="col">
                                <a title="Preview file" onclick="previewFile('${row.id}')" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i> </a>
                            </div>
                            <div class="col">
                                <a title="Hapus Dokumen Prestasi" onclick="deleteModal('${row.id}', '${row.judul}')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> </a>
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
            let { dokumen_url } = response.data;
            const path = `/storage/dokumen_prestasi/${dokumen_url}`;
            const modalHeader = "Preview File PDF";
            const modalBody = `
                <div>
                    <embed src="${path}" type="application/pdf" width="100%" height="600px" />
                </div>
            `;
            const modalFooter = `<a class="btn btn-success btn-lg" onclick="closeLargeModal()"><i class="fa fa-save me-2"> </i> Tutup</a>`;
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

function addModal() {
    const modalHeader = "Tambah Dokumen Prestasi";
    const modalBody = `
        <form class="form form-horizontal">
            <div class="form-body">
                <div class="row">
                  <input type="hidden" value=${id_mahasiswa} id="id_mahasiswa" class="form-control">
                  <div class="col-6 form-group">
                        <label for="id_capaian_unggulan">Capaian Unggulan <i class="text-danger">*</i></label>
                         <select class="form-select" id="id_capaian_unggulan" onclick="selectCapaianUnggulan()">
                           
                        </select>
                    </div>
                    <div class="col-6 form-group">
                        <label for="judul">Judul <i class="text-danger">*</i></label>
                        <input type="text" id="judul" class="form-control">
                    </div>
                    <div class="col-12 form-group">
                        <label for="dokumen_url">File Dokumen <i class="text-danger">*</i></label>
                        <input type="file" id="dokumen_url" class="form-control" accept="application/pdf" name="filepond">
                    </div>
                </div>
            </div>
        </form>
    `;

    const modalFooter = `<a class="btn btn-success btn-lg" onclick="save()"><i class="fa fa-save me-2"> </i> Simpan</a>`;
    showLargeModal(modalHeader, modalBody, modalFooter);

    // Filepond: Image Resize
    pond = FilePond.create(document.querySelector("#dokumen_url"), {
        credits: null,
        acceptedFileTypes: ["application/pdf"],
        server: {
            process: "/temp-upload",
            revert: "/temp-delete",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            onload: (response) => {
                console.log("File upload successful", response);
                return response;
            },
            onerror: (response) => {
                console.error("File upload failed", response);
                return response;
            },
        },
        storeAsFile: true,
        onload: (response) => {
            console.log("File upload successful", response);
            return response;
        },
        onerror: (response) => {
            console.error("File upload failed", response);
            return response;
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

function editModal(id) {
    $.ajax({
        type: "GET",
        url: `/api/dokumen-prestasi/${id}`,
        success: function (response) {
            let { id_capaian_unggulan, id_mahasiswa, judul, dokumen_url } =
                response.data;
            const modalHeader = "Edit Dokumen Prestasi";
            const modalBody = `
                <form class="form form-horizontal">
                    <div class="form-body">
                        <div class="row">
                            <input type="hidden" id="id_mahasiswa" value="${id_mahasiswa}" class="form-control">
                            <div class="col-6 form-group">
                                <label for="id_capaian_unggulan">Capaian Unggulan <i class="text-danger">*</i></label>
                                <input type="number" id="id_capaian_unggulan" value="${id_capaian_unggulan}" class="form-control">
                            </div> 
                            <div class="col-6 form-group">
                                <label for="judul">Judul <i class="text-danger">*</i></label>
                                <input type="text" id="judul" value="${judul}" class="form-control">
                            </div>
                            <div class="col-12 form-group">
                                <label for="dokumen_url">File Dokumen</label>
                                <input type="file" id="dokumen_url" class="form-control">
                            </div>
                        
                        </div>
                    </div>
                </form>
            `;
            const modalFooter = `<a class="btn btn-success btn-lg" onclick="update('${id}')"><i class="fa fa-save me-2"> </i> Simpan Perubahan</a>`;
            showLargeModal(modalHeader, modalBody, modalFooter);

            // Filepond: Load existing file
            pond = FilePond.create(document.querySelector("#dokumen_url"), {
                credits: null,
                acceptedFileTypes: ["application/pdf"],
                server: {
                    process: "/temp-upload",
                    revert: "/temp-delete",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                    },
                },
                storeAsFile: true,
            });

            if (dokumen_url) {
                const filePath = `/storage/dokumen_prestasi/${dokumen_url}`;
                pond.addFile(filePath);
            }
        },
        error: function (err) {
            let errorResponse = err.responseJSON;
            const errorMessage = errorResponse.message;
            const errorData = errorResponse.data;
            showToastErrorAlert(errorMessage + `<br>(${errorData})`);
        },
    });
}

function save() {
    const id_capaian_unggulan = parseInt($("#id_capaian_unggulan").val());
    const id_mahasiswa = parseInt($("#id_mahasiswa").val());
    const judul = $("#judul").val();
    const pondFile = pond.getFile();
    const dokumen_url = pondFile ? JSON.parse(pondFile.serverId).folder : null;

    let data = {
        id_capaian_unggulan: id_capaian_unggulan,
        id_mahasiswa: id_mahasiswa,
        judul: judul,
        dokumen_url: dokumen_url,
    };
    console.log(data);
    $.ajax({
        type: "POST",
        url: `/api/dokumen-prestasi`,
        data: JSON.stringify(data),
        contentType: "application/json",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
        },
        success: function (response) {
            showToastSuccessAlert(response.message);
            closeLargeModal();
            return reloadData();
        },
        error: function (err) {
            let errorResponse = err.responseJSON;
            console.log(errorResponse);
            const errorMessage = errorResponse.message;
            const errorData = errorResponse.data;
            showToastErrorAlert(errorMessage + `<br>(${errorData})`);
        },
    });
}

function update(id) {
    const id_capaian_unggulan = $("#id_capaian_unggulan").val();
    const id_mahasiswa = $("#id_mahasiswa").val();
    const judul = $("#judul").val();

    const pondFile = pond.getFile();
    const dokumen_url = pondFile ? JSON.parse(pondFile.serverId).folder : null;
    let data = {
        id_capaian_unggulan: id_capaian_unggulan,
        id_mahasiswa: id_mahasiswa,
        judul: judul,
        dokumen_url: dokumen_url,
    };

    $.ajax({
        type: "PUT",
        url: `/api/dokumen-prestasi/${id}`,
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

function deleteModal(id, judul) {
    const modalHeader = "Hapus Dokumen Prestasi";
    const modalBody = `Apakah Anda yakin ingin menghapus dokumen prestasi "${judul}"?`;
    const modalFooter = `
        <a class="btn btn-danger btn-lg" onclick="deleteData('${id}')"><i class="fa fa-trash me-2"> </i> Hapus</a>
        <a class="btn btn-secondary btn-lg" onclick="closeModal()"><i class="fa fa-close me-2"> </i> Batal</a>
    `;
    showModal(modalHeader, modalBody, modalFooter);
}

function deleteData(id) {
    $.ajax({
        type: "DELETE",
        url: `/api/dokumen-prestasi/${id}`,
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
