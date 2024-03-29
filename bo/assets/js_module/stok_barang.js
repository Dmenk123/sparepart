var save_method;
var table;

$(document).ready(function() {

    //force integer input in textfield
    $('input.numberinput').bind('keypress', function (e) {
        return (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57) && e.which != 46) ? false : true;
    });

	//datatables
	// table = $('#tabel_user').DataTable({
	// 	responsive: true,
    //     searchDelay: 500,
    //     processing: true,
    //     serverSide: true,
	// 	ajax: {
	// 		url  : base_url + "stok_barang/list_stok_barang",
	// 		type : "POST" 
	// 	},

	// 	//set column definition initialisation properties
	// 	columnDefs: [
	// 		{
	// 			targets: [-1], //last column
	// 			orderable: false, //set not orderable
	// 		},
	// 	],
    // });

    table = $('#tabel_user').DataTable({
        // dom: 'Bfrtip',
        responsive: true,
        processing: true,
        serverside: true,
        ajax: {
            url  : base_url + "stok_barang/list_stok_barang",
            type : "POST", 
            // data: {
            //     tanggal: tanggal,
            //     jenis: jenis,
            // },
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
    
    //change menu status
    $(document).on('click', '.btn_edit_status', function(){
        var id = $(this).attr('id');
        var status = $(this).val();
        swalConfirm.fire({
            title: 'Ubah Status Data User ?',
            text: "Apakah Anda Yakin ?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Ubah Status!',
            cancelButtonText: 'Tidak, Batalkan!',
            reverseButtons: true
          }).then((result) => {
            if (result.value) {
                $.ajax({
                    url : base_url + 'master_user/edit_status_user/'+ id,
                    type: "POST",
                    dataType: "JSON",
                    data : {status : status},
                    success: function(data)
                    {
                        swalConfirm.fire('Berhasil Ubah Status User!', data.pesan, 'success');
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
	$('#modal_stok_form').modal('show');
	$('#modal_title').text('Tambah Stok Barang'); 
}


function edit_stok(id)
{
    reset_modal_form();
    save_method = 'update';
    //Ajax Load data from ajax
    $.ajax({
        url : base_url + 'stok_barang/edit_stok',
        type: "POST",
        dataType: "JSON",
        data : {id:id},
        success: function(data)
        {
            $('[name="id_stok"]').val(data.old_data.id_stok);
            $('[name="id_barang"]').val(data.old_data.id_barang).trigger("change");;
            $('[name="id_gudang"]').val(data.old_data.id_gudang).trigger("change");;
            $('[name="sawal"]').val(data.old_data.qty);
            $('[name="smin"]').val(data.old_data.qty_min);
            $('[name="hpp"]').val(data.old_data.hpp);
           
            // $("#pegawai").val(data.old_data.id_pegawai).trigger("change");
            // if (data.foto_encoded != '') {
            //     $('#preview_img').attr('src', 'data:image/jpeg;base64,'+data.foto_encoded);
            // }
                
            $('#modal_stok_form').modal('show');
	        $('#modal_title').text('Edit Stok Barang'); 
            // console.log(data.foto_encoded);
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}

function save()
{
    var url;
    var txtAksi;

    if(save_method == 'add') {
        url = base_url + 'stok_barang/add_stok_barang';
        txtAksi = 'Tambah Stok Barang';
    }else{
        url = base_url + 'stok_barang/update_stok_barang';
        txtAksi = 'Edit Stok Barang';
    }
    
    var form = $('#form-user')[0];
    var data = new FormData(form);
    
    $("#btnSave").prop("disabled", true);
    $('#btnSave').text('Menyimpan Data'); //change button text
    swalConfirm.fire({
        title: 'Simpan Data Stok Baru ?',
        text: "Apakah Anda Yakin ?",
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
                        swal.fire("Sukses!!", "Aksi "+txtAksi+" Berhasil", "success");
                        $("#btnSave").prop("disabled", false);
                        $('#btnSave').text('Simpan');
                        
                        reset_modal_form();
                        $(".modal").modal('hide');
                        
                        reload_table();
                    }else if(data.status == false){
                        swal.fire("Gagal!!", data.pesan, "error");
                        // $("#btnSave").prop("disabled", false);
                        // $('#btnSave').text('Simpan');
                        
                        reset_modal_form();
                        $(".modal").modal('hide');
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
                    $("#btnSave").prop("disabled", false);
                    $('#btnSave').text('Simpan');

                    reset_modal_form();
                    $(".modal").modal('hide');
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

function delete_stok(id){
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
                url : base_url + 'stok_barang/delete_stok',
                type: "POST",
                dataType: "JSON",
                data : {id:id},
                success: function(data)
                {
                    swalConfirm.fire('Berhasil Hapus Stok!', data.pesan, 'success');
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
    $(".select2").val('0').trigger('change');
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
