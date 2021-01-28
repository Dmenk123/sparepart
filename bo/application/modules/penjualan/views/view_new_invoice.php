<style>
#pageMessages {
  position: fixed;
  bottom: 15px;
  right: 15px;
  width: 30%;
}

.alert {
  position: relative;
}

.alert .close {
  position: absolute;
  top: 5px;
  right: 5px;
  font-size: 1em;
}

.alert .fa {
  margin-right:.3em;
}
</style>
<!-- begin:: Content -->
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

  <!-- begin:: Content Head -->
  <div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
      <div class="kt-subheader__main">
        <!-- <h3 class="kt-subheader__title">
        
        </h3> -->
      </div>
    </div>
  </div>
  <!-- end:: Content Head -->

  <!-- begin:: Content -->
  <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    
    <div class="kt-portlet kt-portlet--mobile">
      <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
        </div>
        <div id="pageMessages"></div>
        <div class="kt-portlet__head-toolbar" >
          <div class="kt-portlet__head-wrapper">
            <div class="row" style="text-align:left!important;">
              <h2><?php echo $title;?></h>
            </div>
          </div>
        </div>
      </div>
      <div class="kt-portlet__body">

        <!--begin: Form Invoice -->
    
        <form id="form-user" name="form-user">
            <div class="row">
                <div class="form-group col-sm-4">
                    <input type="hidden" class="form-control" id="id_agen" name="id_agen">
                    <input type="hidden" class="form-control" id="id_penjualan" name="id_penjualan"  value="<?php $value = (isset($id_penjualan))?$id_penjualan:''; echo $value; ?>">
                    <input type="hidden" class="form-control" id="mode" name="mode" value="<?php echo $mode;?>">
                    <label for="lbl_username" class="form-control-label">Nama Pelanggan:</label>
                        <select name="pelanggan" id="pelanggan" class="form-control select2">
                            <option value="0">-PILIH-</option>
                            <?php foreach($pelanggan->result() as $row):?>
                                <option value="<?php echo $row->id_pelanggan;?>" <?php echo $invoice->id_pelanggan == $row->id_pelanggan ? 'selected' : ''?>><?php echo $row->nama_toko;?></option>
                            <?php endforeach;?>
                        </select>
                    <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-4">
                    <label for="lbl_namabarang" class="form-control-label">Nama Sales :</label>
                        <select name="sales" id="sales" class="form-control select2">
                            <option value="0">-PILIH-</option>
                            <?php foreach($sales->result() as $row):?>
                                <option value="<?php echo $row->id;?>" <?php echo $invoice->id_sales == $row->id ? 'selected' : ''?>><?php echo $row->username;?></option>
                            <?php endforeach;?>
                        </select>
                    <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-4">
                    <label for="lbl_hargabarang" class="form-control-label">Tgl Jatuh Tempo :</label>
                    <input type="text" class="form-control kt_datepicker" id="tgl_jatuh_tempo" name="tgl_jatuh_tempo" autocomplete="off" value="<?php $value = (isset($tgl_jatuh_tempo))?$tgl_jatuh_tempo:''; echo $value; ?>">
                    <span class="help-block"></span>
                </div>
            </div>
         
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <?php 
              if ($mode == 'edit') { ?>
                <button type="button" class="btn btn-primary" id="btnSave" onclick="editDataPenjualan()">Simpan
            <?php } else { ?>
                <button type="button" class="btn btn-primary" id="btnSave" onclick="saveDataPenjualan()">Selanjutnya <i class="fa fa-angle-double-right"></i></button>
            <?php   }
              
            ?>
           
        </div>
         
        </form>
        <!--end: Form Invoice -->
        <!-- coba alert -->
       
        <!-- <div class="jumbotron">
        <div class="container">
            <h1>Let's create some Alerts</h1>
        </div>
        </div>
        <div class="container">
        <button class="btn btn-danger" onclick="createAlert('Opps!','Something went wrong','Here is a bunch of text about some stuff that happened.','danger',true,false,'pageMessages');">Add Danger Alert</button>
        <button class="btn btn-success" onclick="createAlert('','Nice Work!','Here is a bunch of text about some stuff that happened.','success',true,true,'pageMessages');">Add Success Alert</button>
        <button class="btn btn-info" onclick="createAlert('BTDubs','','Here is a bunch of text about some stuff that happened.','info',false,true,'pageMessages');">Add Info Alert</button>
        <button class="btn btn-warning" onclick="createAlert('','','Here is a bunch of text about some stuff that happened.','warning',false,true,'pageMessages');">Add Warning Alert</button>
        </div> -->
        <!-- end coba alert -->
      </div>
    </div>
  </div>
  
</div>



