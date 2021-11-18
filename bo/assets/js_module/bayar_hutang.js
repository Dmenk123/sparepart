var save_method;
var table;
var id_pen;

$(document).ready(function() {
    let uri = new URL(window.location.href);
    bulanUri = uri.searchParams.get("bulan");
    tahunUri = uri.searchParams.get("tahun");
    kategoriUri = uri.searchParams.get("kategori");

    let arrSegment = window.location.pathname.split('/');
    if(arrSegment[4] == 'add_penerimaan') {
      getTable();
      getTotal();
    }

    //force integer input in textfield
    $('input.numberinput').bind('keypress', function (e) {
        return (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57) && e.which != 46) ? false : true;
    });

	  //datatables
	  table = $('#tabel_penerimaan').DataTable({
		  responsive: true,
      searchDelay: 500,
      processing: true,
      serverSide: true,
      ajax: {
        url  : base_url + "bayar_hutang/list_bayar_hutang",
        type : "POST",
        data: {
          tahun: tahunUri,
          bulan: bulanUri,
          kategori: kategoriUri,
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
    
    $('#id_barang').on('select2:select', function (e) {
        var data = e.params.data;
        // console.log(data);
        $.ajax({
          type: "get",
          url: base_url+ "pembelian/get_harga_barang",
          data: {id_barang:data.id},
          dataType: "json",
          success: function (response) {
            $('#hsat').val(response.hpp);
          }
        });
    });

    $('#kode_pembelian').on('select2:select', function (e) {
        var data = e.params.data;
        let uang = String(parseFloat(data.title));
      
        $('#hutang_txt').val(formatRupiah(uang));
        $('#hutang').val(data.title);
    });
   
    $(".modal").on("hidden.bs.modal", function(){
        // reset_modal_form();
        // reset_modal_form_import();
    });

    $('#regForm').submit(function(e){
        e.preventDefault();
        $("#btnSaveAdd").prop("disabled", true);
        $('#btnSaveAdd').text('Menyimpan Data ....');

        var form = $('#regForm')[0];
        var reg = new FormData(form);
        let alert = "Menerima";

        swalConfirm.fire({
          title: 'Perhatian',
          text: "Apakah Anda ingin "+alert+" Data ini ?",
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Ya !',
          cancelButtonText: 'Tidak !',
          reverseButtons: false
        }).then((result) => {
          if (result.value) {
            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: base_url + 'barang_masuk/simpan_penerimaan_barang',
                data: reg,
                dataType: "JSON",
                processData: false, // false, it prevent jQuery form transforming the data into a query string
                contentType: false, 
                cache: false,
                timeout: 600000,
                success: function (data) {
                    if(data.status) {
                      swalConfirm.fire('Berhasil Menambah Data!', data.pesan, 'success');
                      $('#regForm')[0].reset();
                      window.location.href = base_url +'barang_masuk';
                    }else {
                      for (var i = 0; i < data.inputerror.length; i++) 
                      {
                          if (data.inputerror[i] != 'pegawai') {
                              $('[name="'+data.inputerror[i]+'"]').addClass('is-invalid');
                              $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]).addClass('invalid-feedback'); //select span help-block class set text error string
                          }else{
                              //ikut style global
                              $('[name="'+data.inputerror[i]+'"]').next().next().text(data.error_string[i]).addClass('invalid-feedback-select');
                          }
                      }

                      $("#btnSaveAdd").prop("disabled", false);
                      $('#btnSaveAdd').text('Simpan');
                    }
                },
                error: function (e) {
                    console.log("ERROR : ", e);
                    createAlert('Opps!','Terjadi Kesalahan','Coba Lagi nanti','danger',true,false,'pageMessages');
                    $("#btnSaveAdd").prop("disabled", false);
                    $('#btnSaveAdd').text('Simpan');
                }
            });
          }else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
          ) {
            swalConfirm.fire(
              'Dibatalkan',
              'Aksi Dibatalakan',
              'error'
            );

            $("#btnSaveAdd").prop("disabled", false);
            $('#btnSaveAdd').text('Simpan');
          }
        });

        
    });
});	

