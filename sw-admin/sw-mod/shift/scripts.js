
$('#swdatatable').dataTable({
    "iDisplayLength": 20,
    "aLengthMenu": [[20, 30, 50, -1], [20, 30, 50, "All"]]
});


function loadData() {
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
            "sAjaxSource": "sw-mod/shift/sw-datatable.php",
            //"aoColumns": [null,null,null,null,null,null,null,null],
    });
}

 loadData();

function loading(){
    $(".loading").show();
    $(".loading").delay(1500).fadeOut(500);
}


//Timepicker
$('.timepicker').timepicker({
    showInputs: false,
    showMeridian: false,
    use24hours: true,
    format :'HH:mm'
})

/* ----------- Add ------------*/
$('.form-add').submit(function (e) {
        loading();
        e.preventDefault();
        $.ajax({
            url:"sw-mod/shift/proses.php?action=add",
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
                    swal({title: 'Berhasil!', text: 'Data berhasil disimpan.!', icon: 'success', timer: 1500,});
                   setTimeout(function(){location.reload(); }, 1500);
                   //window.setTimeout(window.location.href = "./shift",2500);
                } else {
                    swal({title: 'Oops!', text: data, icon: 'error', timer: 1500,});
                }

            },
            complete: function () {
                $(".loading").hide();
            },
        });
  });



/* -------------------- Edit ------------------- */
$('.form-update').submit(function (e) {
    loading();
    e.preventDefault();
    $.ajax({
        url:"sw-mod/shift/proses.php?action=update",
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
                swal({title: 'Berhasil!', text: 'Data berhasil disimpan.!', icon: 'success', timer: 1500,});
                setTimeout(function(){location.reload(); }, 1500);
            } else {
                swal({title: 'Oops!', text: data, icon: 'error', timer: 1500,});
            }
        },
        complete: function () {
            $(".loading").hide();
        },
    });
});
