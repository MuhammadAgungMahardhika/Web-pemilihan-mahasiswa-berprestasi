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
        ajax: `/api/karya-ilmiah/universitas`,
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
                data: "penilaian_karya_ilmiah",
                name: "penilaian_karya_ilmiah",
                orderable: false,
                searchable: false,
                render: function (penilaian_karya_ilmiah) {
                    // Pengecekan apakah karya ilmiah sudah dinilai oleh user yang sedang login
                    const isEvaluated = penilaian_karya_ilmiah.some(
                        (evaluation) => evaluation.id_user == userId
                    );
                    return isEvaluated
                        ? "<span class='text-success'>Dinilai</span>"
                        : "<span class='text-danger'>Belum dinilai</span>";
                },
            },
            {
                data: null,
                className: "text-center",
                render: function (data, type, row) {
                    console.log(row);
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
    console.log(id);
    $.ajax({
        type: "GET",
        url: `/api/karya-ilmiah/${id}`,
        success: function (response) {
            let { dokumen_url, penilaian_karya_ilmiah } = response.data;
            console.log(penilaian_karya_ilmiah);

            const path = `/storage/karya_ilmiah/${dokumen_url}`;
            const modalHeader = "Preview File PDF";
            const modalBody = `
                <div>
                    <embed src="${path}" type="application/pdf" width="100%" height="600px" />
                </div>
            `;

            // Filter penilaian_karya_ilmiah berdasarkan userId
            const userPenilaian = penilaian_karya_ilmiah.find(
                (penilaian) => penilaian.id_user == userId
            );

            let statusButton;
            if (userPenilaian) {
                // Jika penilaian sudah dilakukan oleh user
                statusButton = `
                    <label for="skor">Skor <i class="text-danger">*</i></label>
                    <input id="skor_universitas" type="number" step="0.01" value="${userPenilaian.skor_universitas}" class="form-control" />
                    <a class="btn btn-success btn-lg" onclick="reviewKaryaIlmiah('${id}','diterima')">
                        <i class="fa fa-save me-2"> </i> Ubah Skor
                    </a>
                `;
            } else {
                // Jika penilaian belum dilakukan oleh user
                statusButton = `
                    <label for="skor">Skor <i class="text-danger">*</i></label>
                    <input id="skor_universitas" type="number" step="0.01" class="form-control" />
                    <a class="btn btn-success btn-lg" onclick="reviewKaryaIlmiah('${id}')">
                        <i class="fa fa-save me-2"></i> Simpan Skor
                    </a>
                `;
            }

            const modalFooter = statusButton;

            showLargeModal(modalHeader, modalBody, modalFooter);
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

function reviewKaryaIlmiah(id) {
    const skor_universitas = $("#skor_universitas").val();
    if (!skor_universitas) {
        return showToastErrorAlert("Skor wajib diisi");
    }
    let data = {
        skor_universitas: skor_universitas,
    };
    $.ajax({
        type: "PATCH",
        url: `/api/karya-ilmiah/review-universitas/${id}`,
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
