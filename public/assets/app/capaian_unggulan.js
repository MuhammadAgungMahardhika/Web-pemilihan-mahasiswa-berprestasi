let table;
showData();

function showData() {
    table = $("#datatable").DataTable({
        processing: true,
        serverSide: true,
        ajax: "/api/capaian-unggulan/data", // Sesuaikan URL API
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
                searchable: true,
            },
            {
                data: "kode",
                name: "kode",
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
                data: "bidang.nama",
                name: "bidang.nama",
                orderable: true,
                searchable: true,
            },
            {
                data: "kategori.nama",
                name: "kategori.nama",
                orderable: true,
                searchable: true,
            },
            {
                data: "skor",
                name: "skor",
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
                                <a onclick="deleteModal('${row.id}', '${row.nama}')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> </a>
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

function addModal() {
    const modalHeader = "Tambah Capaian Unggulan";
    const modalBody = `
        <form class="form form-horizontal">
            <div class="form-body"> 
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="kode">Kode <i class="text-danger">*</i></label>
                        <input type="text" id="kode" class="form-control" autocomplete="one-time-code">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="nama">Nama <i class="text-danger">*</i></label>
                        <input type="text" id="nama" class="form-control" autocomplete="one-time-code">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="skor">Skor <i class="text-danger">*</i></label>
                        <input type="number" id="skor" class="form-control" step="0.01" autocomplete="one-time-code">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="id_bidang">Bidang <i class="text-danger">*</i></label>
                        <select class="form-select" id="id_bidang" onclick="selectBidang()">
                      
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="id_kategori">Kategori <i class="text-danger">*</i></label>
                        <select class="form-select" id="id_kategori" onclick="selectKategori()">
                          
                        </select>
                    </div>
                </div>
            </div>
        </form>
    `;
    const modalFooter = `<a class="btn btn-success btn-lg" onclick="save()"><i class="fa fa-save"> </i> Simpan</a>`;
    showLargeModal(modalHeader, modalBody, modalFooter);
}

function selectBidang(selectedId = null) {
    const bidangOptionLenght = $("#id_bidang option").length;
    if (bidangOptionLenght != 0) {
        return;
    }
    $.ajax({
        type: "GET",
        url: `/api/bidang`,
        dataType: "json",
        success: function (response) {
            const responseData = response.data;
            let dataOption = `<option value=""></option>`;
            responseData.forEach((r) => {
                dataOption += `<option value="${r.id}" ${
                    selectedId == r.id ? "selected" : ""
                }>${r.nama}</option>`;
            });
            $("#id_bidang").html(dataOption);
        },
    });
}

function selectKategori(selectedId = null) {
    const kategoriOptionLenght = $("#id_kategori option").length;
    if (kategoriOptionLenght != 0) {
        return;
    }
    $.ajax({
        type: "GET",
        url: `/api/kategori`,
        dataType: "json",
        success: function (response) {
            const responseData = response.data;
            let dataOption = `<option value=""></option>`;
            responseData.forEach((r) => {
                dataOption += `<option value="${r.id}" ${
                    selectedId == r.id ? "selected" : ""
                }>${r.nama}</option>`;
            });
            $("#id_kategori").html(dataOption);
        },
    });
}

function editModal(id) {
    $.ajax({
        type: "GET",
        url: `/api/capaian-unggulan/${id}`,
        success: function (response) {
            let { id, kode, nama, skor, id_bidang, id_kategori } =
                response.data;
            const modalHeader = "Ubah Data Capaian Unggulan";
            const modalBody = `
                <form class="form form-horizontal">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label for="kode">Kode <i class="text-danger">*</i></label>
                                <input type="text" id="kode" value="${kode}" class="form-control" autocomplete="one-time-code">
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="nama">Nama <i class="text-danger">*</i></label>
                                <input type="text" id="nama" value="${nama}" class="form-control" autocomplete="one-time-code">
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="skor">Skor <i class="text-danger">*</i></label>
                                <input type="number" id="skor" value="${skor}" class="form-control" step="0.01" autocomplete="one-time-code">
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="id_bidang">Bidang <i class="text-danger">*</i></label>
                                <select class="form-select" id="id_bidang"></select>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="id_kategori">Kategori <i class="text-danger">*</i></label>
                                <select class="form-select" id="id_kategori"></select>
                            </div>
                        </div>
                    </div>
                </form>
            `;
            const modalFooter = `<a class="btn btn-success btn-lg" onclick="update('${id}')"><i class="fa fa-save"> </i> Simpan Perubahan</a>`;
            showLargeModal(modalHeader, modalBody, modalFooter);
            selectBidang(id_bidang);
            selectKategori(id_kategori);
        },
        error: function (err) {
            let errorResponse = err.responseJSON;
            const errorMessage = errorResponse.message;
            showToastErrorAlert(errorMessage);
        },
    });
}

function deleteModal(id, name) {
    const modalHeader = "Hapus Data Capaian Unggulan";
    const modalBody = `Apakah Anda Yakin Menghapus Data ${name} Ini?`;
    const modalFooter = `<a class="btn btn-danger btn-sm" onclick="deleteItem('${id}')">Hapus</a>`;
    showModal(modalHeader, modalBody, modalFooter);
}

// API
function save() {
    const kode = $("#kode").val();
    const nama = $("#nama").val();
    const skor = $("#skor").val();
    const id_bidang = $("#id_bidang").val();
    const id_kategori = $("#id_kategori").val();

    // Menyiapkan data untuk dikirim ke server
    let data = {
        kode: kode,
        nama: nama,
        skor: skor,
        id_bidang: id_bidang,
        id_kategori: id_kategori,
    };

    $.ajax({
        type: "POST",
        url: `/api/capaian-unggulan`,
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
    const kode = $("#kode").val();
    const nama = $("#nama").val();
    const skor = $("#skor").val();
    const id_bidang = $("#id_bidang").val();
    const id_kategori = $("#id_kategori").val();

    // Menyiapkan data untuk dikirim ke server
    let data = {
        kode: kode,
        nama: nama,
        skor: skor,
        id_bidang: id_bidang,
        id_kategori: id_kategori,
    };

    $.ajax({
        type: "PUT",
        url: `/api/capaian-unggulan/${id}`,
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
        url: `/api/capaian-unggulan/${id}`,
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
