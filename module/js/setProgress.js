$(document).ready(function(){
    $(document).on("click", ".btnProgress", function () {
    
        const daftar_id = $(this).data("id");
        const class_id  = $(this).data("class");
    
        $("#progress_daftar_id").val(daftar_id);
        $("#progress_class_id").val(class_id);
    
        $.post(
            "../module/crud/crudProgress.php",
            { action: "get", daftar_id, class_id },
            function(res) {
                if (res.status === "success") {
                    $("#progress_session").val(res.session_number);
                    $("#progress_material").val(res.material);
                    $("#progress_notes").val(res.notes);
                    $("#progress_solution").val(res.solution);
                } else {
                    $("#formProgress")[0].reset();
                }
    
                $("#modalProgress").modal("show");
            },
            "json"
        );
    });
    
    $("#btnSaveProgress").click(function () {
    
        $.post(
            "../module/crud/crudProgress.php",
            {
                action: "update",
                daftar_id: $("#progress_daftar_id").val(),
                class_id: $("#progress_class_id").val(),
                session_number: $("#progress_session").val(),
                material: $("#progress_material").val(),
                notes: $("#progress_notes").val(),
                solution: $("#progress_solution").val()
            },
            function (res) {
                if (res.status === "success") {
                    $('#modalProgress').modal('hide');

                    $('#modalProgress').one('hidden.bs.modal', function(){
                        Swal.fire({
                            icon: 'success',
                            title: 'Alhamdulillah',
                            text: 'Progress telah terkirim'
                        }).then(()=>{
                            $("#modalProgress").modal("hide");
                        });

                        $(this).off('hidden.bs.modal');
                    });
                }
            },
            "json"
        );
    });

});

