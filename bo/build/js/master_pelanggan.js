var save_method,table;function add_menu(){reset_modal_form(),save_method="add",$("#modal_pelanggan_form").modal("show"),$("#modal_title").text("Tambah Master Pelanggan")}function edit_pelanggan(a){reset_modal_form(),save_method="update",$.ajax({url:base_url+"master_pelanggan/edit_pelanggan",type:"POST",dataType:"JSON",data:{id:a},success:function(a){$('[name="id_pelanggan"]').val(a.old_data.id_pelanggan),$('[name="nama_pembeli"]').val(a.old_data.nama_pembeli),$('[name="alamat"]').val(a.old_data.alamat),$('[name="provinsi"]').val(a.old_data.id_provinsi),$('[name="kota"]').val(a.old_data.id_kota),$('[name="kecamatan"]').val(a.old_data.kecamatan),$('[name="telp"]').val(a.old_data.no_telp),$('[name="email"]').val(a.old_data.email),$('[name="nama_toko"]').val(a.old_data.nama_toko),$("#modal_pelanggan_form").modal("show"),$("#modal_title").text("Edit Master Pleanggan")},error:function(a,e,t){alert("Error get data from ajax")}})}function reload_table(){table.ajax.reload(null,!1)}function save(){var e,t,a;a="add"==save_method?(e=base_url+"master_Pelanggan/add_data_pelanggan",t="Tambah master Pelanggan","Menambah"):(e=base_url+"master_pelanggan/update_data_pelanggan",t="Edit Master Pelanggan","Mengubah");var n=$("#form-user")[0],r=new FormData(n);$("#btnSave").prop("disabled",!0),$("#btnSave").text("Menyimpan Data"),swalConfirmDelete.fire({title:"Perhatian",text:"Apakah Anda ingin "+a+" Data ini ?",type:"warning",showCancelButton:!0,confirmButtonText:"Ya, Ubah Status!",cancelButtonText:"Tidak, Batalkan!",reverseButtons:!0}).then(a=>{a.value?$.ajax({type:"POST",enctype:"multipart/form-data",url:e,data:r,dataType:"JSON",processData:!1,contentType:!1,cache:!1,timeout:6e5,success:function(a){if(a.status)swal.fire("Sukses!!","Aksi "+t+" Berhasil","success"),$("#btnSave").prop("disabled",!1),$("#btnSave").text("Simpan"),reset_modal_form(),$(".modal").modal("hide"),reload_table();else{for(var e=0;e<a.inputerror.length;e++)"pegawai"!=a.inputerror[e]?($('[name="'+a.inputerror[e]+'"]').addClass("is-invalid"),$('[name="'+a.inputerror[e]+'"]').next().text(a.error_string[e]).addClass("invalid-feedback")):$('[name="'+a.inputerror[e]+'"]').next().next().text(a.error_string[e]).addClass("invalid-feedback-select");$("#btnSave").prop("disabled",!1),$("#btnSave").text("Simpan")}},error:function(a){console.log("ERROR : ",a),$("#btnSave").prop("disabled",!1),$("#btnSave").text("Simpan"),reset_modal_form(),$(".modal").modal("hide")}}):a.dismiss===Swal.DismissReason.cancel&&swalConfirm.fire("Dibatalkan","Aksi Dibatalakan","error")})}function delete_pelanggan(e){swalConfirmDelete.fire({title:"Hapus Data ?",text:"Data Akan dihapus permanen ?",type:"warning",showCancelButton:!0,confirmButtonText:"Ya, Hapus Data !",cancelButtonText:"Tidak, Batalkan!",reverseButtons:!0}).then(a=>{a.value?$.ajax({url:base_url+"master_pelanggan/delete_pelanggan",type:"POST",dataType:"JSON",data:{id:e},success:function(a){swalConfirm.fire("Berhasil Hapus Data Agen!",a.pesan,"success"),table.ajax.reload()},error:function(a,e,t){Swal.fire("Terjadi Kesalahan")}}):a.dismiss===Swal.DismissReason.cancel&&swalConfirm.fire("Dibatalkan","Aksi Dibatalakan","error")})}function reset_modal_form(){$("#form-user")[0].reset(),$(".append-opt").remove(),$("div.form-group").children().removeClass("is-invalid invalid-feedback"),$("span.help-block").text(""),$("#div_pass_lama").css("display","none"),$("#div_preview_foto").css("display","none"),$("#div_skip_password").css("display","none"),$("#label_foto").text("Pilih gambar yang akan diupload"),$("#username").attr("disabled",!1)}function reset_modal_form_import(){$("#form_import_excel")[0].reset(),$("#label_file_excel").text("Pilih file excel yang akan diupload")}function import_excel(){$("#modal_import_excel").modal("show"),$("#modal_import_title").text("Import data Pelanggan")}function import_data_excel(){var a=$("#form_import_excel")[0],a=new FormData(a);$("#btnSaveImport").prop("disabled",!0),$("#btnSaveImport").text("Import Data"),$.ajax({type:"POST",enctype:"multipart/form-data",url:base_url+"master_pelanggan/import_data_master",data:a,dataType:"JSON",processData:!1,contentType:!1,success:function(a){a.status?swal.fire("Sukses!!",a.pesan,"success"):swal.fire("Gagal!!",a.pesan,"error"),$("#btnSaveImport").prop("disabled",!1),$("#btnSaveImport").text("Simpan"),reset_modal_form_import(),$(".modal").modal("hide"),table.ajax.reload()},error:function(a){console.log("ERROR : ",a),$("#btnSaveImport").prop("disabled",!1),$("#btnSaveImport").text("Simpan"),reset_modal_form_import(),$(".modal").modal("hide"),table.ajax.reload()}})}function readURL(a){var e;a.files&&a.files[0]?((e=new FileReader).onload=function(a){$("#div_preview_foto").css("display","block"),$("#preview_img").attr("src",a.target.result)},e.readAsDataURL(a.files[0])):($("#div_preview_foto").css("display","none"),$("#preview_img").attr("src",""))}$(document).ready(function(){$("input.numberinput").bind("keypress",function(a){return 8==a.which||0==a.which||!(a.which<48||57<a.which)||46==a.which}),table=$("#tabel_pelanggan").DataTable({responsive:!0,searchDelay:500,processing:!0,serverSide:!0,ajax:{url:base_url+"master_pelanggan/list_pelanggan",type:"POST"},columnDefs:[{targets:[-1],orderable:!1}]}),$("#foto").change(function(){readURL(this)}),$(document).on("click",".btn_edit_status",function(){var e=$(this).attr("id"),t=$(this).val();swalConfirm.fire({title:"Ubah Status Data User ?",text:"Apakah Anda Yakin ?",icon:"warning",showCancelButton:!0,confirmButtonText:"Ya, Ubah Status!",cancelButtonText:"Tidak, Batalkan!",reverseButtons:!0}).then(a=>{a.value?$.ajax({url:base_url+"master_user/edit_status_user/"+e,type:"POST",dataType:"JSON",data:{status:t},success:function(a){swalConfirm.fire("Berhasil Ubah Status User!",a.pesan,"success"),table.ajax.reload()},error:function(a,e,t){Swal.fire("Terjadi Kesalahan")}}):a.dismiss===Swal.DismissReason.cancel&&swalConfirm.fire("Dibatalkan","Aksi Dibatalakan","error")})}),$(".modal").on("hidden.bs.modal",function(){reset_modal_form(),reset_modal_form_import()})}),$(document).ready(function(){$("#provinsi").change(function(){var a=$(this).val();$.ajax({url:base_url+"master_pelanggan/get_kota",method:"POST",data:{id:a},async:!1,dataType:"json",success:function(a){for(var e="",t=0;t<a.length;t++)e+="<option value="+a[t].id_kota+">"+a[t].nama_kota+"</option>";$(".kota").html(e)}})})});