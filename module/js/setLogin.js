$(document).ready(function(){
    $('#formLogin').on('submit', function(e){
        e.preventDefault();

        const username = $("#username").val().trim();
        const password = $("#password").val().trim();

        // ✅ Validasi minimal di sisi client
        if (!username) {
            Swal.fire('Perhatian', 'Username tidak boleh kosong.', 'warning');
            $('#username').focus();
            return;
        }
        if (!password) {
            Swal.fire('Perhatian', 'Password tidak boleh kosong.', 'warning');
            $('#password').focus();
            return;
        }

        $.ajax({
            url: '../module/crud/crudLogin.php',
            type: 'POST',
            data: { username, password},
            dataType: 'json',
            success: function(res) {

                //admin
                if(res.status === 'admin'){
                    window.location.href= "../admin/admin.php";
                    return;
                }

                //user blm bayar
                if(res.status === 'unpaid'){
                    Swal.fire({
                        icon: 'warning',
                        title: 'Belum Bayar',
                        text: 'Silakan upload bukti pembayaran.'
                    }).then(() =>{
                        window.location.href = "pembayaran.php";
                    });
                    return;
                }

                //user menunggu validasi
                if(res.status === 'waiting') {
                    Swal.fire({
                        icon: 'info',
                        title: 'Menunggu Validasi',
                        text: 'Pembayaran Anda sedang diverifikasi oleh panitia. Mohon bersabar.',
                        confirmButtonText: 'Mengerti'
                    });
                    return;
                }

                //user sudah active
                if(res.status === 'user'){
                    window.location.href = '../user/user.php';
                    return;
                }

                //salah username/password
                if (res.status === 'error') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Login',
                        text: res.message || 'Username atau password salah.',
                        confirmButtonText: 'Coba Lagi'
                    }).then(() => {
                        // Fokus ke password (biasanya yang salah)
                        $('#password').val('').focus();
                    });
                    return;
                }

                // 🛑 Status tidak dikenal (fallback aman)
                Swal.fire({
                    icon: 'question',
                    title: 'Status Tidak Dikenali',
                    text: 'Respons dari server tidak sesuai. Silakan coba lagi.',
                    confirmButtonText: 'OK'
                });
            },

            error: function(){
                Swal.fire("Error", "Terjadi kesalahan pada server", "error");
            }
        });
    });
});