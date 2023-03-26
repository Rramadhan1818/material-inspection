<?php
date_default_timezone_set('Asia/Jakarta');
?>
<!-- Required meta tags -->
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!-- <meta http-equiv="X-Frame-Options" content="SAMEORIGIN"> -->
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<meta name="ATI" content="ATI">
<meta name="author" content="">
<link rel="icon" type="ico" href="../assets/img/icon/ATI_bg.ico">
<title>PT AT Indonesia</title>
<!-- Javascript -->
<script src="../assets/js/jquery-3.6.0.min.js"></script>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/admin.min.js"></script>
<script src="../assets/js/bootstrap.min.js"></script>


<!-- Font Awesome  -->
<!-- <link rel="stylesheet" href="assets/support/font_awesome/all.min.css"> -->

<script src="../assets/support/font_awesome/all.min.js"></script>
<link rel="stylesheet" href="../assets/support/sweatalert2_/sweetalert2.min.css">

<!-- Daterange  -->
<script src="../assets/support/daterange/moment.min.js"></script>
<script src="../assets/support/daterange/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="../assets/support/daterange/daterangepicker.css" />

<!-- DateTimePicker -->
<link href="../assets/support/datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
<script src="../assets/support/datepicker/js/bootstrap-datepicker.min.js"></script>

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
<link rel="stylesheet" href="../assets/css/main_min_new.css">

<!-- Chart 
<link rel="stylesheet" href="assets/css/apexchart.css">
<script src="assets/js/apexchart.js"></script>
-->


<!-- Custom CSS -->
<link rel="stylesheet" href="../assets/css/select2.min.css">
<link rel="stylesheet" href="../assets/css/custom_2.css">
<link rel="stylesheet" href="../assets/css/custom.css">
<link rel="stylesheet" href="../assets/css/select2-bootstrap4.min.css">
<script src="../assets/support/sweatalert2_/sweetalert2.all.min.js"></script>
<script src="../assets/js/select2.min.js"></script>
<script src="../assets/js/jquery-validate.min.js"></script>

<style type="text/css">
	.preloader {
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		z-index: +100000;
		background-color: rgba(0, 0, 0, 0.65);
	}

	.preloader .loading {
		position: fixed;
		left: 50%;
		top: 50%;
		transform: translate(-50%, -50%);
		font: 14px arial;
	}
</style>

<script type="text/javascript">
	$(function () {
			//Initialize Select2 Elements
			$('.select2').select2()

			//Initialize Select2 Elements
			$('.select2bs4').select2({
				theme: 'bootstrap4'
			})
			
			$('.my-colorpicker2').on('colorpickerChange', function (event) {
				$('.my-colorpicker2 .fa-square').css('color', event.color.toString());
			});

			$("input[data-bootstrap-switch]").each(function () {
				$(this).bootstrapSwitch('state', $(this).prop('checked'));
			});

		})

		window.onload = function() { jam(); }

		 function jam() {
		  var e = document.getElementById('jam'),
		  d = new Date(), h, m, s;
		  h = d.getHours();
		  m = set(d.getMinutes());
		  s = set(d.getSeconds());

		  e.innerHTML = h +':'+ m +':'+ s;

		  setTimeout('jam()', 1000);
		 }

	function set(e) {
		e = e < 10 ? '0' + e : e;
		return e;
	}

	function fullScreen() {
		var el = document.documentElement,
			rfs = // for newer Webkit and Firefox
			el.requestFullScreen ||
			el.webkitRequestFullScreen ||
			el.mozRequestFullScreen ||
			el.msRequestFullScreen;

		if (typeof rfs != "undefined" && rfs) {
			rfs.call(el);
		} else if (typeof window.ActiveXObject != "undefined") {
			// for Internet Explorer
			var wscript = new ActiveXObject("WScript.Shell");
			if (wscript != null) {
				wscript.SendKeys("{F11}");
			}
		}

	}

	$(window).bind("load", function () {
		$('#loading').fadeOut(500);
	});
	// End -->
	//var refreshId = setInterval(fullScreen, 1000);
</script>


