$(document).ready(function() {
    $('#swdatatable').dataTable({
        "iDisplayLength": 20,
        "aLengthMenu": [[20, 30, 50, -1], [20, 30, 50, "All"]]
    });

    function loading(){
        $(".loading").show();
        $(".loading").delay(1500).fadeOut(500);
    }


function loadDataIzin() {
    $('#sw-datatable').dataTable( {
            "bProcessing": true,
            "bServerSide": false,
            "bAutoWidth": true,
            "bSort": false,
            "bStateSave": true,
            "bDestroy" : true,
            "ssSorting" : [[0, 'desc']],
            "iDisplayLength": 25,
            "aLengthMenu": [
                [25, 30, 50, -1],
                [25, 30, 50, "All"]
            ],
            "sAjaxSource": "sw-mod/izin/sw-datatable.php",
           // "aoColumns": [null,null,null,null,null,null,null,null],
    });
}

loadDataIzin();




$(document).on('click', '.btn-setujui', function(){ 
    var id = $(this).attr("data-id");
    $.ajax({  
        url:"sw-mod/izin/proses.php?action=setujui",
        type:'POST',    
        data:{id:id}, 
           success: function (data) {
               if (data == 'success') {
                   swal({title: 'Berhasil!', text: 'Data berhasil disimpan.!', icon: 'success', timer: 2000,});
                   loadDataIzin();
               } else {
                   swal({title: 'Oops!', text: data, icon: 'error', timer: 1500,});
               }

           },   
   });
});


    /* -------------------- Edit ------------------- */
     $(document).on('click', '.update-status', function(){ 
            var id = $(this).attr("data-id");
            var status = $(this).attr("data-status");
        $.ajax({  
             url:"sw-mod/izin/proses.php?action=update-status",
             type:'POST',    
             data:{id:id,status:status}, 
                beforeSend: function () { 
                    loading();
                },
                success: function (data) {
                    if (data == 'success') {
                        swal({title: 'Berhasil!', text: 'Data berhasil disimpan.!', icon: 'success', timer: 2000,});
                        loadDataIzin();
                    } else {
                        swal({title: 'Oops!', text: data, icon: 'error', timer: 1500,});
                    }

                },
                complete: function () {
                    $(".loading").hide();
                },
        });
    });

    /*------------ Delete -------------*/
     $(document).on('click', '.delete', function(){ 
            var id = $(this).attr("data-id");
            var employees_id = $(this).attr("data-employees");
              swal({
                text: "Anda yakin menghapus data ini?",
                icon: "warning",
                  buttons: {
                    cancel: true,
                    confirm: true,
                  },
                value: "delete",
              })

              .then((value) => {
                if(value) {
                    loading();
                    $.ajax({  
                         url:"sw-mod/izin/proses.php?action=delete",
                         type:'POST',    
                         data:{id:id,employees_id:employees_id},  
                        success:function(data){ 
                            if (data == 'success') {
                                swal({title: 'Berhasil!', text: 'Data berhasil dihapus.!', icon: 'success', timer: 3500,});
                                setTimeout(function(){ location.reload(); }, 2500);
                            } else {
                                swal({title: 'Gagal!', text: data, icon: 'error', timer: 2500,});
                                
                            }
                         }  
                    });  
               } else{  
                return false;
            }  
        });
    }); 

});