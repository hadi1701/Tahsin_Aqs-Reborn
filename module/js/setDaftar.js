$(document).ready(function(){
    $(document).ready(function() {
        $("#btn-submit").click(function(e){
            e.preventDefault();

            // Reset tampilan error sebelumnya
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

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

            // Validasi client-side minimal (UX lebih cepat)
            let clientErrors = [];
            if (!formData.username) clientErrors.push('username');
            if (!formData.email) clientErrors.push('email');
            if (!formData.password) clientErrors.push('password');
            if (!formData.nama) clientErrors.push('nama');
            if (!formData.usia) clientErrors.push('usia');
            if (!formData.gender) clientErrors.push('gender');
            if (!formData.no_wa) clientErrors.push('no_wa');
            if (!formData.komunitas) clientErrors.push('komunitas');

            if (clientErrors.length > 0) {
                // Sorot semua field kosong
                clientErrors.forEach(field => {
                    $(`#${field}`).addClass('is-invalid');
                });
                
                Swal.fire({
                    title: '❌ Ada data yang belum diisi',
                    text: 'Silakan lengkapi semua kolom yang wajib.',
                    icon: 'warning',
                    confirmButtonText: 'Mengerti'
                });
                $(`#${clientErrors[0]}`).focus();
                return;
            }

            // Kirim ke server
            $.ajax({
                url: "../module/crud/crudDaftar.php",
                type: "POST",
                data: formData,
                dataType: "json",
                beforeSend: function() {
                    $("#btn-submit").prop("disabled", true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sedang diproses...');
                },
                success: function(response) {
                    // Reset ulang (untuk antisipasi double-submit)
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').remove();

                    // ✅ SUKSES
                    if (response.status === "success") {
                        Swal.fire({
                            title: 'Success',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'Lanjut ke Pembayaran',
                            allowOutsideClick: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "pembayaran.php";
                            }
                        });

                    // ⚠️ VALIDATION_ERROR atau DUPLICATE → tangani SAMA (karena struktur 'errors' identik)
                    } else if (response.status === "validation_error" || response.status === "duplicate") {
                        const errors = response.errors || {};
                        const fields = Object.keys(errors);

                        if (fields.length === 0) {
                            Swal.fire('Perhatian', 'Terjadi kesalahan validasi. Silakan coba lagi.', 'warning');
                            return;
                        }

                        // 🔹 Jika hanya 1 field error → tampilkan spesifik
                        if (fields.length === 1) {
                            const field = fields[0];
                            const msg = errors[field];

                            // Terjemahan field ke label
                            const labelMap = {
                                username: 'Username',
                                email: 'Email',
                                password: 'Password',
                                nama: 'Nama lengkap',
                                usia: 'Usia',
                                gender: 'Jenis kelamin',
                                no_wa: 'Nomor WhatsApp',
                                komunitas: 'Komunitas'
                            };
                            const label = labelMap[field] || field;

                            // Sorot & tampilkan pesan inline
                            const $field = $(`#${field}`);
                            $field.addClass('is-invalid');
                            if ($field.length && !$field.next('.invalid-feedback').length) {
                                $field.after(`<div class="invalid-feedback">${msg}</div>`);
                            }

                            // 🎯 SweetAlert spesifik
                            Swal.fire({
                                title: `❌ ${msg}`,
                                text: `Perbaiki kolom ${label}.`,
                                icon: 'warning',
                                confirmButtonText: 'Mengerti',
                                allowOutsideClick: false
                            }).then(() => {
                                $field.focus();
                            });

                        // 🔹 Jika >1 field error → tampilkan daftar
                        } else {
                            let list = '';
                            let firstField = null;

                            fields.forEach(field => {
                                const msg = errors[field];
                                list += `<li>${msg}</li>`;

                                const $field = $(`#${field}`);
                                $field.addClass('is-invalid');
                                if ($field.length && !$field.next('.invalid-feedback').length) {
                                    $field.after(`<div class="invalid-feedback">${msg}</div>`);
                                }
                                if (!firstField) firstField = $field;
                            });

                            Swal.fire({
                                title: 'Mohon Perbaiki Data',
                                html: `<ul style="text-align:left;margin-top:10px;">${list}</ul>`,
                                icon: 'warning',
                                confirmButtonText: 'Perbaiki',
                                allowOutsideClick: false
                            }).then(() => {
                                if (firstField) firstField.focus();
                            });
                        }

                    // ❌ ERROR SISTEM
                    } else {
                        Swal.fire({
                            title: '❌ Gagal Menyimpan',
                            text: response.message || 'Mohon maaf, terjadi gangguan sistem. Silakan coba lagi nanti.',
                            icon: 'error',
                            confirmButtonText: 'Coba Lagi'
                        });
                    }
                },
                error: function(xhr, status, err) {
                    console.error("AJAX Error:", err);
                    Swal.fire({
                        title: '❌ Koneksi Gagal',
                        text: 'Tidak dapat terhubung ke server. Periksa jaringan internet Anda.',
                        icon: 'error',
                        confirmButtonText: 'Coba Lagi'
                    });
                },
                complete: function() {
                    $("#btn-submit").prop("disabled", false).text("Daftar Sekarang");
                }
            });
        });

        // 🔐 Toggle password visibility (opsional)
        $(document).on('click', '.input-group-text', function() {
            const $input = $(this).siblings('input');
            const type = $input.attr('type') === 'password' ? 'text' : 'password';
            $input.attr('type', type);
            $(this).find('i').toggleClass('bx-hide bx-show');
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
                    $("#modalUpdate #email").val(data.email);
                    $("#modalUpdate #nama").val(data.nama);
                    $("#modalUpdate #usia").val(data.usia);
                    $("#modalUpdate #no_wa").val(data.no_wa);
                    $("#modalUpdate #komunitas").val(data.komunitas);
                    $("#modalUpdate input[name='gender']").filter(`[value='${gender}']`).prop('checked', true);
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

                    //hide modal dulu
                    $('#modalUpdate').modal('hide');

                    //tunggu modal benar" hilang, baru reload
                    $('#modalUpdate').on('hidden.bs.modal', function (){
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
                        text: "Terjadi Kesalahan: " + (response.message || "Data gagal diupdate!")
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
            text: `Hapus data dengan ID: ` + id + "?",
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

    $(document).on('click', '#btn-submit-payment', function(e){
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

