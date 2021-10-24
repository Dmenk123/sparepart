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
                    <input type="hidden" class="form-control" id="id_penerimaan" name="id_penerimaan"  value="<?php $value = (isset($id_penerimaan))?$id_penerimaan:''; echo $value; ?>">
                    <input type="hidden" class="form-control" id="mode" name="mode" value="<?php echo $mode;?>">
                    <label for="lbl_username" class="form-control-label">Kode Pembelian :</label>
                        <select name="id_pembelian" id="id_pembelian" class="form-control select2">
                            <option value="0">-PILIH-</option>
                            <?php foreach($data_beli->result() as $row):?>
                                <option value="<?php echo $row->id_pembelian;?>"><?php echo $row->kode_pembelian;?></option>
                            <?php endforeach;?>
                        </select>
                    <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-4">
                    <label for="lbl_username" class="form-control-label">Gudang :</label>
                      <select name="id_gudang" id="id_gudang" class="form-control select2">
                          <option value="0">-PILIH-</option>
                          <?php foreach($data_gudang as $val):?>
                              <option value="<?php echo $val->id_gudang;?>"><?php echo $val->nama_gudang;?></option>
                          <?php endforeach;?>
                      </select>
                    <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-4">
                    <label for="lbl_namabarang" class="form-control-label">Kode Penerimaan :</label>
                    <input type="text" class="form-control" value="<?= $kode_trans; ?>" disabled>
                    <input type="hidden" class="form-control" id="kode_penerimaan" name="kode_penerimaan" value="<?= $kode_trans; ?>">
                    <span class="help-block"></span>
                </div>
            </div>
         
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <?php if ($mode == 'edit') { ?>
                <button type="button" class="btn btn-primary" id="btnSave" onclick="editNewPenerimaan()">Simpan
            <?php } else { ?>
                <button type="button" class="btn btn-primary" id="btnSave" onclick="saveNewPenerimaan()">Selanjutnya <i class="fa fa-angle-double-right"></i></button>
            <?php } ?>
          </div>
         
        </form>
      </div>
    </div>
  </div>
  
</div>



