let save_method;
let table;
let id_pen;
let bulanUri;
let tahunUri;
let kategoriUri;

$(document).ready(function() {
    let uri = new URL(window.location.href);
    bulanUri = uri.searchParams.get("bulan");
    tahunUri = uri.searchParams.get("tahun");
    kategoriUri = uri.searchParams.get("kategori");
    
    //force integer input in textfield
    $('input.numberinput').bind('keypress', function (e) {
        return (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57) && e.which != 46) ? false : true;
    });

    table = $('#tabel_pengeluaran').DataTable({
        // dom: 'Bfrtip',
        responsive: true,
        processing: true,
        serverside: true,
        ajax: {
            url  : base_url + "pengeluaran_lain/list_data_pengeluaran",
            type : "POST", 
            data: {
                tahun: tahunUri,
                bulan: bulanUri,
                kategori: kategoriUri,
            },
        },
        language: {
            decimal: ",",
            thousands: "."
        },
        // columnDefs: [
        //     { targets: 8, className: 'text-right' },
        //     { targets: 9, className: 'text-right' },
        //     { visible: false, searchable: false, targets: 10 },
        //     { visible: false, searchable: false, targets: 11 },
        // ]
    });


    $('#regForm').submit(function(e){
      e.preventDefault();
      // var url = '<?php echo base_url(); ?>';
      var reg = $('#regForm').serialize();
      $.ajax({
          type: 'POST',
          data: reg,
          dataType: 'json',
          url: base_url + 'pengeluaran_lain/save_trans_detail',
          success: function(data)
          {
              swalConfirm.fire('Berhasil Menambah Data!', data.pesan, 'success');
              $('#regForm')[0].reset();
              getTable();
              getTotal();
          },
          error: function (jqXHR, textStatus, errorThrown)
          {
              Swal.fire('Terjadi Kesalahan');
          }
        
      });
    });

      // getTable();
      // getTotal();

      

  //   const load_detail_apbd_surabaya = (jenis) => {
  //     if ( $.fn.DataTable.isDataTable('#datatable_apbd_pemkot') ) {
  //         $('#datatable_apbd_pemkot').DataTable().clear().destroy();
  //     }
  
  //     $('#datatable_apbd_pemkot').DataTable({
  //         dom: 'Bfrtip',
  //         buttons: [
  //             'copy',
  //             {
  //                 extend: 'excel',
  //                 messageTop: 'Data APBD bidang '+jenis+' sampai dengan tanggal '+tanggal
  //             },
  //             {
  //                 extend: 'print',
  //                 exportOptions: {
  //                     columns: [ 0, ':visible' ] //only show visible column
  //                 },
  //                 messageTop: 'Data APBD bidang '+jenis+' sampai dengan tanggal '+tanggal
  //             },
  //         ],
  //         processing: true,
  //         serverside: true,
  //         ajax: {
  //             url: '{{route('app.datatable_apbd_sby')}}',
  //             method: 'POST',
  //             data: {
  //                 tanggal: tanggal,
  //                 jenis: jenis,
  //             },
  //         },
  //         language: {
  //             decimal: ",",
  //             thousands: "."
  //         },
  //         columnDefs: [
  //             { targets: 8, className: 'text-right' },
  //             { targets: 9, className: 'text-right' },
  //             { visible: false, searchable: false, targets: 10 },
  //             { visible: false, searchable: false, targets: 11 },
  //         ]
  //     });
  
  // }
    
    //change menu status
    // $(document).on('click', '.btn_edit_status', function(){
    //     var id = $(this).attr('id');
    //     var status = $(this).val();
    //     swalConfirm.fire({
    //         title: 'Ubah Status Data User ?',
    //         text: "Apakah Anda Yakin ?",
    //         icon: 'warning',
    //         showCancelButton: true,
    //         confirmButtonText: 'Ya, Ubah Status!',
    //         cancelButtonText: 'Tidak, Batalkan!',
    //         reverseButtons: true
    //       }).then((result) => {
    //         if (result.value) {
    //             $.ajax({
    //                 url : base_url + 'master_user/edit_status_user/'+ id,
    //                 type: "POST",
    //                 dataType: "JSON",
    //                 data : {status : status},
    //                 success: function(data)
    //                 {
    //                     swalConfirm.fire('Berhasil Ubah Status User!', data.pesan, 'success');
    //                     table.ajax.reload();
    //                 },
    //                 error: function (jqXHR, textStatus, errorThrown)
    //                 {
    //                     Swal.fire('Terjadi Kesalahan');
    //                 }
    //             });
    //         } else if (
    //           /* Read more about handling dismissals below */
    //           result.dismiss === Swal.DismissReason.cancel
    //         ) {
    //           swalConfirm.fire(
    //             'Dibatalkan',
    //             'Aksi Dibatalakan',
    //             'error'
    //           )
    //         }
    //     });
    // });

    // $(".modal").on("hidden.bs.modal", function(){
    //     reset_modal_form();
    //     reset_modal_form_import();
    // });

    // $('#id_barang').change(function(){
    //   let res = $(this).val();
    //   let id_gudang = $('#id_gudang').val();
      
    //   if(res == 0) {
        
    //     $('#qty').val('0').attr({
    //       "max" : 0,
    //       "min" : 0
    //     });

    //   }else{
    //     $.ajax({
    //       type: "get",
    //       url: base_url+"penjualan/select_qty_barang",
    //       data: {id_barang:res, id_gudang:id_gudang},
    //       dataType: "json",
    //       success: function (response) {
    //         $('#qty').val(response).attr({
    //           "max" : response,
    //           "min" : 0
    //        });
    //       }
    //     });
    //   }
    // });
});	

