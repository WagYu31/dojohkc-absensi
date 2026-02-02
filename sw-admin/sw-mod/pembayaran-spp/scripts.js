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
    var user    = $('.user').val();
    var status  = $('.status').val();
    var tahun_pelajaran = $('.tahun-pelajaran').val();
    var bulan   = $('.bulan').val();
    var tahun   = $('.tahun').val();
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
                "url": "./sw-mod/pembayaran-spp/sw-datatable.php",
                "type": "POST",
                data: {user:user,status:status,tahun_pelajaran:tahun_pelajaran,bulan:bulan,tahun:tahun},
            },
 
            "columnDefs": [{ 
                "targets": [ 0 ], 
                "orderable": false, 
            },],
        });
    });
}

$(".user").change(function(){
    loadData();
});

$(".status").change(function(){
    loadData();
});

$(".tahun-pelajaran").change(function(){
    loadData();
});

$(".bulan").change(function(){
    loadData();
});

$(".tahun").change(function(){
    loadData();
});



$(document).on('click', '.btn-stts-pembayaran', function() {
    var id = $(this).attr("data-id");
    var status = $(this).attr("data-status");
    $.ajax({
        type: "POST",
        url: "./sw-mod/pembayaran-spp/sw-proses.php?action=status-pembayaran",
        data: {id:id,status:status},
        success: function (data) {
          if (data == 'success') {
                if (status == 'pending') {
                    $(".badge-pembayaran" + id).attr("class", "badge-pembayaran" + id + " btn btn-warning btn-xs");
                    $(".badge-pembayaran" + id).html("Pending");

                } else if (status == 'berhasil') {
                    $(".badge-pembayaran" + id).attr("class", "badge-pembayaran" + id + " btn btn-primary btn-xs");
                    $(".badge-pembayaran" + id).html("Berhasil");

                } else {
                    $(".badge-pembayaran" + id).attr("class", "badge-pembayaran" + id + " btn btn-danger btn-xs");
                    $(".badge-pembayaran" + id).html("Gagal");
                }
                console.log(data);
            }else{
               console.log(data);
            }
        }
    });
});



function loadDataPembayaran() {
    var user = $('.user-pembayaran').val();
    var tahun_pelajaran = $('.tahun-pelajaran').val();
    if(user == '' || tahun_pelajaran == ''){
        //swal({title: 'Oops!', text: 'Silahkan pilih filter datanya', icon: 'error', timer: 1500,});
    }else{
        $.ajax({
            type: 'POST',
            url  : './sw-mod/pembayaran-spp/sw-proses.php?action=data-pembayaran',
            data: {user:user,tahun_pelajaran:tahun_pelajaran},
            cache: false,
            success: function(data){
                $(".load-data-pembayaran").html(data);
            }
        });
   }  
};

$(".user-pembayaran").change(function(){
    loadDataPembayaran();
    $('.nominal').focus();
});


$(".user-pembayaran").change(function(){
    loadDataPembayaran();
});

$(".tahun-pelajaran").change(function(){
    loadDataPembayaran();
});


/** Add */
$('.form-add').submit(function (e) {
    loading();
    e.preventDefault();
    $.ajax({
        url:"./sw-mod/pembayaran-spp/sw-proses.php?action=add",
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
                swal({title: 'Berhasil!', text: 'Pembayaran berhasil disimpan!', icon: 'success', timer: 2500,});
                loadDataPembayaran();
                $('.nominal').val('0');
                $('.nominal').focus();
                $('.id').val('');
            } else {
                swal({title: 'Oops!', text: data, icon: 'error', timer: 2500,});
            }

        },
        complete: function () {
            $(".loading").hide();
        },
    });
});


$(document).on('click', '.btn-update', function(){
    var id = $(this).attr('data-id');
    $.ajax({
        type: 'POST',
        url  : './sw-mod/pembayaran-spp/sw-proses.php?action=get-data-update',
        data: {id:id},
        dataType:'json',
        success: function(response) {
            $('.id').val(response.pembayaran_spp_id);
            $('.user').val(response.user_id);
            $('.kelas').val(response.kelas);
            $('.tahun-pembayaran').val(response.tahun_pelajaran);
            $('.bulan').val(response.bulan);
            $('.tahun').val(response.tahun);
            $('.nominal').val(response.nominal);
        }, error: function(response){
            console.log(response.responseText);
        }
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
                 url:'./sw-mod/pembayaran-spp/sw-proses.php?action=delete',
                 type:'POST',    
                 data:{id:id},  
                success:function(data){ 
                    if (data == 'success') {
                        swal({title: 'Berhasil!', text: 'Data berhasil dihapus.!', icon: 'success', timer: 2500,});
                        loadDataPembayaran();
                        $('.id').val('');
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
    loadDataPembayaranSPP();
});

$(".tahun_pelajaran").change(function(){
    loadDataPembayaranSPP();
});


function loadDataPembayaranSPP() {
    var user = $('.user-spp').val();
    var tahun_pelajaran = $('.tahun_pelajaran').val();
    if(user == '' || tahun_pelajaran == ''){
        //swal({title: 'Oops!', text: 'Silahkan pilih filter datanya', icon: 'error', timer: 1500,});
    }else{
        $.ajax({
            type: 'POST',
            url  : './sw-mod/pembayaran-spp/sw-proses.php?action=cek-data-spp',
            data: {user:user,tahun_pelajaran:tahun_pelajaran},
            cache: false,
            success: function(data){
                $(".load-data-spp").html(data);
            }
        });
   }  
};


$(document).on('click', '.btn-print', function(){
    var order        = $(this).attr("data-order");
    var url         = "./sw-mod/pembayaran-spp/print.php?action=print&order="+order+""; 
    window.open(url, '_blank');
});


$(document).on('click', '.btn-download', function(){
    var status = $('.status').val();
    var tahun_ajaran = $('.tahun-pelajaran').val();
    var bulan = $('.bulan').val();
    var tahun = $('.tahun').val();
    var url         = "./sw-mod/pembayaran-spp/print.php?action=excel&status="+status+"&tahun_ajaran="+tahun_ajaran+"&bulan="+bulan+"&tahun="+tahun+""; 
    window.open(url, '_blank');
});

$(document).on('click', '.btn-print-cekspp', function(){
    var user = $('.user-spp').val();
    var tahun_ajaran = $('.tahun-pelajaran').val();
    var url         = "./sw-mod/pembayaran-spp/print.php?action=print-cekspp&user="+user+"&tahun_pelajaran="+tahun_ajaran+""; 
    window.open(url, '_blank');
});

