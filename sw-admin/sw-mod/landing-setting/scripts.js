// Load Tabs
function loadTabHero() {
    $("#loadLanding").html('<div class="text-center"><div class="spinner-border" role="status"></div><p>Loading data...</p></div>');
    $("#loadLanding").load("sw-mod/landing-setting/form.php?action=hero");
}

function loadTabAbout() {
    $("#loadLanding").html('<div class="text-center"><div class="spinner-border" role="status"></div><p>Loading data...</p></div>');
    $("#loadLanding").load("sw-mod/landing-setting/form.php?action=about");
}

function loadTabFeatures() {
    $("#loadLanding").html('<div class="text-center"><div class="spinner-border" role="status"></div><p>Loading data...</p></div>');
    $("#loadLanding").load("sw-mod/landing-setting/form.php?action=features");
}

function loadTabCta() {
    $("#loadLanding").html('<div class="text-center"><div class="spinner-border" role="status"></div><p>Loading data...</p></div>');
    $("#loadLanding").load("sw-mod/landing-setting/form.php?action=cta");
}

function loadTabPoster() {
    $("#loadLanding").html('<div class="text-center"><div class="spinner-border" role="status"></div><p>Loading data...</p></div>');
    $("#loadLanding").load("sw-mod/landing-setting/form.php?action=poster");
}

function loadTabGaleri() {
    $("#loadLanding").html('<div class="text-center"><div class="spinner-border" role="status"></div><p>Loading data...</p></div>');
    $("#loadLanding").load("sw-mod/landing-setting/form.php?action=galeri");
}

function loadTabAtlet() {
    $("#loadLanding").html('<div class="text-center"><div class="spinner-border" role="status"></div><p>Loading data...</p></div>');
    $("#loadLanding").load("sw-mod/landing-setting/form.php?action=atlet");
}