const detail_penerimaan = (kode, id) => {  
  // reset_modal_form();
  $.ajax({
    type: 'GET',
    data: {kode:kode, id:id},
    dataType: 'json',
    url: base_url + 'barang_masuk/get_detail_penerimaan',
    success: function(data)
    {
        let header = data.header;
        $('#span_kode_beli_det').text(header.kode_pembelian);
        $('#span_tgl_beli_det').text(moment(header.tanggal_beli).format('LL'));
        $('#span_agen_det').text(header.nama_perusahaan);
        $('#span_kode_masuk_det').text(header.kode_penerimaan);
        $('#span_petugas_det').text(header.nama_user);
        $('#tbl_konten_detail tbody').html(data.html_det);
        $('#modal_det_masuk').modal('show');
        $('#modal_title').text('Detail Barang Masuk'); 
    },
    error: function (jqXHR, textStatus, errorThrown)
    {
        Swal.fire('Terjadi Kesalahan ');
    }
    
  });
	
}

function edit_penerimaan(kode)
{
  window.location.href = base_url +'barang_masuk/add_penerimaan?reff='+kode+'&update=true';
}

function editorder(order_id)
{
  window.location.href = base_url +'penjualan/add_order?order_id='+order_id+'&mode=edit';
}

function editinvoice(order_id)
{
  window.location.href = base_url +'penjualan/new_invoice?order_id='+order_id+'&mode=edit';
}

function cetak_invoice(order_id)
{
  window.location.href = base_url +'penjualan/cetak_invoice?order_id='+order_id;
}

function simpanedit()
{
  swalConfirmDelete.fire({
    title: 'Perhatian',
    text: "Apakah Anda ingin Menyimpan Invoice ini ?",
    type: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Ya !',
    cancelButtonText: 'Tidak !',
    reverseButtons: true
  }).then((result) => {
      if (result.value) {
        window.location.href = base_url +'penjualan';
      }else if (
          /* Read more about handling dismissals below */
          result.dismiss === Swal.DismissReason.cancel
        ) {
          swalConfirm.fire(
            'Dibatalkan',
            'Aksi Dibatalakan',
            'error'
          )
        }
  });
  
}

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}

function saveNew()
{
    var url;
    var txtAksi;
    const loadingCircle = $("#loading-circle");
 
    url = base_url + 'bayar_hutang/save_new_transaksi';
    txtAksi = 'Tambah Penerimaan';
    var alert = "Menambah";
    
    var form = $('#form-user')[0];
    var data = new FormData(form);
    
    $("#btnSave").prop("disabled", true);
    $('#btnSave').text('Menyimpan Data'); //change button text
    swalConfirm.fire({
        title: 'Perhatian',
        text: "Apakah Anda ingin "+alert+" Data ini ?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya !',
        cancelButtonText: 'Tidak !',
        reverseButtons: false
      }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: url,
                data: data,
                dataType: "JSON",
                processData: false, // false, it prevent jQuery form transforming the data into a query string
                contentType: false, 
                cache: false,
                timeout: 600000,
                success: function (data) {
                    if(data.status) {
                        createAlert('','Berhasil!',''+data.pesan+'','success',true,true,'pageMessages');
                        // swal.fire("Sukses!!", "Aksi "+txtAksi+" Berhasil", "success");
                        $("#btnSave").prop("disabled", false);
                        $('#btnSave').text('Simpan');
                        loadingCircle.css("display", "block");
                        setTimeout(function(){
                          ajax_send(data.kode);
                          loadingCircle.css("display", "none");
                        }, 2000);
                    }else {
                        for (var i = 0; i < data.inputerror.length; i++) 
                        {
                            if (data.inputerror[i] != 'pegawai') {
                                $('[name="'+data.inputerror[i]+'"]').addClass('is-invalid');
                                $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]).addClass('invalid-feedback'); //select span help-block class set text error string
                            }else{
                                //ikut style global
                                $('[name="'+data.inputerror[i]+'"]').next().next().text(data.error_string[i]).addClass('invalid-feedback-select');
                            }
                        }

                        $("#btnSave").prop("disabled", false);
                        $('#btnSave').text('Simpan');
                    }
                },
                error: function (e) {
                    console.log("ERROR : ", e);
                    createAlert('Opps!','Terjadi Kesalahan','Coba Lagi nanti','danger',true,false,'pageMessages');
                    $("#btnSave").prop("disabled", false);
                    $('#btnSave').text('Simpan');

                    // reset_modal_form();
                    // $(".modal").modal('hide');
                }
            });
        }else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
          ) {
            swalConfirm.fire(
              'Dibatalkan',
              'Aksi Dibatalakan',
              'error'
            )
          }
    });
}