const getSelectBarang = (obj) => {
  let id_gudang = obj.value;
  if(id_gudang == 0) {
    $('#id_barang').empty().append('<option value="0">-PILIH-</option>');
  }else{
    $.ajax({
      type: "get",
      url: base_url+"penjualan/get_option_barang",
      data: {id_gudang:id_gudang},
      dataType: "json",
      success: function (response) {
        $('#id_barang').html(response.html);
      }
    });
  }
}

function add_menu()
{
  reset_modal_form();
  save_method = 'add';
	$('#modal_agen_form').modal('show');
	$('#modal_title').text('Tambah Master Agen'); 
}

const detail_penjualan = (kode, id) => {  
  // reset_modal_form();
  $.ajax({
    type: 'GET',
    data: {kode:kode, id:id},
    dataType: 'json',
    url: base_url + 'penjualan/get_detail_penjualan',
    success: function(data)
    {
        let header = data.header;
        $('#span_faktur').text(header.no_faktur);
        $('#span_tgl_jual').text(moment(header.created_at).format('LL'));
        $('#span_pelanggan').text(header.nama_toko);
        $('#span_sales').text(header.nama_sales);
        // $('#span_petugas_det').text(header.nama_user);
        $('#tbl_konten_detail tbody').html(data.html_det);
        $('#modal_det_jual').modal('show');
        $('#modal_title').text('Detail Penjualan'); 
    },
    error: function (jqXHR, textStatus, errorThrown)
    {
        Swal.fire('Terjadi Kesalahan ');
    }
    
  });
	
}

