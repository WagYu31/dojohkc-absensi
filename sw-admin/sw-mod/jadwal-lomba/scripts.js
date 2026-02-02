'use strict';
function loading(){
    $('.btn-save').prop("disabled", true);
      // add spinner to button
      $('.btn-save').html(
        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...'
      );
     window.setTimeout(function () {
      $('.btn-save').prop("disabled", false);
      $('.btn-save').html('<i class="far fa-save"></i> Simpan'
      );
    }, 2000);
}


$("body").on("click", ".datepicker", function(){
    $(this).datepicker({
      format: 'dd-mm-yyyy',
      autoclose:true
    });
    $(this).datepicker("show");
});


loadData();
function loadData(){
    var table;
    $(document).ready(function() {
        //datatables
        table = $('.datatable').DataTable({
            "processing": true, 
            "serverSide": false, 
            "bAutoWidth": true,
            "bSort": false,
            "bStateSave": true,
            "bDestroy" : true,
            "paging": true,
            "ssSorting" : [[0, 'desc']],
            "iDisplayLength": 25,
            "order": [],
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
                "url": "./sw-mod/jadwal-lomba/sw-datatable.php",
                "type": "POST",
            },
 
            "columnDefs": [{ 
                "targets": [ 0 ], 
                "orderable": false, 
            },],
        });
    });
}

/** Add */
$('.form-add').submit(function (e) {
    loading();
    e.preventDefault();
    $.ajax({
        url:"./sw-mod/jadwal-lomba/sw-proses.php?action=add",
        type: "POST",
        data: new FormData(this),
        processData: false,
        contentType: false,
        cache: false,
        async: false,
        beforeSend: function () { 
            loading();
        },
        success: function (data) {
            if (data == 'success') {
                swal({title: 'Berhasil!', text: 'Data berhasil disimpan!', icon: 'success', timer: 2500,});
                window.setTimeout(window.location.href = "./jadwal-lomba",2500);
            } else {
                swal({title: 'Oops!', text: data, icon: 'error', timer: 2500,});
            }
        },
        complete: function () {
            $(".loading").hide();
        },
    });
});

$('.form-update').submit(function (e) {
    loading();
    e.preventDefault();
    $.ajax({
        url:"./sw-mod/jadwal-lomba/sw-proses.php?action=update",
        type: "POST",
        data: new FormData(this),
        processData: false,
        contentType: false,
        cache: false,
        async: false,
        beforeSend: function () { 
            loading();
        },
        success: function (data) {
            if (data == 'success') {
                swal({title: 'Berhasil!', text: 'Data berhasil disimpan!', icon: 'success', timer: 2500,});
                window.setTimeout(window.location.href = "./jadwal-lomba",2500);
            } else {
                swal({title: 'Oops!', text: data, icon: 'error', timer: 2500,});
            }
        },
        complete: function () {
            $(".loading").hide();
        },
    });
});

/** Hapus data */
$(document).on('click', '.btn-delete', function(){ 
    var id = $(this).attr("data-id");
      swal({
        text: "Anda yakin ingin menghapus data ini?",
        icon: "warning",
          buttons: {
            cancel: true,
            confirm: true,
          },
        value: "yes",
      })

      .then((value) => {
        if(value) {
            loading();
            $.ajax({  
                 url:'./sw-mod/jadwal-lomba/sw-proses.php?action=delete',
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


$(document).on('click', '.btn-cekspp', function(){
    $('.modal-cekspp').modal('show');
    $('.modal-title').html('Cek Data SPP');
});


$(".user-spp").change(function(){
    loadDataSPP();
});

$(".tahun_pelajaran").change(function(){
    loadDataSPP();
});


function loadDataSPP() {
    var user = $('.user-spp').val();
    var tahun_pelajaran = $('.tahun_pelajaran').val();
    if(user == '' || tahun_pelajaran == ''){
        //swal({title: 'Oops!', text: 'Silahkan pilih filter datanya', icon: 'error', timer: 1500,});
    }else{
        $.ajax({
            type: 'POST',
            url  : './sw-mod/jadwal-lomba/sw-proses.php?action=cek-data-spp',
            data: {user:user,tahun_pelajaran:tahun_pelajaran},
            cache: false,
            success: function(data){
                $(".load-data-spp").html(data);
            }
        });
   }  
};



