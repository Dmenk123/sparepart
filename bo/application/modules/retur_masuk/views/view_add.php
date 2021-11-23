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
              <table class="table table-borderless">
                <tr>
                  <th>Kode Retur</th>
                  <td><?= (isset($data_header->kode_retur)) ? $data_header->kode_retur : '-';?></span></td>
                </tr>
                <tr>
                  <th>Tanggal Retur</th>
                  <td><?= (isset($data_header->tanggal_retur)) ? DateTime::createFromFormat('Y-m-d',$data_header->tanggal_retur)->format('d/m/Y') : '-';?></td>
                </tr>
                <tr>
                  <th>Agen</th>
                  <td><?= (isset($data_header->nama_perusahaan)) ? $data_header->nama_perusahaan : "";?></span></td>
                </tr>
              </table>
            </div><!-- /.col -->
            <div class="col-sm-6 invoice-col">
              <table class="table table-borderless">
                <tr>
                  <th>Kode Penerimaan Retur</th>
                  <td><?= (isset($data_header->kode)) ? $data_header->kode : '-';?></span></td>
                </tr>
                <tr>
                  <th>Petugas</th>
                  <td><?= $data_user[0]->nama;?></td>
                </tr>
              </table>
            </div><!-- /.col -->
        </div>

        <hr>

        <form id="regForm">
          <input type="hidden" name="id" id="id" value="<?=$data_header->id;?>">
          <input type="hidden" name="id_retur" id="id_retur" value="<?=$data_header->id_retur_beli;?>">
          <input type="hidden" name="kode" id="kode" value="<?=$data_header->kode;?>">
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
            <div class="form-group col-sm-6" style="padding-top:25px;">
              <a class="btn btn-default pull-left" href="<?=base_url($this->uri->segment(1));?>"><i class="fa fa-arrow-left"></i> Kembali</a>
            </div>
            <div class="form-group col-sm-6" style="padding-top:25px;">
                <button type="submit" id="btnSaveAdd" class="btn btn-primary pull-right">Simpan</button>
            </div>
          </div>
        </form>
   
        

        <!-- this row will not appear when printing -->
        <!-- <div class="row no-print">
            <div class="col-sm-6">
              
            </div>
        </div> -->
       
      </div>
    </div>
  </div>
  
</div>