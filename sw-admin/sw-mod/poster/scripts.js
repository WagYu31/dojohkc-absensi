
function loading() {
    $('.btn-save').prop("disabled", true);
    $('.btn-save').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...');
    window.setTimeout(function () {
        $('.btn-save').prop("disabled", false);
        $('.btn-save').html('<i class="far fa-save"></i> Simpan');
    }, 2000);
}

loadData();
function loadData() {
    var table;
    $(document).ready(function () {
        table = $('.datatable').DataTable({
            "processing": true,
            "serverSide": false,
            "bAutoWidth": true,
            "bSort": false,
            "bStateSave": true,
            "bDestroy": true,
            "paging": true,
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
                "url": "./sw-mod/poster/sw-datatable.php",
                "type": "POST",
            },
            "columnDefs": [{
                "targets": [0],
                "orderable": false,
            },],
        });
    });
}

/** Upload Drag and Drop */
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('.image-upload-wrap').hide();
            $('.file-upload-image').attr('src', e.target.result);
            $('.file-upload-content').show();
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        removeUpload();
    }
}

function removeUpload() {
    $('.file-upload-input').replaceWith($('.file-upload-input').clone());
    $('.file-upload-content').hide();
    $('.image-upload-wrap').show();
}
$('.image-upload-wrap').bind('dragover', function () {
    $('.image-upload-wrap').addClass('image-dropping');
});
$('.image-upload-wrap').bind('dragleave', function () {
    $('.image-upload-wrap').removeClass('image-dropping');
});

/** Add */
$('.form-add').submit(function (e) {
    e.preventDefault();
    $.ajax({
        url: "./sw-mod/poster/sw-proses.php?action=add",
        type: "POST",
        data: new FormData(this),
        processData: false,
        contentType: false,
        cache: false,
        async: false,
        success: function (data) {
            if (data === 'success') {
                swal({ title: 'Berhasil!', text: 'Poster berhasil disimpan!', icon: 'success', timer: 2500 });
                setTimeout(() => { window.location.href = "./poster"; }, 2500);
            } else {
                swal({ title: 'Oops!', text: data, icon: 'error', timer: 2500 });
            }
        }
    });
});

/** Update */
$('.form-update').submit(function (e) {
    e.preventDefault();
    $.ajax({
        url: "./sw-mod/poster/sw-proses.php?action=update",
        type: "POST",
        data: new FormData(this),
        processData: false,
        contentType: false,
        cache: false,
        async: false,
        success: function (data) {
            if (data === 'success') {
                swal({ title: 'Berhasil!', text: 'Poster berhasil diupdate!', icon: 'success', timer: 2500 });
                setTimeout(() => { window.location.href = "./poster"; }, 2500);
            } else {
                swal({ title: 'Oops!', text: data, icon: 'error', timer: 2500 });
            }
        }
    });
});

/** Delete */
$(document).on('click', '.btn-delete', function () {
    var id = $(this).attr("data-id");
    swal({
        text: "Anda yakin ingin menghapus poster ini?",
        icon: "warning",
        buttons: { cancel: true, confirm: true },
        value: "yes",
    }).then((value) => {
        if (value) {
            loading();
            $.ajax({
                url: './sw-mod/poster/sw-proses.php?action=delete',
                type: 'POST',
                data: { id: id },
                success: function (data) {
                    if (data == 'success') {
                        swal({ title: 'Berhasil!', text: 'Poster berhasil dihapus!', icon: 'success', timer: 2500 });
                        loadData();
                    } else {
                        swal({ title: 'Gagal!', text: data, icon: 'error', timer: 2500 });
                    }
                }
            });
        } else {
            return false;
        }
    });
});