function delete_penerimaan(kode, id){
  swalConfirmDelete.fire({
      title: 'Hapus Data ?',
      text: "Data Akan dihapus permanen ?",
      type: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Ya, Hapus Data !',
      cancelButtonText: 'Tidak, Batalkan!',
      reverseButtons: true
    }).then((result) => {
      if (result.value) {
          $.ajax({
              url : base_url + 'barang_masuk/delete_penerimaan',
              type: "POST",
              dataType: "JSON",
              data : {
                kode:kode, 
                id:id
              },
              success: function(data)
              {
                  swalConfirm.fire('Berhasil Hapus Data !', data.pesan, 'success');
                  table.ajax.reload();
              },
              error: function (jqXHR, textStatus, errorThrown)
              {
                  Swal.fire('Terjadi Kesalahan');
              }
          });
      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
        swalConfirm.fire(
          'Dibatalkan',
          'Aksi Dibatalakan',
          'error'
        )
      }
  });
}

/* Fungsi formatRupiah */
function formatRupiah(angka, prefix) {
  var number_string = angka.replace(/[^,\d]/g, "").toString(),
    split = number_string.split(","),
    sisa = split[0].length % 3,
    rupiah = split[0].substr(0, sisa),
    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

  // tambahkan titik jika yang di input sudah menjadi angka ribuan
  if (ribuan) {
    separator = sisa ? "." : "";
    rupiah += separator + ribuan.join(".");
  }

  rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
  return prefix == undefined ? rupiah : rupiah ? prefix+". " + rupiah : "";
}


// function reset_modal_form()
// {
//     $('#form-user')[0].reset();
//     $('.append-opt').remove(); 
//     $('div.form-group').children().removeClass("is-invalid invalid-feedback");
//     $('span.help-block').text('');
//     $('#div_pass_lama').css("display","none");
//     $('#div_preview_foto').css("display","none");
//     $('#div_skip_password').css("display", "none");
//     $('#label_foto').text('Pilih gambar yang akan diupload');
//     $('#username').attr('disabled', false);
// }

// function reset_modal_form_import()
// {
//     $('#form_import_excel')[0].reset();
//     $('#label_file_excel').text('Pilih file excel yang akan diupload');
// }

// function import_excel(){
//   $('#modal_import_excel').modal('show');
// 	$('#modal_import_title').text('Import data user'); 
// }

// function import_data_excel(){
//     var form = $('#form_import_excel')[0];
//     var data = new FormData(form);
    
//     $("#btnSaveImport").prop("disabled", true);
//     $('#btnSaveImport').text('Import Data');
//     $.ajax({
//         type: "POST",
//         enctype: 'multipart/form-data',
//         url: base_url + 'master_user/import_data_master',
//         data: data,
//         dataType: "JSON",
//         processData: false, // false, it prevent jQuery form transforming the data into a query string
//         contentType: false, 
//         success: function (data) {
//             if(data.status) {
//                 swal.fire("Sukses!!", data.pesan, "success");
//                 $("#btnSaveImport").prop("disabled", false);
//                 $('#btnSaveImport').text('Simpan');
//             }else {
//                 swal.fire("Gagal!!", data.pesan, "error");
//                 $("#btnSaveImport").prop("disabled", false);
//                 $('#btnSaveImport').text('Simpan');
//             }

//             reset_modal_form_import();
//             $(".modal").modal('hide');
//             table.ajax.reload();
//         },
//         error: function (e) {
//             console.log("ERROR : ", e);
//             $("#btnSaveImport").prop("disabled", false);
//             $('#btnSaveImport').text('Simpan');

//             reset_modal_form_import();
//             $(".modal").modal('hide');
//             table.ajax.reload();
//         }
//     });
// }

function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        $('#div_preview_foto').css("display","block");
        $('#preview_img').attr('src', e.target.result);
      }
      reader.readAsDataURL(input.files[0]);
    } else {
        $('#div_preview_foto').css("display","none");
        $('#preview_img').attr('src', '');
    }
}

