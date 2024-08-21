let table;
let pond;

FilePond.registerPlugin(
    FilePondPluginImagePreview,
    FilePondPluginImageResize,
    FilePondPluginFileValidateSize,
    FilePondPluginFileValidateType
);

function previewFile(id) {
    $.ajax({
        type: "GET",
        url: `/api/karya-ilmiah/${id}`,
        success: function (response) {
            let { dokumen_url } = response.data;
            const path = `/storage/karya_ilmiah/${dokumen_url}`;
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
    const modalHeader = "Upload Karya Ilmiah";
    const modalBody = `
        <form class="form form-horizontal">
            <div class="form-body">
                <div class="row">
                    <input type="hidden" value=${periode} id="periode" class="form-control">
                    <input type="hidden" value=${id_mahasiswa} id="id_mahasiswa" class="form-control">
                    <div class="col-6 form-group">
                        <label for="judul">Judul <i class="text-danger">*</i></label>
                        <input type="text" id="judul" class="form-control">
                    </div>
                    <div class="col-12 form-group">
                        <label for="dokumen_url">File Dokumen (Maksimal 2 MB)<i class="text-danger">*</i></label>
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
        acceptedFileTypes: [
            "image/png",
            "image/jpg",
            "image/jpeg",
            "application/pdf",
        ],
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

function editModal(id) {
    $.ajax({
        type: "GET",
        url: `/api/karya-ilmiah/${id}`,
        success: function (response) {
            let { periode, id_mahasiswa, judul, dokumen_url } = response.data;
            const modalHeader = "Perbarui Karya Ilmiah";
            const modalBody = `
                <form class="form form-horizontal">
                    <div class="form-body">
                        <div class="row">
                            <input type="hidden" id="periode" value="${periode}" class="form-control">
                            <input type="hidden" id="id_mahasiswa" value="${id_mahasiswa}" class="form-control">
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
                acceptedFileTypes: [
                    "image/png",
                    "image/jpg",
                    "image/jpeg",
                    "application/pdf",
                ],
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
                const filePath = `/storage/karya_ilmiah/${dokumen_url}`;
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
    const periode = $("#periode").val();
    const id_mahasiswa = parseInt($("#id_mahasiswa").val());
    const judul = $("#judul").val();
    const pondFile = pond.getFile();
    const dokumen_url = pondFile ? JSON.parse(pondFile.serverId).folder : null;

    let data = {
        periode: periode,
        id_mahasiswa: id_mahasiswa,
        judul: judul,
        dokumen_url: dokumen_url,
    };

    $.ajax({
        type: "POST",
        url: `/api/karya-ilmiah`,
        data: JSON.stringify(data),
        contentType: "application/json",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
        },
        success: function (response) {
            showToastSuccessAlert(response.message);
            closeLargeModal();
            return window.location.reload();
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
    const periode = $("#periode").val();
    const id_mahasiswa = $("#id_mahasiswa").val();
    const judul = $("#judul").val();

    const pondFile = pond.getFile();
    const dokumen_url = pondFile ? JSON.parse(pondFile.serverId).folder : null;
    let data = {
        periode: periode,
        id_mahasiswa: id_mahasiswa,
        judul: judul,
        dokumen_url: dokumen_url,
    };

    $.ajax({
        type: "PUT",
        url: `/api/karya-ilmiah/${id}`,
        contentType: "application/json",
        data: JSON.stringify(data),
        headers: {
            "X-CSRF-TOKEN": csrfToken,
        },
        success: function (response) {
            showToastSuccessAlert(response.message);
            closeLargeModal();
            return window.location.reload();
        },
        error: function (err) {
            let errorResponse = err.responseJSON;
            const errorMessage = errorResponse.message;
            const errorData = errorResponse.data;
            showToastErrorAlert(errorMessage + `<br>(${errorData})`);
        },
    });
}
