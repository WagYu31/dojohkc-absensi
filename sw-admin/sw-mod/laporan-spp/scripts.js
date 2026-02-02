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


$(document).on('click', '.btn-filter', function(){
    loadData();
});

loadData();
function loadData() {
    var user = $('.user').val();
    var tahun_pelajaran = $('.tahun-pelajaran').val();
    $.ajax({
        type: 'POST',
        url  : './sw-mod/laporan-spp/sw-proses.php?action=filtering',
        data: {user:user,tahun_pelajaran:tahun_pelajaran},
        cache: false,
        success: function(data){
            $(".load-data").html(data);
        }
    }); 
};


/** Print */
$(document).on('click', '.btn-print', function(){
    var user   = $('.user').val();
    var tahun_pelajaran   = $('.tahun-pelajaran').val();
    var url     = "./sw-mod/laporan-spp/print.php?action=print&user="+user+"&tahun_pelajaran="+tahun_pelajaran+""; 
    window.open(url, '_blank');
});

$(document).on('click', '.btn-download', function(){
    var user   = $('.user').val();
    var tahun_pelajaran   = $('.tahun-pelajaran').val();
    var url     = "./sw-mod/laporan-spp/print.php?action=excel&user="+user+"&tahun_pelajaran="+tahun_pelajaran+""; 
    window.open(url, '_blank');
});


