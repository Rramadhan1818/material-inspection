 <ul class="nav nav-tabs" id="myTab" role="tablist">
     <li class="nav-item" role="presentation">
         <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#tbl_1" type="button"
             role="tab" aria-controls="home" aria-selected="true"> 0.01mm - 0~1 mm</button>
     </li>
     <li class="nav-item" role="presentation">
         <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#tbl_2" type="button" role="tab"
             aria-controls="profile" aria-selected="false">0.01mm - 0~0.8 mm</button>
     </li>
     <li class="nav-item" role="presentation">
         <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#tbl_3" type="button" role="tab"
             aria-controls="contact" aria-selected="false">0.01mm - 0~10 mm</button>
     </li>
     <li class="nav-item" role="presentation">
         <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#tbl_4" type="button" role="tab"
             aria-controls="contact" aria-selected="false">0.1mm - 0~4 mm</button>
     </li>
     <li class="nav-item" role="presentation">
         <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#tbl_5" type="button" role="tab"
             aria-controls="contact" aria-selected="false">0.001mm - 0~0.2mm</button>
     </li>
     <li class="nav-item" role="presentation">
         <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#tbl_6" type="button" role="tab"
             aria-controls="contact" aria-selected="false">0.001mm - 0~1mm</button>
     </li>
     <li class="nav-item" role="presentation">
         <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#tbl_7" type="button" role="tab"
             aria-controls="contact" aria-selected="false">0.001mm - 0~0.1mm</button>
     </li>
 </ul>
 <div class="tab-content" id="myTabContent">
     <div class="tab-pane fade show active" id="tbl_1" role="tabpanel" aria-labelledby="home-tab">
         <?php $std = 'STD1'; include 'table/tbl_dial_gauge1.php'; ?>
     </div>
     <div class="tab-pane fade" id="tbl_2" role="tabpanel" aria-labelledby="home-tab">
         <?php $std = 'STD2'; include 'table/tbl_dial_gauge2.php'; ?>
     </div>
     <div class="tab-pane fade" id="tbl_3" role="tabpanel" aria-labelledby="home-tab">
         <?php $std = 'STD3'; include 'table/tbl_dial_gauge3.php'; ?>
     </div>
     <div class="tab-pane fade" id="tbl_4" role="tabpanel" aria-labelledby="home-tab">
         <?php $std = 'STD4'; include 'table/tbl_dial_gauge4.php'; ?>
     </div>
     <div class="tab-pane fade" id="tbl_5" role="tabpanel" aria-labelledby="home-tab">
         <?php $std = 'STD5'; include 'table/tbl_dial_gauge5.php'; ?>
     </div>
     <div class="tab-pane fade" id="tbl_6" role="tabpanel" aria-labelledby="home-tab">
         <?php $std = 'STD6'; include 'table/tbl_dial_gauge6.php'; ?>
     </div>
     <div class="tab-pane fade" id="tbl_7" role="tabpanel" aria-labelledby="home-tab">
         <?php $std = 'STD7'; include 'table/tbl_dial_gauge7.php'; ?>
     </div>
 </div>

<script>
    $(document).ready(function() {
	$('#toolData1').DataTable( {
			searchBuilder: true,
            dom: 'Blfrtip',
            // "responsive": false,
            buttons: [
                'excel', 'csv', 'pdf', 'print'
            ],
                    paging	: true,
                    scrollX			: false,
                    scrollCollapse	: false,
                    fixedColumns	: true,
                    lengthChange	: false,
                    responsive	: false,
                    SearchPlaceholder: "Cari ..",
                    Search: '',
                    SearchPlaceholder: "Cari Data ..",
    } );
    $('#toolData2').DataTable( {
			searchBuilder: true,
            dom: 'Blfrtip',
            // "responsive": false,
            buttons: [
                'excel', 'csv', 'pdf', 'print'
            ],
                    paging	: true,
                    scrollX			: false,
                    scrollCollapse	: false,
                    fixedColumns	: true,
                    lengthChange	: false,
                    responsive	: false,
                    SearchPlaceholder: "Cari ..",
                    Search: '',
                    SearchPlaceholder: "Cari Data ..",
    } );
    $('#toolData3').DataTable( {
			searchBuilder: true,
            dom: 'Blfrtip',
            // "responsive": false,
            buttons: [
                'excel', 'csv', 'pdf', 'print'
            ],
                    paging	: true,
                    scrollX			: false,
                    scrollCollapse	: false,
                    fixedColumns	: true,
                    lengthChange	: false,
                    responsive	: false,
                    SearchPlaceholder: "Cari ..",
                    Search: '',
                    SearchPlaceholder: "Cari Data ..",
    } );
    $('#toolData4').DataTable( {
			searchBuilder: true,
            dom: 'Blfrtip',
            // "responsive": false,
            buttons: [
                'excel', 'csv', 'pdf', 'print'
            ],
                    paging	: true,
                    scrollX			: false,
                    scrollCollapse	: false,
                    fixedColumns	: true,
                    lengthChange	: false,
                    responsive	: false,
                    SearchPlaceholder: "Cari ..",
                    Search: '',
                    SearchPlaceholder: "Cari Data ..",
    } );
    $('#toolData5').DataTable( {
			searchBuilder: true,
            dom: 'Blfrtip',
            // "responsive": false,
            buttons: [
                'excel', 'csv', 'pdf', 'print'
            ],
                    paging	: true,
                    scrollX			: false,
                    scrollCollapse	: false,
                    fixedColumns	: true,
                    lengthChange	: false,
                    responsive	: false,
                    SearchPlaceholder: "Cari ..",
                    Search: '',
                    SearchPlaceholder: "Cari Data ..",
    } );
    $('#toolData6').DataTable( {
			searchBuilder: true,
            dom: 'Blfrtip',
            // "responsive": false,
            buttons: [
                'excel', 'csv', 'pdf', 'print'
            ],
                    paging	: true,
                    scrollX			: false,
                    scrollCollapse	: false,
                    fixedColumns	: true,
                    lengthChange	: false,
                    responsive	: false,
                    SearchPlaceholder: "Cari ..",
                    Search: '',
                    SearchPlaceholder: "Cari Data ..",
    } );
    $('#toolData7').DataTable( {
			searchBuilder: true,
            dom: 'Blfrtip',
            // "responsive": false,
            buttons: [
                'excel', 'csv', 'pdf', 'print'
            ],
                    paging	: true,
                    scrollX			: false,
                    scrollCollapse	: false,
                    fixedColumns	: true,
                    lengthChange	: false,
                    responsive	: false,
                    SearchPlaceholder: "Cari ..",
                    Search: '',
                    SearchPlaceholder: "Cari Data ..",
    } );
} );
</script>