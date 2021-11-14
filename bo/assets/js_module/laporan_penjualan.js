var save_method;
var table;

$(document).ready(function() {
    
    //force integer input in textfield
    $('input.numberinput').bind('keypress', function (e) {
        return (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57) && e.which != 46) ? false : true;
    });

    var model = $('#model').val();
    var tanggal_awal = $('#tanggal_awal').val();
    var tanggal_akhir = $('#tanggal_akhir').val();
    var bulan = $('#bulan').val();
    var tahun = $('#tahun').val();
    var tahun2 = $('#tahun2').val();

    if(model == '1'){
        $(".div_tanggal_mulai").hide();
        $(".div_tanggal_akhir").hide();
        $(".div_bulan").show();
        $(".div_tahun").hide();
      }
      else if(model == '3') {
        $(".div_tanggal_mulai").show();
        $(".div_tanggal_akhir").show();
        $(".div_bulan").hide();
        $(".div_tahun").hide();
      }
      else if (model == '2') {
        $(".div_tahun").show();
        $(".div_tanggal_mulai").hide();
        $(".div_tanggal_akhir").hide();
        $(".div_bulan").hide();
      }
      else {
        $(".div_tanggal_mulai").hide();
        $(".div_tanggal_akhir").hide();
        $(".div_bulan").hide();
        $(".div_tahun").hide();
      }
	//datatables
    if (model) {
        table = $('#tabel_lap_penjualan').DataTable({
            responsive: true,
            searchDelay: 500,
            processing: true,
            serverSide: false,
            bDestroy: true,
            ajax: {
                url  : base_url + "laporan_penjualan/datatable",
                type : "POST",
                data : {
                    model : model, 
                    start: tanggal_awal,
                    end : tanggal_akhir,
                    bulan : bulan,
                    tahun : tahun,
                    tahun2 : tahun2
                },
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

function changeModel() {
    if($("#model").val() == '1'){
      $(".div_tanggal_mulai").hide();
      $(".div_tanggal_akhir").hide();
      $(".div_bulan").show();
      $(".div_tahun").hide();
    }
    else if($("#model").val() == '3') {
      $(".div_tanggal_mulai").show();
      $(".div_tanggal_akhir").show();
      $(".div_bulan").hide();
      $(".div_tahun").hide();
    }
    else if ($("#model").val() == '2') {
      $(".div_tahun").show();
      $(".div_tanggal_mulai").hide();
      $(".div_tanggal_akhir").hide();
      $(".div_bulan").hide();
    }
    else {
      $(".div_tanggal_mulai").hide();
      $(".div_tanggal_akhir").hide();
      $(".div_bulan").hide();
      $(".div_tahun").hide();
    }
  }

  function save() {
    var eksekusi = false;
    var pesan = '';

      if($("#model").val() == '1'){
        if($("#bulan").val() != '') {
          if($("#tahun").val() != '') {
            eksekusi = true;
          }
          else {
            pesan = "Silahkan memilih Tahun terlebih dahulu";
          }
        }
        else {
          pesan = "Silahkan memilih Bulan terlebih dahulu";
        }
      }
      else if($("#model").val() == '3') {
        if($("#tanggal_mulai").val() != '' && $("#tanggal_akhir").val() != '') {
          eksekusi = true;
        }
        else {
          pesan = "Silahkan memilih Tanggal Awal dan AKhir terlebih dahulu";
        }
      }
      else if ($("#model").val() == '2') {
        if ($("#tahun2").val() != '') {
          eksekusi = true;
        }
        else{
          pesan = "Silahkan memilih Tahun terlebih dahulu";
        }
      }
    

    if(eksekusi) {
        $('#submit_form').submit();
    }
    else {
        Swal.fire(pesan)
        exit
    }
  }

