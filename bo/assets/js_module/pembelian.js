var save_method;
var table;
var id_pen;

$(document).ready(function() {

  let uri = new URL(window.location.href);
  bulanUri = uri.searchParams.get("bulan");
  tahunUri = uri.searchParams.get("tahun");
  kategoriUri = uri.searchParams.get("kategori");

  let arrSegment = window.location.pathname.split('/');
  if(arrSegment[4] == 'add_pembelian') {
    getTable();
    getTotal();
  }
 
  $('#regForm').submit(function(e){
    e.preventDefault();
    // var url = '<?php echo base_url(); ?>';
    var reg = $('#regForm').serialize();
    $.ajax({
        type: 'POST',
        data: reg,
        dataType: 'json',
        url: base_url + 'pembelian/save_pembelian',
        success: function(data)
        {
            swalConfirm.fire('Berhasil Menambah Data!', data.pesan, 'success');
            $('#regForm')[0].reset();
            $("#id_barang").val(null).trigger('change');
            getTable();
            getTotal();
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            Swal.fire('Terjadi Kesalahan ');
        }
        
    });
  });

  //force integer input in textfield
  $('input.numberinput').bind('keypress', function (e) {
      return (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57) && e.which != 46) ? false : true;
  });

	//datatables
	table = $('#tabel_pembelian').DataTable({
    responsive: true,
    searchDelay: 500,
    processing: true,
    serverSide: true,
    ajax: {
      url  : base_url + "pembelian/list_pembelian",
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

    createdRow: function( row, data, dataIndex){
      if(data[6] ==  'Lunas'){
          $(row).addClass('highlight_row_success');
      }
    }
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
          // $('#hsat').val(response.hpp);
          $('#hsat').mask('000.000.000.000', {reverse: true}).val(response.hpp).trigger('input');
        }
      });
  });
   
  $(".modal").on("hidden.bs.modal", function(){
      reset_modal_form();
      reset_modal_form_import();
  });
  
});	

const gunakan_potongan_nota = (id) => {
   swalConfirm.fire({
      title: 'Gunakan Potongan ?',
      text: "Total Transaksi akan dikurangi nilai retur ?",
      type: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Ya !',
      cancelButtonText: 'Tidak !',
      reverseButtons: false
  }).then((result) => {
      if (result.value) {
          $.ajax({
              url : base_url + 'pembelian/pakai_potongan_nota',
              type: "POST",
              dataType: "JSON",
              data : {id:id},
              success: function(data)
              {
                  if(data.status) {
                    swalConfirm.fire('Berhasil Pakai Potongan !', data.pesan, 'success');
                    // getTable();
                    // getTotal();
                    location.reload();
                  }else{
                    swalConfirm.fire('Gagal Pakai Potongan !', data.pesan, 'error');
                  }
                 
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

const hapus_potongan_nota = (id) => {
   swalConfirm.fire({
      title: 'Hapus Potongan ?',
      text: "Yakin Hapus Potongan Pembelian ?",
      type: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Ya !',
      cancelButtonText: 'Tidak !',
      reverseButtons: false
  }).then((result) => {
      if (result.value) {
          $.ajax({
              url : base_url + 'pembelian/hapus_potongan_nota',
              type: "POST",
              dataType: "JSON",
              data : {id:id},
              success: function(data)
              {
                if(data.status) {
                  swalConfirm.fire('Berhasil Hapus Potongan !', data.pesan, 'success');
                  // getTable();
                  // getTotal();
                  location.reload();
                }else{
                  swalConfirm.fire('Gagal Hapus Potongan !', data.pesan, 'error');
                }
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

function add_menu()
{
  reset_modal_form();
  save_method = 'add';
	$('#modal_agen_form').modal('show');
	$('#modal_title').text('Tambah Master Agen'); 
}

function edit_pembelian(kode)
{
  window.location.href = base_url +'pembelian/add_pembelian?kode_pembelian='+kode+'&update=true';
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
    text: "Apakah Anda ingin Menyimpan Pembelian ini ?",
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

function saveNewpembelian()
{
    var url;
    var txtAksi;
    const loadingCircle = $("#loading-circle");
 
    url = base_url + 'pembelian/save_new_pembelian';
    txtAksi = 'Tambah Pembelian';
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

function delete_pembelian(id){
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
              url : base_url + 'pembelian/delete_pembelian',
              type: "POST",
              dataType: "JSON",
              data : {id:id},
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


function reset_modal_form()
{
    $('#form-user')[0].reset();
    $('.append-opt').remove(); 
    $('div.form-group').children().removeClass("is-invalid invalid-feedback");
    $('span.help-block').text('');
    $('#div_pass_lama').css("display","none");
    $('#div_preview_foto').css("display","none");
    $('#div_skip_password').css("display", "none");
    $('#label_foto').text('Pilih gambar yang akan diupload');
    $('#username').attr('disabled', false);
}

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

function ajax_send(kode)
{
    window.location.href = base_url+'pembelian/add_pembelian?kode_pembelian='+kode;
}
 
function getTable(){
    var id = $('#id_pembelian').val();
    console.log(id);
    $.ajax({
        type: 'POST',
        url: base_url + 'pembelian/fetch',
        data: {id:id},
        success:function(response){
            $('#tbody').html(response);
        }
    });
}

function getTotal(){
  var id = $('#id_pembelian').val();
  console.log(id);
  $.ajax({
      type: 'POST',
      url: base_url + 'pembelian/total_pembelian',
      data: {id:id},
      dataType: "json",
      success:function(data){
        $('#total').html(data.total);
      }
  });
}

function hapus_trans_detail(id)
{
  // alert('kesini'); exit;
  swalConfirm.fire({
    title: 'Apakah Anda Yakin ?',
    text: "ingin menghapus daftar order ini ?",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Ya !',
    cancelButtonText: 'Tidak !',
    reverseButtons: true
  }).then((result) => {
    if (result.value) {
        $.ajax({
            url : base_url + 'pembelian/hapus_trans_detail',
            type: "POST",
            dataType: "JSON",
            data : {id : id},
            success: function(data)
            {
                swalConfirm.fire('Berhasil !', data.pesan, 'success');
                getTable();
                getTotal();
                
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

function tes(id)
{
  var tes = '#qty_order_'+id;
  var qty = $(tes).val();
  $.ajax({
    url : base_url + 'pembelian/change_qty',
    type: "POST",
    dataType: "JSON",
    data : {id : id, qty : qty},
    success: function(data)
    {
        // swalConfirm.fire('Berhasil !', data.pesan, 'success');
        getTable();
        getTotal();
        
    },
    error: function (jqXHR, textStatus, errorThrown)
    {
        Swal.fire('Terjadi Kesalahan');
    }
});
  
}

const detail_pembelian = (kode, id) => {  
  // reset_modal_form();
  $.ajax({
    type: 'GET',
    data: {kode:kode, id:id},
    dataType: 'json',
    url: base_url + 'pembelian/get_detail_pembelian',
    success: function(data)
    {
        let header = data.header;
        $('#span_kode_beli_det').text(header.kode_pembelian);
        $('#span_tgl_beli_det').text(moment(header.tanggal).format('LL'));
        $('#span_agen_det').text(header.nama_perusahaan);
        $('#span_petugas_det').text(header.nama_user);
        $('#tbl_konten_detail tbody').html(data.html_det);
        $('#modal_det_beli').modal('show');
        $('#modal_title').text('Detail Pembelian'); 
    },
    error: function (jqXHR, textStatus, errorThrown)
    {
        Swal.fire('Terjadi Kesalahan ');
    }
    
  });
	
}  