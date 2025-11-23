$(document).ready(function(){
    $("#btn-submit-admin").click(function(e){
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
            url: "../module/crud/crudAdmin.php",
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
                            window.location.href = "daftar_admin.php";
                            $('#formAdmin')[0].reset();
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

    $('.btn-update-admin').on('click', function(e){
        e.preventDefault();

        //ambil id dari data id yang diklik
        const id = $(this).data('id');

        $('#modalUpdateAdmin').modal('show');

        //tampilkan id di input
        $('#modalUpdateAdmin #id').val(id);

        $.ajax({
            url: "../module/crud/crudAdmin.php",
            type: "GET",
            data: {id: id},
            dataType: 'json',
            success: function(response){
                if(response.status === "success") {
                    const data = response.data;

                    //isi field di modal dengan data dari database
                    $("#modalUpdateAdmin #id").val(data.id);
                    $("#modalUpdateAdmin #email").val(data.email);
                    $("#modalUpdateAdmin #password").val('');
                    $("#modalUpdateAdmin #nama").val(data.nama);
                    $("#modalUpdateAdmin #usia").val(data.usia);
                    $("#modalUpdateAdmin #no_wa").val(data.no_wa);

                    //khusus button radio
                    const gender = data.gender.trim().toLowerCase();
                    if (gender === 'laki-laki') {
                        $("#updategenderL").prop('checked', true);
                    } else if (gender === 'perempuan') {
                        $("#updategenderP").prop('checked', true);
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Data tidak ditemukan'
                    });
                }
            },
            error: function(xhr, status, error) {
                if(modalInstance) modalInstance.hide();
                Swal.fire({ icon: 'error', title: 'Terjadi Kesalahan', text: error });
                console.log(xhr.responseText);
            }
        });
    });

    $('#formUpdateAdmin').on('submit', function(e){
        e.preventDefault();

        const formData = $(this).serialize(); //ambil semua input nama = value

        $.ajax({
            url: '../module/crud/crudAdmin.php',
            type: "POST",
            data: formData, //langsung kirim form data
            dataType: 'json',
            success: function(response){
                if(response.status === 'success') {
                    
                    $('#modalUpdateAdmin').modal('hide');

                    $('#modalUpdateAdmin').one('hidden.bs.modal', function(){
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            confirmButtonText: 'OK'
                        }).then(() => {
                            location.reload();
                        });
                        //hapus listener supaya tidak numpuk
                        $(this).off('hidden.bs.modal');
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message ?? "Terjadi Kesalahan: "
                    });
                }
            },
            error: function(xhr, status, error){
                Swal.fire({
                    icon: 'error',
                    title: 'Server Error',
                    text: error
                });
                console.log(xhr.responseText);
            }
        });
    });

    $('.btn-delete-admin').on('click', function(e){
        e.preventDefault();

        const id = $(this).data('id');
        const nama = $(this).data('nama');

        if(!id) {
            alert('Data Id tidak terbaca');
            return;
        }

        Swal.fire({
            icon: 'warning',
            title: 'Yakin?',
            text: "Hapus data dengan nama: " + nama + "?",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if(result.isConfirmed){
                $.ajax({
                    url: "../module/crud/crudAdmin.php",
                    type: "DELETE",
                    data: JSON.stringify({ id: id, nama: nama }), // kirim JSON body
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
});

