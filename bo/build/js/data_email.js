var save_method,table;function kirim_email(){var e=$("#form_email")[0],a=new FormData(e),e=CKEDITOR.instances.pesan_email.getData();a.append("pesan_email",e),$("#btn_email").prop("disabled",!0),$("#btn_email").text("Menyimpan Data"),$.ajax({type:"POST",enctype:"multipart/form-data",url:base_url+"data_email/kirim_email_manual",data:a,dataType:"JSON",processData:!1,contentType:!1,cache:!1,timeout:6e5,success:function(e){if(e.status)swal.fire("Sukses!!","Konfirmasi Berhasil","success"),window.location=base_url+"data_email";else{if(e.err)swal.fire("Gagal!!","Terjadi Kesalahan","warning");else for(var a=0;a<e.inputerror.length;a++)$('[name="'+e.inputerror[a]+'"]').next().text(e.error_string[a]).addClass("invalid-feedback-select");$("#btn_email").prop("disabled",!1),$("#btn_email").text("Kirim Email")}},error:function(e){console.log("ERROR : ",e),$("#btn_email").prop("disabled",!1),$("#btn_email").text("Kirim Email"),reset_modal_form(),$(".modal").modal("hide")}})}$(document).ready(function(){table=$("#tabel_email").DataTable({responsive:!0,searchDelay:500,processing:!0,serverSide:!0,ajax:{url:base_url+"data_email/list_email",type:"POST"},columnDefs:[{targets:[-1],orderable:!1}]}),$("#pesan_email").ckeditor(),$("input.numberinput").bind("keypress",function(e){return 8==e.which||0==e.which||!(e.which<48||57<e.which)||46==e.which})});