function createAlert(title, summary, details, severity, dismissible, autoDismiss, appendToId) {
    var iconMap = {
      info: "fa fa-info-circle",
      success: "fa fa-thumbs-up",
      warning: "fa fa-exclamation-triangle",
      danger: "fa ffa fa-exclamation-circle"
    };
  
    var iconAdded = false;
  
    var alertClasses = ["alert", "animated", "flipInX"];
    alertClasses.push("alert-" + severity.toLowerCase());
  
    if (dismissible) {
      alertClasses.push("alert-dismissible");
    }
  
    var msgIcon = $("<i />", {
      "class": iconMap[severity] // you need to quote "class" since it's a reserved keyword
    });
  
    var msg = $("<div />", {
      "class": alertClasses.join(" ") // you need to quote "class" since it's a reserved keyword
    });
  
    if (title) {
      var msgTitle = $("<h4 />", {
        html: title
      }).appendTo(msg);
      
      if(!iconAdded){
        msgTitle.prepend(msgIcon);
        iconAdded = true;
      }
    }
  
    if (summary) {
      var msgSummary = $("<strong />", {
        html: summary
      }).appendTo(msg);
      
      if(!iconAdded){
        msgSummary.prepend(msgIcon);
        iconAdded = true;
      }
    }
  
    if (details) {
      var msgDetails = $("<p />", {
        html: details
      }).appendTo(msg);
      
      if(!iconAdded){
        msgDetails.prepend(msgIcon);
        iconAdded = true;
      }
    }
    
  
    if (dismissible) {
      var msgClose = $("<span />", {
        "class": "close", // you need to quote "class" since it's a reserved keyword
        "data-dismiss": "alert",
        html: "<i class='fa fa-times-circle'></i>"
      }).appendTo(msg);
    }
    
    $('#' + appendToId).prepend(msg);
    
    if(autoDismiss){
      setTimeout(function(){
        msg.addClass("flipOutX");
        setTimeout(function(){
          msg.remove();
        },1000);
      }, 5000);
    }
}

/////////////////////////////////////////////
function ajax_send(kode) {
  window.location.href = base_url+'barang_masuk/add_penerimaan?reff='+kode;
}

function getTable(){
  var id = $('#id_pembelian').val();
  var idMasuk = $('#id_penerimaan').val();
  console.log(id);
  $.ajax({
      type: 'POST',
      url: base_url + 'barang_masuk/fetch',
      data: {id:id, idMasuk:idMasuk},
      success:function(response){
          $('#tbody').html(response);
          getTotal();
      }
  });
}

function getTotal(){
  let result = 0;
  
  $('#tbody tr td input.kelas_htotal').each(function(){
    result += parseFloat(this.value);
  });
  
  $('td#total').html('Rp '+numberWithCommas(result));
}

function numberWithCommas(x) {
  return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function tes(id)
{
  var tes = '#qty_order_'+id;
  var row_harga = '#harga_total_'+id;
  var row_harga_raw = '#harga_total_raw_'+id;
  var qty = $(tes).val();
  var current_url = window.location.href;
  var url = new URL(current_url);
  var kodereff = url.searchParams.get("reff");

  $.ajax({
    url : base_url + 'barang_masuk/change_qty',
    type: "POST",
    dataType: "JSON",
    data : {id : id, qty : qty, kodereff:kodereff},
    success: function(data)
    {
      $(tes).val(data.qty);
      $(row_harga).text(data.harga_total);
      $(row_harga_raw).val(data.harga_raw);
      getTotal();
    },
    error: function (jqXHR, textStatus, errorThrown)
    {
        Swal.fire('Terjadi Kesalahan');
    }
  });
}

function hapus_trans_detail(elem)
{
  swalConfirmDelete.fire({
    title: 'Hapus Data ?',
    text: "Data Akan dihapus permanen ?",
    type: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Ya, Hapus Data !',
    cancelButtonText: 'Tidak, Batalkan!',
    reverseButtons: true
  }).then((result) => {
    if (result.value) {
        $(elem).closest('tr').remove();
        getTotal();
    } else if (
      /* Read more about handling dismissals below */
      result.dismiss === Swal.DismissReason.cancel
    ) {
      swalConfirm.fire(
        'Dibatalkan',
        'Aksi Dibatalakan',
        'error'
      )
    }
  });
}
  