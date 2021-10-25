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
                    <input type="hidden" class="form-control" id="id_pembelian" name="id_pembelian"  value="<?php $value = (isset($id_pembelian))?$id_pembelian:''; echo $value; ?>">
                    <input type="hidden" class="form-control" id="mode" name="mode" value="<?php echo $mode;?>">
                    <label for="lbl_username" class="form-control-label">Agen:</label>
                        <select name="id_agen" id="id_agen" class="form-control select2">
                            <option value="0">-PILIH-</option>
                            <?php foreach($agen->result() as $row):?>
                                <option value="<?php echo $row->id_agen;?>"><?php echo $row->nama_perusahaan;?> | <?php echo $row->alamat;?></option>
                            <?php endforeach;?>
                        </select>
                    <span class="help-block"></span>
                </div>

                <div class="form-group col-sm-4">
                    <label for="" class="form-control-label">Metode Pembayaran:</label>
                        <select name="method_bayar" id="method_bayar" class="form-control select2">
                            <option value="">-PILIH-</option>
                            <option value="1">Cash</option>
                            <option value="2">Kredit</option>
                        </select>
                    <span class="help-block"></span>
                </div>

                <div class="form-group col-sm-4">
                    <label for="lbl_namabarang" class="form-control-label">Kode Transaksi :</label>
                    <input type="text" class="form-control" value="<?= $kode_trans; ?>" disabled>
                    <input type="hidden" class="form-control" id="kode_pembelian" name="kode_pembelian" value="<?= $kode_trans; ?>">
                    <span class="help-block"></span>
                </div>
                <!-- <div class="form-group col-sm-4">
                    <label for="lbl_hargabarang" class="form-control-label">Tgl Jatuh Tempo :</label>
                    <input type="text" class="form-control kt_datepicker" id="tgl_jatuh_tempo" name="tgl_jatuh_tempo" autocomplete="off" value="<?php $value = (isset($tgl_jatuh_tempo))?$tgl_jatuh_tempo:''; echo $value; ?>">
                    <span class="help-block"></span>
                </div> -->
            </div>
         
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <?php if ($mode == 'edit') { ?>
                <button type="button" class="btn btn-primary" id="btnSave" onclick="editNewPembelian()">Simpan
            <?php } else { ?>
                <button type="button" class="btn btn-primary" id="btnSave" onclick="saveNewpembelian()">Selanjutnya <i class="fa fa-angle-double-right"></i></button>
            <?php } ?>
          </div>
         
        </form>
      </div>
    </div>
  </div>
  
</div>