$(document).ready(function () {
    function loading() {
        $(".loading").show();
        $(".loading").delay(1500).fadeOut(500);
    }

    // Auto-load first tab
    loadTabHero();

    // ==================== SAVE HERO ====================
    $("#loadLanding").on("submit", ".update-hero", function (e) {
        loading();
        e.preventDefault();
        $.ajax({
            url: "sw-mod/landing-setting/proses.php?action=hero",
            type: "POST",
            data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false,
            async: false,
            beforeSend: function () { loading(); },
            success: function (data) {
                if (data == 'success') {
                    swal({ title: 'Berhasil!', text: 'Data Hero berhasil disimpan!', icon: 'success', timer: 1500 });
                    loadTabHero();
                } else {
                    swal({ title: 'Oops!', text: data, icon: 'error', timer: 2000 });
                }
            },
            complete: function () { $(".loading").hide(); },
        });
    });

    // ==================== SAVE ABOUT ====================
    $("#loadLanding").on("submit", ".update-about", function (e) {
        loading();
        e.preventDefault();
        $.ajax({
            url: "sw-mod/landing-setting/proses.php?action=about",
            type: "POST",
            data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false,
            async: false,
            beforeSend: function () { loading(); },
            success: function (data) {
                if (data == 'success') {
                    swal({ title: 'Berhasil!', text: 'Data Tentang Kami berhasil disimpan!', icon: 'success', timer: 1500 });
                    loadTabAbout();
                } else {
                    swal({ title: 'Oops!', text: data, icon: 'error', timer: 2000 });
                }
            },
            complete: function () { $(".loading").hide(); },
        });
    });

    // ==================== SAVE FEATURES ====================
    $("#loadLanding").on("submit", ".update-features", function (e) {
        loading();
        e.preventDefault();

        // Collect features JSON from dynamic cards
        var features = [];
        $('#featureCards .feature-card-item').each(function () {
            features.push({
                icon: $(this).find('.feature-icon-input').val(),
                color: $(this).find('.feature-color-input').val(),
                title: $(this).find('.feature-title-input').val(),
                desc: $(this).find('.feature-desc-input').val()
            });
        });

        var formData = new FormData(this);
        formData.append('features_json', JSON.stringify(features));

        $.ajax({
            url: "sw-mod/landing-setting/proses.php?action=features",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            async: false,
            beforeSend: function () { loading(); },
            success: function (data) {
                if (data == 'success') {
                    swal({ title: 'Berhasil!', text: 'Data Fitur berhasil disimpan!', icon: 'success', timer: 1500 });
                    loadTabFeatures();
                } else {
                    swal({ title: 'Oops!', text: data, icon: 'error', timer: 2000 });
                }
            },
            complete: function () { $(".loading").hide(); },
        });
    });

    // Add feature card
    $("#loadLanding").on("click", "#btnAddFeature", function () {
        var html = '<div class="feature-card-item" style="background:#f9f9f9;border:1px solid #ddd;border-radius:8px;padding:15px;margin:10px 15px;">' +
            '<div class="row">' +
            '<div class="col-md-3"><label>Icon (Ionicon)</label><input type="text" class="form-control feature-icon-input" placeholder="camera-outline"></div>' +
            '<div class="col-md-2"><label>Warna</label><select class="form-control feature-color-input"><option value="red">Merah</option><option value="gold">Emas</option><option value="green">Hijau</option><option value="blue">Biru</option></select></div>' +
            '<div class="col-md-3"><label>Judul</label><input type="text" class="form-control feature-title-input"></div>' +
            '<div class="col-md-3"><label>Deskripsi</label><input type="text" class="form-control feature-desc-input"></div>' +
            '<div class="col-md-1" style="padding-top:25px;"><button type="button" class="btn btn-danger btn-sm btn-remove-feature"><i class="fa fa-trash"></i></button></div>' +
            '</div>' +
            '</div>';
        $('#featureCards').append(html);
    });

    // Remove feature card
    $("#loadLanding").on("click", ".btn-remove-feature", function () {
        $(this).closest('.feature-card-item').fadeOut(300, function () { $(this).remove(); });
    });

    // ==================== SAVE CTA & FOOTER ====================
    $("#loadLanding").on("submit", ".update-cta", function (e) {
        loading();
        e.preventDefault();
        $.ajax({
            url: "sw-mod/landing-setting/proses.php?action=cta",
            type: "POST",
            data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false,
            async: false,
            beforeSend: function () { loading(); },
            success: function (data) {
                if (data == 'success') {
                    swal({ title: 'Berhasil!', text: 'Data CTA & Footer berhasil disimpan!', icon: 'success', timer: 1500 });
                    loadTabCta();
                } else {
                    swal({ title: 'Oops!', text: data, icon: 'error', timer: 2000 });
                }
            },
            complete: function () { $(".loading").hide(); },
        });
    });

    // ==================== ADD POSTER ====================
    $("#loadLanding").on("submit", ".add-poster", function (e) {
        loading();
        e.preventDefault();
        $.ajax({
            url: "sw-mod/landing-setting/proses.php?action=add-poster",
            type: "POST",
            data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false,
            async: false,
            beforeSend: function () { loading(); },
            success: function (data) {
                if (data == 'success') {
                    swal({ title: 'Berhasil!', text: 'Poster berhasil ditambahkan!', icon: 'success', timer: 1500 });
                    loadTabPoster();
                } else {
                    swal({ title: 'Oops!', text: data, icon: 'error', timer: 2000 });
                }
            },
            complete: function () { $(".loading").hide(); },
        });
    });

    // Toggle poster status
    $("#loadLanding").on("click", ".btn-toggle-poster", function () {
        var id = $(this).data('id');
        var status = $(this).data('status');
        $.ajax({
            url: "sw-mod/landing-setting/proses.php?action=toggle-poster",
            type: "POST",
            data: { id: id, status: status },
            success: function (data) {
                if (data == 'success') {
                    swal({ title: 'Berhasil!', text: 'Status poster diperbarui!', icon: 'success', timer: 1500 });
                    loadTabPoster();
                } else {
                    swal({ title: 'Oops!', text: data, icon: 'error', timer: 2000 });
                }
            }
        });
    });

    // Delete poster
    $("#loadLanding").on("click", ".btn-delete-poster", function () {
        var id = $(this).data('id');
        swal({
            title: 'Yakin hapus?',
            text: 'Poster akan dihapus permanen!',
            icon: 'warning',
            buttons: ['Batal', 'Ya, Hapus!'],
            dangerMode: true,
        }).then(function (willDelete) {
            if (willDelete) {
                $.ajax({
                    url: "sw-mod/landing-setting/proses.php?action=delete-poster",
                    type: "POST",
                    data: { id: id },
                    success: function (data) {
                        if (data == 'success') {
                            swal({ title: 'Terhapus!', text: 'Poster berhasil dihapus.', icon: 'success', timer: 1500 });
                            loadTabPoster();
                        } else {
                            swal({ title: 'Oops!', text: data, icon: 'error', timer: 2000 });
                        }
                    }
                });
            }
        });
    });

    // ==================== ADD GALERI ====================
    $("#loadLanding").on("submit", ".add-galeri", function (e) {
        loading();
        e.preventDefault();
        $.ajax({
            url: "sw-mod/landing-setting/proses.php?action=add-galeri",
            type: "POST",
            data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false,
            async: false,
            beforeSend: function () { loading(); },
            success: function (data) {
                if (data == 'success') {
                    swal({ title: 'Berhasil!', text: 'Media berhasil ditambahkan!', icon: 'success', timer: 1500 });
                    loadTabGaleri();
                } else {
                    swal({ title: 'Oops!', text: data, icon: 'error', timer: 2000 });
                }
            },
            complete: function () { $(".loading").hide(); },
        });
    });

    // Toggle galeri status
    $("#loadLanding").on("click", ".btn-toggle-galeri", function () {
        var id = $(this).data('id');
        var status = $(this).data('status');
        $.ajax({
            url: "sw-mod/landing-setting/proses.php?action=toggle-galeri",
            type: "POST",
            data: { id: id, status: status },
            success: function (data) {
                if (data == 'success') {
                    swal({ title: 'Berhasil!', text: 'Status media diperbarui!', icon: 'success', timer: 1500 });
                    loadTabGaleri();
                } else {
                    swal({ title: 'Oops!', text: data, icon: 'error', timer: 2000 });
                }
            }
        });
    });

    // Delete galeri
    $("#loadLanding").on("click", ".btn-delete-galeri", function () {
        var id = $(this).data('id');
        swal({
            title: 'Yakin hapus?',
            text: 'Media akan dihapus permanen!',
            icon: 'warning',
            buttons: ['Batal', 'Ya, Hapus!'],
            dangerMode: true,
        }).then(function (willDelete) {
            if (willDelete) {
                $.ajax({
                    url: "sw-mod/landing-setting/proses.php?action=delete-galeri",
                    type: "POST",
                    data: { id: id },
                    success: function (data) {
                        if (data == 'success') {
                            swal({ title: 'Terhapus!', text: 'Media berhasil dihapus.', icon: 'success', timer: 1500 });
                            loadTabGaleri();
                        } else {
                            swal({ title: 'Oops!', text: data, icon: 'error', timer: 2000 });
                        }
                    }
                });
            }
        });
    });

    // ==================== EDIT POSTER ====================
    $("#loadLanding").on("click", ".btn-edit-poster", function () {
        var id = $(this).data('id');
        var judul = $(this).data('judul');
        var file = $(this).data('file');
        $('#editPosterId').val(id);
        $('#editPosterJudul').val(judul);
        $('#editPosterPreview').attr('src', '../sw-content/poster/' + file);
        $('#modalEditPoster').modal('show');
    });

    $("#loadLanding").on("submit", ".edit-poster-form", function (e) {
        loading();
        e.preventDefault();
        $.ajax({
            url: "sw-mod/landing-setting/proses.php?action=edit-poster",
            type: "POST",
            data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false,
            async: false,
            beforeSend: function () { loading(); },
            success: function (data) {
                if (data == 'success') {
                    $('#modalEditPoster').modal('hide');
                    swal({ title: 'Berhasil!', text: 'Poster berhasil diperbarui!', icon: 'success', timer: 1500 });
                    loadTabPoster();
                } else {
                    swal({ title: 'Oops!', text: data, icon: 'error', timer: 2000 });
                }
            },
            complete: function () { $(".loading").hide(); },
        });
    });

    // ==================== EDIT GALERI ====================
    $("#loadLanding").on("click", ".btn-edit-galeri", function () {
        var id = $(this).data('id');
        var judul = $(this).data('judul');
        var tipe = $(this).data('tipe');
        var file = $(this).data('file');
        $('#editGaleriId').val(id);
        $('#editGaleriJudul').val(judul);
        $('#editGaleriTipe').val(tipe);
        if (tipe === 'foto') {
            $('#editGaleriFotoGroup').show();
            $('#editGaleriVideoGroup').hide();
            $('#editGaleriPreview').attr('src', '../sw-content/galeri/' + file);
            $('#editGaleriYtUrl').val('');
        } else {
            $('#editGaleriFotoGroup').hide();
            $('#editGaleriVideoGroup').show();
            $('#editGaleriYtUrl').val(file);
        }
        $('#modalEditGaleri').modal('show');
    });

    $("#loadLanding").on("submit", ".edit-galeri-form", function (e) {
        loading();
        e.preventDefault();
        $.ajax({
            url: "sw-mod/landing-setting/proses.php?action=edit-galeri",
            type: "POST",
            data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false,
            async: false,
            beforeSend: function () { loading(); },
            success: function (data) {
                if (data == 'success') {
                    $('#modalEditGaleri').modal('hide');
                    swal({ title: 'Berhasil!', text: 'Media berhasil diperbarui!', icon: 'success', timer: 1500 });
                    loadTabGaleri();
                } else {
                    swal({ title: 'Oops!', text: data, icon: 'error', timer: 2000 });
                }
            },
            complete: function () { $(".loading").hide(); },
        });
    });

    // ==================== ADD ATLET ====================
    $("#loadLanding").on("submit", ".add-atlet", function (e) {
        loading();
        e.preventDefault();
        $.ajax({
            url: "sw-mod/landing-setting/proses.php?action=add-atlet",
            type: "POST",
            data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false,
            async: false,
            beforeSend: function () { $(".loading").show(); },
            success: function (data) {
                if (data.trim() === "success") {
                    swal({ title: 'Berhasil!', text: 'Atlet berhasil ditambahkan!', icon: 'success', timer: 2000 });
                    loadTabAtlet();
                } else {
                    swal({ title: 'Oops!', text: data, icon: 'error', timer: 2000 });
                }
            },
            complete: function () { $(".loading").hide(); },
        });
    });

    // Toggle atlet status
    $("#loadLanding").on("click", ".btn-toggle-atlet", function () {
        var id = $(this).data('id');
        var status = $(this).data('status');
        $.ajax({
            url: "sw-mod/landing-setting/proses.php?action=toggle-atlet",
            type: "POST",
            data: { id: id, status: status },
            success: function (data) {
                if (data.trim() === "success") {
                    swal({ title: 'Berhasil!', text: 'Status atlet diperbarui!', icon: 'success', timer: 1500 });
                    loadTabAtlet();
                } else {
                    swal({ title: 'Oops!', text: data, icon: 'error', timer: 2000 });
                }
            },
        });
    });

    // Delete atlet
    $("#loadLanding").on("click", ".btn-delete-atlet", function () {
        var id = $(this).data('id');
        swal({
            title: 'Yakin hapus?',
            text: 'Data atlet akan dihapus permanen!',
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then(function (willDelete) {
            if (willDelete) {
                $.ajax({
                    url: "sw-mod/landing-setting/proses.php?action=delete-atlet",
                    type: "POST",
                    data: { id: id },
                    success: function (data) {
                        if (data.trim() === "success") {
                            swal({ title: 'Berhasil!', text: 'Atlet berhasil dihapus!', icon: 'success', timer: 1500 });
                            loadTabAtlet();
                        } else {
                            swal({ title: 'Oops!', text: data, icon: 'error', timer: 2000 });
                        }
                    },
                });
            }
        });
    });

    // Edit Atlet Modal
    $("#loadLanding").on("click", ".btn-edit-atlet", function () {
        $("#editAtletId").val($(this).data("id"));
        $("#editAtletNama").val($(this).data("nama"));
        $("#editAtletPrestasi").val($(this).data("prestasi"));
        $("#editAtletKategori").val($(this).data("kategori"));
        $("#editAtletPreview").attr("src", "../sw-content/atlet/" + $(this).data("foto"));
        $("#modalEditAtlet").modal("show");
    });

    // Submit Edit Atlet
    $("#loadLanding").on("submit", ".edit-atlet-form", function (e) {
        loading();
        e.preventDefault();
        $.ajax({
            url: "sw-mod/landing-setting/proses.php?action=edit-atlet",
            type: "POST",
            data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false,
            async: false,
            beforeSend: function () { $(".loading").show(); },
            success: function (data) {
                if (data.trim() === "success") {
                    $("#modalEditAtlet").modal("hide");
                    swal({ title: 'Berhasil!', text: 'Data atlet berhasil diperbarui!', icon: 'success', timer: 2000 });
                    loadTabAtlet();
                } else {
                    swal({ title: 'Oops!', text: data, icon: 'error', timer: 2000 });
                }
            },
            complete: function () { $(".loading").hide(); },
        });
    });

});
