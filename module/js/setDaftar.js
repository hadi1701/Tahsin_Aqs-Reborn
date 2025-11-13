$(document).ready(function(){
    $("#btn-submit").click(function(e){
        e.preventDefault();

        const formData = {
            username: $("#username").val(),
            password: $("#password").val(),
            nama: $("#nama").val(),
            usia: $("#usia").val(),
            gender: $("input[name='gender']:checked").val(),
            no_wa: $("#no_wa").val(),
            komunitas: $("#komunitas").val()
        };

        console.log("Data yang dikirim", formData);

        $.ajax({
            url: "../module/crud/crudDaftar.php",
            type: "POST",
            data: formData,
            dataType: "json",
            success: function(response) {
                if (response.status === "success") {
                    alert(response.message);
                    window.location.href="pembayaran.php";
                    $('#formDaftar')[0].reset();
                } else {
                    alert("Gagal menyimpan data! " + response.message);
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
                    alert(response.message);
                    $('#modalUpdate').modal('hide');
                    location.reload();
                } else {
                    alert("Gagal: " + response.message);
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

        if(confirm("Yakin ingin menghapus data dengan id:" + id + "?")){
            $.ajax({
                url: "../module/crud/crudDaftar.php",
                type: "DELETE",
                data: JSON.stringify({ id: id }), // kirim JSON body
                contentType: "application/json",
                dataType: 'json',
                success: function(res){
                    if(res.status === "success") {
                        alert(res.message);
                        location.reload();
                    } else {
                        alert(res.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert("Terjadi Kesalahan" + error);
                    console.log("Error AJAX: ", xhr.responseText);
                }

            });
        }
    });

     //ketika tombol validasi diklik
    $(document).on('click', '.btn-validasi', function(e){
        e.preventDefault();

        const id = $(this).data('id');
        const nama = $(this).data('nama');

        if (confirm(`Validasi pembayaran untuk ${nama}`)) {
            $.ajax({
                url: '../module/crud/crudPembayaran.php',
                type: 'POST',
                data: {action: "update", id: id},
                dataType: 'json',
                success: function(res) {
                    if(res.status === 'success') {
                        alert(`Pembayaran untuk ${nama} berhasil divalidasi`);
                        location.reload();
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error AJAX:", xhr.responseText);
                    alert("Terjadi kesalahan saat validasi");
                }
            });
        }
    });
});