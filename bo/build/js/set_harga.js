var save_method,table;function add_menu(){reset_modal_form(),save_method="add",$("#modal_harga_form").modal("show"),$("#modal_title").text("Set Data Harga")}function reload_table(){table.ajax.reload(null,!1)}function save(){var a,t="add"==save_method?(a=base_url+"set_harga/add_data","Set harga"):(a=base_url+"set_harga/update_data","Update Harga"),e=$("#form-harga")[0],e=new FormData(e);$("#btnSave").prop("disabled",!0),$("#btnSave").text("Menyimpan Data"),$.ajax({type:"POST",enctype:"multipart/form-data",url:a,data:e,dataType:"JSON",processData:!1,contentType:!1,cache:!1,timeout:6e5,success:function(a){if(a.status)swal.fire("Sukses!!","Aksi "+t+" Berhasil","success"),$("#btnSave").prop("disabled",!1),$("#btnSave").text("Simpan"),reset_modal_form(),$(".modal").modal("hide"),reload_table();else{for(var e=0;e<a.inputerror.length;e++)"pegawai"!=a.inputerror[e]?($('[name="'+a.inputerror[e]+'"]').addClass("is-invalid"),$('[name="'+a.inputerror[e]+'"]').next().text(a.error_string[e]).addClass("invalid-feedback")):$('[name="'+a.inputerror[e]+'"]').next().next().text(a.error_string[e]).addClass("invalid-feedback-select");$("#btnSave").prop("disabled",!1),$("#btnSave").text("Simpan")}},error:function(a){console.log("ERROR : ",a),$("#btnSave").prop("disabled",!1),$("#btnSave").text("Simpan"),reset_modal_form(),$(".modal").modal("hide")}})}function stop_diskon(e){swalConfirmDelete.fire({title:"Stop Diskon ?",text:"Data Diskon Akan Di Stop ?",type:"warning",showCancelButton:!0,confirmButtonText:"Ya, Stop Diskon !",cancelButtonText:"Tidak, Batalkan!",reverseButtons:!0}).then(a=>{a.value?$.ajax({url:base_url+"set_harga/stop_diskon",type:"POST",dataType:"JSON",data:{id:e},success:function(a){swalConfirm.fire("Berhasil Hapus Diskon!",a.pesan,"success"),table.ajax.reload()},error:function(a,e,t){Swal.fire("Terjadi Kesalahan")}}):a.dismiss===Swal.DismissReason.cancel&&swalConfirm.fire("Dibatalkan","Aksi Dibatalakan","error")})}function reset_modal_form(){$("#form-harga")[0].reset(),$(".append-opt").remove(),$("div.form-group").children().removeClass("is-invalid invalid-feedback"),$("span.help-block").text(""),$(".kt-select2").val("").trigger("change"),$("#div_diskon_area").addClass("hidden")}function readURL(a){var e;a.files&&a.files[0]?((e=new FileReader).onload=function(a){$("#div_preview_foto").css("display","block"),$("#preview_img").attr("src",a.target.result)},e.readAsDataURL(a.files[0])):($("#div_preview_foto").css("display","none"),$("#preview_img").attr("src",""))}$(document).ready(function(){$("input.numberinput").bind("keypress",function(a){return 8==a.which||0==a.which||!(a.which<48||57<a.which)||46==a.which}),$("#is_diskon").change(function(a){a.preventDefault(),"1"==$(this).val()?$("#div_diskon_area").removeClass("hidden"):($("#div_diskon_area").addClass("hidden"),$("#diskon").val("").trigger("change"),$("#masa_berlaku").val(""),$("#tgl_mulai_disc").val(""))}),table=$("#tabel_harga").DataTable({responsive:!0,searchDelay:500,processing:!0,serverSide:!0,ajax:{url:base_url+"set_harga/list_harga",type:"POST"},columnDefs:[{targets:[-1],orderable:!1}]}),$("#talent").select2({tokenSeparators:[","," "],minimumInputLength:0,minimumResultsForSearch:5,ajax:{url:base_url+"master_talent/get_select_talent",dataType:"json",type:"GET",data:function(a){return{term:a.term}},processResults:function(a){return{results:$.map(a,function(a){return{text:a.text,id:a.id}})}}}}),$("#diskon").select2({tokenSeparators:[","," "],minimumInputLength:0,minimumResultsForSearch:5,ajax:{url:base_url+"master_diskon/get_select_diskon",dataType:"json",type:"GET",data:function(a){return{term:a.term}},processResults:function(a){return{results:$.map(a,function(a){return{text:a.text,id:a.id,kode:a.koderef,nama:a.nama}})}}}}),$(document).on("click",".btn_edit_status",function(){var e=$(this).attr("id"),t=$(this).val();swalConfirm.fire({title:"Ubah Status Data User ?",text:"Apakah Anda Yakin ?",icon:"warning",showCancelButton:!0,confirmButtonText:"Ya, Ubah Status!",cancelButtonText:"Tidak, Batalkan!",reverseButtons:!0}).then(a=>{a.value?$.ajax({url:base_url+"master_user/edit_status_user/"+e,type:"POST",dataType:"JSON",data:{status:t},success:function(a){swalConfirm.fire("Berhasil Ubah Status User!",a.pesan,"success"),table.ajax.reload()},error:function(a,e,t){Swal.fire("Terjadi Kesalahan")}}):a.dismiss===Swal.DismissReason.cancel&&swalConfirm.fire("Dibatalkan","Aksi Dibatalakan","error")})}),$(".modal").on("hidden.bs.modal",function(){reset_modal_form()})});