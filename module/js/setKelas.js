$(document).ready(function(){

    // 1. setiap ganti kelas → tampilkan murid kelas
    $("#selectKelas").change(function(){
        const classID = $(this).val();
        if(classID === ""){
            $("#tableMurid").html('<tr><td colspan="4" class="text-center">Silakan pilih kelas</td></tr>');
            return;
        }

        $.post("../module/crud/crudKelas.php", 
            { action: "getMembers", class_id: classID },
            function(res){
                if(res.status === "success"){
                    $("#tableMurid").html(res.html);
                } else {
                    $("#tableMurid").html(`
                        <tr><td colspan="4" class="text-center">Tidak ada murid</td></tr>
                    `);
                }
            },
        "json");
    });

    // 2. search murid belum di kelas
    $("#searchMurid").on("keyup", function(){
        let keyword = $(this).val().trim();
        let classID = $("#selectKelas").val();

        if(keyword === "" || classID === ""){
            $("#searchResult").html("");
            return;
        }

        $.post("../module/crud/crudKelas.php",
            { action:"search", keyword: keyword, class_id: classID },
            function(res){
                $("#searchResult").html(res.html || "");
            },
        "json");
    });

    // 3. tambah murid ke kelas
    $(document).on("click", ".btnAddToClass", function(){
        let id = $(this).data("id");
        let classID = $("#selectKelas").val();

        $.post("../module/crud/crudKelas.php",
            { action:"addMember", daftar_id:id, class_id:classID },
            function(res){
                if(res.status === "success"){
                    // refresh member table
                    $("#selectKelas").trigger("change");
                    $("#searchMurid").trigger("keyup");
                } else {
                    alert("Gagal menambah murid");
                }
            },
        "json");
    });

    // 4. hapus murid dari kelas
    $(document).on("click", ".btnRemove", function(){
        let id = $(this).data("id");
        let classID = $("#selectKelas").val();

        $.post("../module/crud/crudKelas.php",
            { action:"removeMember", daftar_id:id, class_id:classID },
            function(res){
                if(res.status === "success"){
                    $("#selectKelas").trigger("change");
                }
            },
        "json");
    });

    $(document).on('click', '.btnDetail', function(){
        const id = $(this).data('id');
        const classID = $(this).data('class');

        window.location.href= `detailPeserta.php?daftar_id=${id}&class_id=${classID}`;
    });
});