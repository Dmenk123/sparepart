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
    
        <form id="form_pembayaran" name="form_pembayaran">
           <div class="kt-portlet">
              <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                  <h3 class="kt-portlet__head-title">
                    Formulir Pembayaran
                  </h3>
                </div>
              </div>
              <div class="kt-portlet__body">
                  <div class="form-group">
                    <label>Kode Pembayaran</label>
                    <input type="text" class="form-control" value="<?= $kode_trans; ?>" disabled>
                    <input type="hidden" class="form-control" id="kode_trans" name="kode_trans" value="<?= $kode_trans; ?>">
                    <input type="hidden" class="form-control" id="id_trans" name="id_trans"  value="<?php $value = (isset($id_trans))?$id_trans:''; echo $value; ?>">
                    <input type="hidden" class="form-control" id="id_trans_det" name="id_trans_det"  value="<?php $value = (isset($id_trans_det))?$id_trans_det:''; echo $value; ?>">
                    <span class="help-block"></span>
                  </div>
                  <div class="form-group">
                    <label for="">Tanggal</label>
                    <input type="text" class="form-control kt_datepicker" id="tanggal" readonly="" name="tanggal"> 
                    <span class="help-block"></span>
                  </div>
                  <div class="form-group">
                    <label for="exampleSelect1">Kode Pembelian</label>
                    <select name="kode_pembelian" id="kode_pembelian" class="form-control select2">
                      <option value="0" data-id="0">-PILIH-</option>
                      <?php foreach($data_hutang as $row):?>
                          <option value="<?php echo $row->kode;?>" title="<?php echo $row->hutang_fix;?>"><?php echo $row->kode;?></option>
                      <?php endforeach;?>
                    </select>
                    <span class="help-block"></span>
                  </div>
                  <div class="form-group">
                    <label>Sisa Hutang</label>
                    <input type="text"  class="form-control" id="hutang_txt" name="hutang_txt" value="" disabled>
                    <input type="hidden" class="form-control" id="hutang" name="hutang" value="">
                    <span class="help-block"></span>
                  </div>
                  <div class="form-group">
                    <label>Pembayaran</label>
                    <input type="text"  class="form-control uang" id="pembayaran" name="pembayaran" value="">
                    <span class="help-block"></span>
                  </div>
                  <div class="form-group">
                    <label>Keterangan</label>
                    <input type="text"  class="form-control" id="keterangan" name="keterangan" value="">
                    <span class="help-block"></span>
                  </div>
              </div>
              <div class="kt-portlet__foot">
                <div class="kt-form__actions">
                  <button type="submit" class="btn btn-primary">Submit</button>
                  <button type="reset" class="btn btn-secondary">Cancel</button>
                </div>
              </div>
            </div>         
        </form>
      </div>
    </div>
  </div>
  
</div>



