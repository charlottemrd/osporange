
    $(document).ready( function () {
    var table = $('#table').DataTable( {
    "paging": true,
    "language": {
    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json"
},

    pagingType: "simple_numbers",
    "bLengthChange": false,
    "bInfo" : false,
    rowReorder: true,
    "dom": 'lrtip',
    "aoColumnDefs": [
{ 'bSortable': false, 'aTargets': [ -1 ] }
    ],

});


    $('#mySearchButton').on( 'keyup click', function () {
    table.search($('#mySearchText').val()).draw();
} );


} );