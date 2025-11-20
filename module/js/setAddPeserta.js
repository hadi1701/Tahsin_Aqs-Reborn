$(document).ready(function(){
    $("#btn-submit-peserta").click(function(e){
        e.preventDefault();

        const formData = {
            username: $("#username").val(),
            email: $("#email").val(),
            password: $("#password").val(),
            nama: $("#nama").val(),
            usia: $("#usia").val(),
            gender: $("input[name='gender']:checked").val(),
            no_wa: $("#no_wa").val(),
            komunitas: $("#komunitas").val()
        };

        console.log("Data yang dikirim", formData);

        $.ajax({
            url: "../module/crud/crudAddPeserta.php",
            type: "POST",
            data: formData,
            dataType: "json",
            success: function(response) {
                if (response.status === "success") {
                    Swal.fire({
                        title: 'Success',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if(result.isConfirmed) {
                            //Redirect ke halaman login setelah sukses mengirim bukti pembayaran
                            window.location.href = "daftar_peserta.php";
                            $('#formAdd')[0].reset();
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Gagal',
                        text: response.message,
                        icon: 'error',
                        confirmButtonText: 'Coba Lagi'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.log("Response dari server:", xhr.responseText);
                alert("Terjadi Kesalahan: " + error);
            }
        });
    });

    $('.btn-update').on('click', function(e){
        e.preventDefault();

        //ambil id dari data id yang diklik
        const id = $(this).data('id');

        //tampilkan modal update
        $('#modalUpdate').modal('show');

        //tampilkan id di input
        $('#modalUpdate #id').val(id);

        $.ajax({
            url: "../module/crud/crudDaftar.php",
            type: "GET",
            data: {id: id},
            dataType: 'json',
            success: function(response){
                if(response.status === "success") {
                    const data = response.data;

                    //isi field di modal dengan data dari database
                    $("#modalUpdate #username").val(data.username);
                    $("#modalUpdate #email").val(data.email);
                    $('#modalUpdate #password').val(data.password);
                    $("#modalUpdate #nama").val(data.nama);
                    $("#modalUpdate #usia").val(data.usia);
                    $("#modalUpdate #no_wa").val(data.no_wa);
                    $("#modalUpdate #komunitas").val(data.komunitas);

                    //khusus button radio
                    const gender = data.gender.trim().toLowerCase();
                    if (gender === 'laki-laki') {
                        $("#updategenderL").prop('checked', true);
                    } else if (gender === 'perempuan') {
                        $("#updategenderP").prop('checked', true);
                    }
                } else {
                    alert("Data tidak ditemukan" + response.message);
                }
            },
            error: function(xhr, status, error) {
                alert("Terjadi Kesalahan" + error);
            }
        });
    });

    $('#formUpdate').on('submit', function(e){
        e.preventDefault();

        const formData = $(this).serialize(); //ambil semua input nama = value

        $.ajax({
            url: '../module/crud/crudDaftar.php',
            type: "POST",
            data: formData, //langsung kirim form data
            dataType: 'json',
            success: function(response){
                if(response.status === 'success') {
                    Sal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        $('#modalUpdate').modal('hide');
                        location.reload();
                    });
                } else {
                    Sal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Terjadi Kesalahan: " + error
                    });
                }
            },
            error: function(xhr, status, error){
                alert("Terjadi Kesalahan: " + error);
                console.log(xhr.responseText);
            }
        });
    });

    $('.btn-delete').on('click', function(e){
        e.preventDefault();

        const id = $(this).data('id');
        console.log('id dari: ', id);

        if(!id) {
            alert('Data Id tidak terbaca');
            return;
        }

        Swal.fire({
            icon: 'warning',
            title: 'Yakin?',
            text: "Hapus data dengan ID: " + id + "?",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if(result.isConfirmed){
                $.ajax({
                    url: "../module/crud/crudDaftar.php",
                    type: "DELETE",
                    data: JSON.stringify({ id: id }), // kirim JSON body
                    contentType: "application/json",
                    dataType: 'json',
                    success: function(res){
                        if(res.status === "success") {
                            Swal.fire({
                                icon:'success',
                                title: 'Terhapus!',
                                text: res.message
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: "Gagal!",
                                text: res.message
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: "Terjadi Kesalahan: " + error
                        })
                        console.log("Error AJAX: ", xhr.responseText);
                    }
                });
            }
        });
    });

    $(document).on('click', '#btnSubmit', function(e){
        e.preventDefault();

        const formData = new FormData($('#uploadForm')[0]); //ambil semua data form,termasuk file

        $.ajax({
            url: '../module/crud/crudPembayaran.php',
            type: 'POST',
            data: formData, //data yang dikirim(file juga termasuk)
            processData: false, //agar tdk proses data(FormData yang menangani)
            contentType: false, //jgn set content-type (biarkan browser mengatur multipart)
            dataType: 'json',
            success: function(response){
                if(response.status === 'success') {
                    Swal.fire({
                        title: 'Success',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if(result.isConfirmed) {
                            //Redirect ke halaman login setelah sukses mengirim bukti pembayaran
                            window.location.href = "login.php";
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Gagal',
                        text: response.message,
                        icon: 'error',
                        confirmButtonText: 'Coba Lagi'
                    });

                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: 'Terjadi Kesalahan',
                    text: 'Gagal mengupload bukti pembayaran. Silakan coba lagi.',
                    icon: 'error',
                    confirmButtonText: 'Tutup'
                });
                console.log(xhr.responseText); //Debugging error di console
            }
        });
    });

     //ketika tombol validasi diklik
    $(document).on('click', '.btn-validasi', function(e){
        e.preventDefault();

        const id = $(this).data('id');
        const nama = $(this).data('nama');

        Swal.fire({
            icon: 'question',
            title: 'Validasi Pembayaran',
            text: `Anda yakin ingin memvalidasi pembayaran untuk ${nama}?`,
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, validasi!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../module/crud/crudValidasi.php',
                    type: 'POST',
                    data: {action: "update", id: id},
                    dataType: 'json',
                    success: function(res) {
                        if(res.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: `Pembayaran untuk ${nama} berhasil divalidasi`,
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: res.message || 'Terjadi kesalahan saat validasi',
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error AJAX:", xhr.responseText);
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: "Terjadi kesalahan saat validasi"
                        });
                    }
                });
            }
        });
    });
});

