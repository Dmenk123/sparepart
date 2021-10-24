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
              <h2><?=$title;?></h>
            </div>
          </div>
        </div>
      </div>
      <div class="kt-portlet__body">
        <div class="row">
          <div class="col-xs-12">
            <h2 class="page-header">
              <i class="fa fa-globe"></i> JMP Motor
            </h2>
          </div><!-- /.col -->
        </div>
                   
        <div class="row invoice-info">
            <div class="col-sm-6 invoice-col">
                <b>Kode Pembelian # <?= (isset($data->kode_pembeliaan)) ? $data->kode_pembeliaan : '-';?></b><br>
                <b>Agen :</b> <?= (isset($data->nama_perusahaan)) ? $data->nama_perusahaan : "";?><br>
            </div><!-- /.col -->
            <div class="col-sm-6 invoice-col">
                <b>Kode Penerimaan # <?= (isset($data->kode_penerimaan)) ? $data->kode_penerimaan : '-';?></b><br>
                <b>Petugas :</b>  <?= $data_user[0]->nama;?>
            </div><!-- /.col -->
        </div>

        <hr>

        <form id="regForm">
          <input type="hidden" name="id_penerimaan" id="id_penerimaan" value="<?=$data->id_penerimaan;?>">
          <input type="hidden" name="id_pembelian" id="id_pembelian" value="<?=$data->id_pembelian;?>">
          <input type="hidden" name="kode_penerimaan" id="kode_penerimaan" value="<?=$data->kode_penerimaan;?>">
          <div class="row">
            <div class="col-xs-12 table-responsive">
                <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>Qty</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Sub Total</th>
                        <th>&nbsp;</th>
                      </tr>
                    </thead>
                    <tbody id="tbody">
                    </tbody>
                </table>
            </div><!-- /.col -->
          </div>

          <div class="row">
              <!-- accepted payments column -->
              <div class="col-md-12">
                  <div class="table-responsive">
                      <table class="table" width="100%" >
                          <tbody>
                              <tr>
                                  <td width="69%" >Total:</td>
                                  <td id="total">0</td>
                              </tr>
                          </tbody>
                      </table>
                  </div>
              </div><!-- /.col -->
          </div>
          <div class="row no-print">
            <div class="form-group col-sm-3" style="padding-top:25px;">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
          </div>
        </form>
   
        

        <!-- this row will not appear when printing -->
        <div class="row no-print">
            <div class="col-sm-6">
                <!-- <a href="" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
                <button class="btn btn-primary center" style="margin-right: 5px;"><i class="fa fa-download"></i> Generate PDF</button>
                  -->
                
                <!-- <button class="btn btn-success pull-right" ><i class="fa fa-credit-card"></i> Submit Payment</button> -->
            </div>
        </div>
       
      </div>
    </div>
  </div>
  
</div>



