
loadData();
function loadData(){
    var table;
    $(document).ready(function() {
        //datatables
        table = $('.datatable').DataTable({
            "scrollY": false,
            "scrollX": false,
            "processing": true, 
            "serverSide": false, 
            "bAutoWidth": true,
            "bSort": false,
            "bStateSave": true,
            "bDestroy" : true,
            "paging": true,
            "ssSorting" : [[0, 'desc']],
            "iDisplayLength": 25,
           // "order": [[1, 'desc']],
            
            "aLengthMenu": [
                [25, 30, 50, -1],
                [25, 30, 50, "All"]
            ],
            language: {
              paginate: {
                previous: "<i class='fa fa-angle-double-left'></i>",
                next: "<i class='fa fa-angle-double-right'></i>"
              }
            },
            "ajax": {
                "url": "./sw-mod/spp/sw-datatable.php",
                "type": "POST",
            },
 
            "columnDefs": [{ 
                "targets": [ 0 ], 
                "orderable": false, 
            },],
        });
    });

}

$(document).on('click', '.btn-add', function(){
    $('.modal-add').modal('show');
    $('.modal-title').html('Tambah Master SPP');
    $(".form-add").trigger("reset");
    $('.id').val('');
});

$(".form-add").validate({
    rules: {
        field: {
            required: true
        },
    },

    // Specify validation error messages
    messages: {
        field: {
            required: "Silahkan masukkan data sesuai inputan",
        },
    },
    // in the "action" attribute of the form when valid
    submitHandler: submitForm_Add
    });

/* handle form submit */
function submitForm_Add() { 
    var data = $(".form-add").serialize();
    $.ajax({    
        type : 'POST',
        url  : 'sw-mod/spp/sw-proses.php?action=add',
        data : data,
        cache: false,
        async: false,
        success: function (data) {
            if (data == 'success') {
                swal({title: 'Berhasil!', text: 'Data berhasil disimpan.!', icon: 'success', timer: 2500,});
                $(".form-add").trigger("reset");
                $('.modal-add').modal('hide');
                loadData();
            } else {
                swal({title: 'Oops!', text: data, icon: 'error', timer: 2500,});
                loadData();
            }
        }
    });
    return false; 
}

/**  Update*/
$(document).on('click', '.btn-update', function(){
    var id = $(this).attr("data-id");
    var tahun = $(this).attr("data-tahun");
    var nominal = $(this).attr("data-nominal");
    $('.id').val(id);
    $('.tahun').val(tahun);
    $('.nominal').val(nominal);
    $('.modal-add').modal('show');
    $('.modal-title').html('Update Master SPP');
});


 /* ------------- Set Active  --------------*/
 $(document).on('click', '.btn-active', function(){
    var id = $(this).attr("data-id");
    var active = $(".active"+id).attr("data-active");
    if(active == "Y"){
        var dataactive = "N";
    }else{
        var dataactive = "Y";
    }
     var dataString = 'id='+ id + '&active='+ dataactive;
    $.ajax({
        type: "POST",
        url: "./sw-mod/spp/sw-proses.php?action=active",
        data: dataString,
        success: function (data) {
            if(active == "Y"){
                $(".active"+id).attr("data-active","N");
            }else{
                $(".active"+id).attr("data-active","Y");
            }
  
          if (data == 'success') {
                console.log('Successfully set active');
            }else{
               console.log(data);
            }
        }
    });
  });

/** Hapus data  */
$(document).on('click', '.btn-delete', function(){ 
    var id = $(this).attr("data-id");
      swal({
        text: "Anda yakin ingin menghapus data ini.?",
        icon: "warning",
          buttons: {
            cancel: true,
            confirm: true,
          },
        value: "yes",
      })

      .then((value) => {
        if(value) {
            $.ajax({  
                 url:'./sw-mod/spp/sw-proses.php?action=delete',
                 type:'POST',    
                 data:{id:id},  
                success:function(data){ 
                    if (data == 'success') {
                        swal({title: 'Berhasil!', text: 'Data berhasil dihapus.!', icon: 'success', timer: 2500,});
                        loadData();
                    } else {
                        swal({title: 'Gagal!', text: data, icon: 'error', timer:2500,});
                        
                    }
                 }  
            });  
       } else{  
        return false;
    }  
});
}); 
    