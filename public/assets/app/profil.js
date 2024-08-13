FilePond.registerPlugin(
    FilePondPluginImagePreview,
    FilePondPluginImageResize,
    FilePondPluginFileValidateSize,
    FilePondPluginFileValidateType
);
// Filepond: Image Resize
let pond = FilePond.create(document.querySelector("#foto_url"), {
    credits: null,
    acceptedFileTypes: ["image/*"],
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

if (fotoUrl) {
    console.log("upload");
    const filePath = `/storage/profil/${fotoUrl}`;
    pond.addFile(filePath);
}
function update() {
    // Ambil data dari form

    const pondFile = pond.getFile();
    const foto_url = pondFile ? JSON.parse(pondFile.serverId).folder : null;
    let data = {
        name: $("#name").val(),
        username: $("#username").val(),
        foto_url: foto_url,
        status: $("#status").val(),
    };

    console.log(data);
    if (idMahasiswa) {
        console.log("Mahasiswa");
        let dataMahasiswa = {
            nim: $("#nim").val(),
            nik: $("#nik").val(),
            id_departmen: $("#id_departmen").val(),
            nama: $("#nama_mahasiswa").val(),
            semester: $("#semester").val(),
            jenis_kelamin: $("#jenis_kelamin").val(),
            agama: $("#agama").val(),
            tempat_lahir: $("#tempat_lahir").val(),
            tgl_lahir: $("#tanggal_lahir").val(),
            no_hp: $("#no_hp").val(),
            alamat: $("#alamat").val(),
            nama_ayah: $("#nama_ayah").val(),
            no_hp_ayah: $("#no_hp_ayah").val(),
            nama_ibu: $("#nama_ibu").val(),
        };
        updateMahasiswa(idMahasiswa, dataMahasiswa);
    }
    $.ajax({
        url: `/api/user/${idUser}`,
        type: "PATCH",
        contentType: "application/json",
        data: JSON.stringify(data),
        headers: {
            "X-CSRF-TOKEN": csrfToken,
        },
        success: function (response) {
            console.log(response);
            const message = response.message;
            showToastSuccessAlert(message);
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

function updateMahasiswa(id, data) {
    $.ajax({
        url: `/api/mahasiswa/${id}`,
        type: "PATCH",
        contentType: "application/json",
        data: JSON.stringify(data),
        headers: {
            "X-CSRF-TOKEN": csrfToken,
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
