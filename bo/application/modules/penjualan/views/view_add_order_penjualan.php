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
              <h2>ADD ORDER</h>
            </div>
          </div>
        </div>
      </div>
      
      <div class="kt-portlet__body">

        <!--begin: Form Invoice -->
            <!-- title row -->
            <div class="row">
                <div class="col-xs-12">
                    <h2 class="page-header">
                        <i class="fa fa-globe"></i> JMP Motor.
                        <!-- <small class="pull-right">Date: 2017/01/09</small> -->
                    </h2>
                </div><!-- /.col -->
            </div>
            <!-- info row -->
            <div class="row invoice-info">
                <div class="col-sm-6 invoice-col">
                    <address>
                        <table class="table table-borderless">
                          <tr>
                            <th>Address</th>
                            <td><?= (isset($invoice->alamat)) ? $invoice->alamat : '-';?></span></td>
                          </tr>
                          <!-- <tr>
                            <th>Tanggal Pembelian</th>
                            <td><?= (isset($data->tanggal_beli)) ? DateTime::createFromFormat('Y-m-d',$data->tanggal_beli)->format('d/m/Y') : '-';?></td>
                          </tr> -->
                          <tr>
                            <th rowspan="2">Phone</th>
                            <td><?= (isset($invoice->no_telp)) ? $invoice->no_telp : "";?></span></td>
                          </tr>
                          <tr>
                            <td><?= (isset($invoice->email)) ? $invoice->email : "";?></span></td>
                          </tr>
                        </table>
                    </address>
                </div><!-- /.col -->

                <div class="col-sm-6 invoice-col">
                   <table class="table table-borderless">
                      <tr>
                        <th>No. Faktur</th>
                        <td><?= (isset($invoice->no_faktur)) ? $invoice->no_faktur : '-';?></span></td>
                      </tr>
                      <tr>
                        <th>Sales Name</th>
                        <td><?= (isset($invoice->username)) ? $invoice->username : "";?></span></td>
                      </tr>
                      <tr>
                        <th colspan="2">Barang Tidak ada ? 
                          <span><a data-target="#modal_frame" data-toggle="modal" href="#modal_frame">Klik disini untuk buka Form Master Barang</a></span>
                        </th>
                      </tr>
                    </table>
                </div><!-- /.col -->
            </div><!-- /.row -->

            <form id="regForm">
            <div class="row">
                <div class="form-group col-sm-4">
                  <label for="lbl_gudang" class="form-control-label">Gudang :</label>
                  <input type="hidden" value="<?= (isset($invoice->id_penjualan))?$invoice->id_penjualan:"";?>" name="id_penjualan" id="id_penjualan">
                  <select name="id_gudang" id="id_gudang" class="form-control select2" onchange="getSelectBarang(this)">
                      <option value="0">-PILIH-</option>
                      <?php foreach($gudang->result() as $row):?>
                          <option value="<?php echo $row->id_gudang;?>"><?php echo $row->nama_gudang;?></option>
                      <?php endforeach;?>
                  </select>
                  <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-4">
                  <label for="lbl_namabarang" class="form-control-label">Nama Barang : </label>
                  <select name="id_barang" id="id_barang" class="form-control select2">
                      <option value="0">-PILIH-</option>
                  </select>
                  <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-1">
                    <label for="lbl_hargabarang" class="form-control-label">Qty :</label>
                    <input type="number" class="form-control" id="qty" name="qty" autocomplete="off">
                    <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-2">
                    <label for="lbl_hargabarang" class="form-control-label">Diskon :</label>
                    <input type="text" class="form-control percent" id="dis" name="diskon" value="0" autocomplete="off">
                    <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-3" style="padding-top:25px;">
                  <button id="btnSave" type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
            </form>
            <br />
            <!-- Table row -->
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
            </div><!-- /.row -->

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
            </div><!-- /.row -->

            <!-- this row will not appear when printing -->
            <div class="row no-print">
                <div class="form-group col-sm-6" style="padding-top:25px;">
                  <a class="btn btn-default pull-left" href="<?=base_url($this->uri->segment(1));?>"><i class="fa fa-arrow-left"></i> Kembali</a>
                </div>
                <div class="form-group col-sm-6" style="padding-top:25px;">
                  <button class="btn btn-success pull-right" ><i class="fa fa-credit-card"></i> Submit Payment</button>
                </div>
            </div>
        <!--end: Form Invoice -->
       
      </div>
    </div>
  </div>
  
</div>



