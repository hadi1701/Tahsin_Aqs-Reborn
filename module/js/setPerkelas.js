$(document).ready(function() {

    // LOAD TABLE
    function loadAssign() {
        $("#tableAssign").load("../module/crud/crudPerkelas.php?action=read");
    }

    loadAssign();

    // ASSIGN MURID KE KELAS
    $("#btnAssign").click(function() {
        let murid = $("#murid").val();
        let kelas = $("#kelas").val();

        if (murid === "" || kelas === "") {
            Swal.fire("Gagal", "Pilih murid dan kelas dulu!", "warning");
            return;
        }

        $.ajax({
            url: "../module/crud/crudPerkelas.php?action=assign",
            type: "POST",
            data: { 
                daftar_id: murid,
                class_id: kelas
            },
            success: function(res) {
                let r = JSON.parse(res);

                if (r.status === "exists") {
                    Swal.fire("Peringatan", "Murid sudah terdaftar di batch ini!", "info");
                }
                else if (r.status === "success") {
                    Swal.fire("Berhasil", "Murid berhasil dimasukkan ke kelas!", "success");
                    loadAssign();
                }
            }
        });
    });

    // DELETE MURID DARI KELAS
    $(document).on("click", ".btnDelete", function() {
        let id = $(this).data("id");

        Swal.fire({
            title: "Yakin?",
            text: "Hapus murid dari kelas ini?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, hapus"
        }).then((result) => {
            if (result.isConfirmed) {

                $.ajax({
                    url: "../module/crud/crudPerkelas.php?action=delete",
                    type: "POST",
                    data: { id: id },
                    success: function(res) {
                        let r = JSON.parse(res);

                        if (r.status === "deleted") {
                            Swal.fire("Berhasil", "Murid dihapus dari kelas!", "success");
                            loadAssign();
                        }
                    }
                });

            }
        });
    });

});