const delete_penjualan = (kode, id) => {
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
              url : base_url + 'penjualan/delete_penjualan',
              type: "POST",
              dataType: "JSON",
              data : {id:id, kode:kode},
              success: function(data)
              {
                  swalConfirm.fire('Berhasil Hapus Data!', data.pesan, 'success');
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

function edit_pengeluaran(kode, id)
{
  window.location.href = base_url +'pengeluaran_lain/add_pengeluaran_det?index='+id+'&kode='+kode+'&mode=edit';
}


function editorder(no_faktur)
{
  window.location.href = base_url +'penjualan/add_order?no_faktur='+no_faktur+'&mode=edit';
}

function editinvoice(no_faktur)
{
  window.location.href = base_url +'penjualan/new_invoice?no_faktur='+no_faktur+'&mode=edit';
}

function cetak_invoice(no_faktur)
{
  window.location.href = base_url +'penjualan/cetak_invoice?no_faktur='+no_faktur;
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

function saveNewPengeluaran()
{
    var url;
    var txtAksi;
    const loadingCircle = $("#loading-circle");
 
    url = base_url + 'pengeluaran_lain/add_new_pengeluaran';
    txtAksi = 'Tambah Invoice';
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
                        toastr.success("Sukses Menambah Penjualan", "Sukses");
                        $("#btnSave").prop("disabled", false);
                        $('#btnSave').text('Simpan');
                        setTimeout(function(){
                          ajax_send(data.kode);
                        }, 1000);
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
                    // console.log("ERROR : ", e);
                    toastr.warning(e, "Peringatan");
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

function ajax_send(kode)
{
  window.location.href = base_url+'pengeluaran_lain/add_pengeluaran_det?kode='+kode;
}

function editDataPenjualan()
{
    var url;
    var txtAksi;
    const loadingCircle = $("#loading-circle");
 
    url = base_url + 'penjualan/update_new_invoice';
    txtAksi = 'Edit Invoice';
    var alert = "Mengupdate";
    
    
    var form = $('#form-user')[0];
    var data = new FormData(form);
    
    $("#btnSave").prop("disabled", true);
    $('#btnSave').text('Menyimpan Data'); //change button text
    swalConfirmDelete.fire({
        title: 'Perhatian',
        text: "Apakah Anda ingin "+alert+" Data ini ?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya !',
        cancelButtonText: 'Tidak !',
        reverseButtons: true
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
                        toastr.success(data.pesan, "Sukses");
                        $("#btnSave").prop("disabled", false);
                        $('#btnSave').text('Simpan');
                        window.location.href = base_url+'penjualan';
                        
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
                    toastr.error("ERROR : " + e, "Gagal");
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

function reset_modal_form_import()
{
    $('#form_import_excel')[0].reset();
    $('#label_file_excel').text('Pilih file excel yang akan diupload');
}

function import_excel(){
  $('#modal_import_excel').modal('show');
  $('#modal_import_title').text('Import data user'); 
}

function import_data_excel(){
    var form = $('#form_import_excel')[0];
    var data = new FormData(form);
    
    $("#btnSaveImport").prop("disabled", true);
    $('#btnSaveImport').text('Import Data');
    $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: base_url + 'master_user/import_data_master',
        data: data,
        dataType: "JSON",
        processData: false, // false, it prevent jQuery form transforming the data into a query string
        contentType: false, 
        success: function (data) {
            if(data.status) {
                swal.fire("Sukses!!", data.pesan, "success");
                $("#btnSaveImport").prop("disabled", false);
                $('#btnSaveImport').text('Simpan');
            }else {
                swal.fire("Gagal!!", data.pesan, "error");
                $("#btnSaveImport").prop("disabled", false);
                $('#btnSaveImport').text('Simpan');
            }

            reset_modal_form_import();
            $(".modal").modal('hide');
            table.ajax.reload();
        },
        error: function (e) {
            console.log("ERROR : ", e);
            $("#btnSaveImport").prop("disabled", false);
            $('#btnSaveImport').text('Simpan');

            reset_modal_form_import();
            $(".modal").modal('hide');
            table.ajax.reload();
        }
    });
}


 
function getTable(){
    var id = $('#id_penjualan').val();
    console.log(id);
    $.ajax({
        type: 'POST',
        url: base_url + 'penjualan/fetch',
        data: {id:id},
        success:function(response){
            $('#tbody').html(response);
        }
    });
}

function getTotal(){
  var id = $('#id_penjualan').val();
  console.log(id);
  $.ajax({
      type: 'POST',
      url: base_url + 'penjualan/total_order',
      data: {id:id},
      dataType: "json",
      success:function(data){
        $('#total').html(data.total);
      }
  });
}

function hapus_order(id)
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
            url : base_url + 'penjualan/hapus_order',
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
    url : base_url + 'penjualan/change_qty',
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
  