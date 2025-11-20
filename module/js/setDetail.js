$(document).ready(function(){

    // TOGGLE STATUS
    $(document).on('click', '.toggle-progress', function() {

        const id = $(this).data('id');

        $.ajax({
            url: '../module/crud/crudDetail.php',
            type: 'POST',
            data: {
                action: 'toggle',
                id: id
            },
            dataType: 'json',
            success: function(response) {

                if (response.status === "success") {

                    const badge = $("#badge-" + id);

                    if (response.newStatus === "done") {
                        badge
                            .removeClass("bg-danger")
                            .addClass("bg-success")
                            .text("done");
                    } else {
                        badge
                            .removeClass("bg-success")
                            .addClass("bg-danger")
                            .text("pending");
                    }

                    updateProgressBar();
                }
            },
            error: function() {
                alert("Gagal tersambung ke server");
            }
        });
    });


    // DELETE
    $(document).on("click", ".btn-delete", function(){
        const id = $(this).data("id");

        Swal.fire({
            title: "Yakin hapus?",
            text: "Data progress akan hilang!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, hapus",
            cancelButtonText: "Batal"
        }).then((result)=>{
            if(result.isConfirmed){
                $.ajax({
                    url: "../module/crud/crudDetail.php",
                    type: "POST",
                    dataType: "json",
                    data: { action: "delete", id: id },

                    success: function(res){
                        if(res.status === "success"){
                            Swal.fire("Terhapus!", "Data berhasil dihapus.", "success")
                                .then(()=> location.reload());
                        } else {
                            Swal.fire("Error!", res.message, "error");
                        }
                    },

                    error: function(){
                        Swal.fire("Error!", "Tidak bisa terhubung ke server.", "error");
                    }
                });
            }
        });
    });

});


// ---------------------------
// HITUNG PROGRESS SECARA LIVE
// ---------------------------
function updateProgressBar() {

    const total = $(".toggle-progress").length;

    // hitung yang sudah selesai (done)
    const done = $(".badge.bg-success").length;

    const percent = total > 0 ? Math.round((done / total) * 100) : 0;

    $(".progress-bar")
        .css("width", percent + "%")
        .text(percent + "%");

    $(".progress-info")
        .text(done + " done dari " + total);
}

