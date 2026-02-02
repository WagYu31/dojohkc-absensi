function loading() {
	$("#stat").html('<div class="alert alert-info"><i>Authenticating..</i></div>');
}
$(document).ready(function () {
	$("#login").click(function () { login(); });
});

$('.login').submit(function (e) {
	e.preventDefault();
	$.ajax({
		url:"../login/login-proses.php?action=login",
		type: "POST",
		data: new FormData(this),
		processData: false,
		contentType: false,
		cache: false,
		async: false,
		success: function (data) {
			if (data == 'success') {
				swal({title: 'Berhasil!', text: 'Berhasil Login!', icon: 'success', timer: 1500,});
				setTimeout(function() {window.location.href = "../"}, 2000);
			} else {
				swal({title: 'Oops!', text: data, icon: 'error', timer: 1500,});
			}
		},
	});

});

/* ----------- Add ------------*/
$('.forgot').submit(function (e) {
	e.preventDefault();
	$.ajax({
		url:"../login/login-proses.php?action=forgot",
		type: "POST",
		data: new FormData(this),
		processData: false,
		contentType: false,
		cache: false,
		async: false,
		beforeSend: function () { 
			//loading();
		},
		success: function (data) {
			if (data == 'success') {
				swal({title: 'Berhasil!', text: 'Password berhasil diresset ulang!', icon: 'success', timer: 2000,});
				setTimeout(function() {window.location.href = "../"}, 2000);
			} else {
				swal({title: 'Oops!', text: data, icon: 'error', timer:3000,});
			}

		},
		complete: function () {
			
		},
	});

});