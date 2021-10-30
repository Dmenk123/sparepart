var save_method;
var table;

$(document).ready(function() {

    //force integer input in textfield
    $('input.numberinput').bind('keypress', function (e) {
        return (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57) && e.which != 46) ? false : true;
    });

	//datatables
    $("#filters").click(function(){
        var id = $('#id_pelanggan').val();
        monitoring(id)
        if (id != '') {
            table = $('#tabel_mon_pelanggan').DataTable({
                responsive: true,
                searchDelay: 500,
                processing: true,
                serverSide: false,
                ajax: {
                    url  : base_url + "monitoring_pelanggan/datatable_monitoring",
                    type : "POST",
                    data : {id_pelanggan : id},
                },
    
                //set column definition initialisation properties
                columnDefs: [
                    {
                        targets: [-1], //last column
                        orderable: false, //set not orderable
                    },
                ],
            });
        }
       
    }); 
    

    $(".modal").on("hidden.bs.modal", function(){
        reset_modal_form();
        reset_modal_form_import();
    });
});	

function add_menu()
{
    reset_modal_form();
    save_method = 'add';
	$('#modal_log').modal('show');
	$('#modal_title').text('Tambah Log Harga Jual'); 
}



function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}


function monitoring(id)
{
    url = base_url + 'monitoring_pelanggan/monitoring_cart';
    $.ajax({
      type: "POST",
      enctype: 'multipart/form-data',
      url: url,
      data: {id_pelanggan:id},
      dataType: "JSON",
      // processData: false, // false, it prevent jQuery form transforming the data into a query string
      // contentType: false, 
      // cache: false,
      timeout: 600000,
      success: function (response) {
          if(response.status) {
              console.log('berhasil');
              new Chart(document.getElementById("line-chart"), {
                  type: 'bar',
                  data: {
                    labels: response.label,
                    datasets: response.datasets
                  },
                  options: {
                    title: {
                      display: true,
                      text: response.judul
                    }
                  }
              });
          }else {
              for (var i = 0; i < data.inputerror.length; i++) 
              {
                  if (data.inputerror[i] != 'jabatans') {
                      $('[name="'+data.inputerror[i]+'"]').addClass('is-invalid');
                      $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]).addClass('invalid-feedback'); //select span help-block class set text error string
                  }else{
                      $($('#jabatans').data('select2').$container).addClass('has-error');
                  }
              }
  
              $("#btnSave").prop("disabled", false);
              $('#btnSave').text('Simpan');
          }
      },
      error: function (e) {
          console.log("ERROR : ", e);
          $("#btnSave").prop("disabled", false);
          $('#btnSave').text('Simpan');
  
          reset_modal_form();
          $(".modal").modal('hide');
      }
    });
}