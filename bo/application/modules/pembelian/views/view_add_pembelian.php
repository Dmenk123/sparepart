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
              <address>
                  <table class="table table-borderless">
                    <tr>
                      <th>Kode Pembelian</th>
                      <td><?= (isset($agen->nama_perusahaan)) ? $kode_trans : '-';?></span></td>
                    </tr>
                    <tr>
                      <th>Agen</th>
                      <td><?= (isset($agen->nama_perusahaan)) ? $agen->nama_perusahaan : "";?></span></td>
                    </tr>
                  </table>
              </address>
            </div><!-- /.col -->

            <div class="col-sm-6 invoice-col">
              <table class="table table-borderless">
                <tr>
                  <th>Petugas</th>
                  <td><?= $data_user[0]->nama;?></span></td>
                </tr>
                <tr>
                  <th>Metode Pembayaran</th>
                  <td><?= (isset($pembelian->is_kredit)) ? 'Kredit' : "Cash";?></span></td>
                </tr>
                <tr>
                  <th colspan="2">Barang Tidak ada ? 
                    <span><a data-target="#modal_frame" data-toggle="modal" href="#modal_frame">Klik disini untuk buka Form Master Barang</a></span>
                  </th>
                </tr>
              </table>
            </div><!-- /.col -->
        </div>

        <hr>

        <form id="regForm">
          <div class="row">
              <div class="form-group col-sm-4">
                  <label for="lbl_namabarang" class="form-control-label">Nama Barang :</label>
                  <input type="hidden" name="id_pembelian" id="id_pembelian" value="<?=$id_pembelian;?>">
                  <input type="hidden" name="id_agen" id="id_agen" value="<?=$id_agen;?>">
                  <select name="id_barang" id="id_barang" class="form-control select2">
                      <option value="">-PILIH-</option>
                      <?php foreach($barang->result() as $row):?>
                          <option value="<?php echo $row->id_barang;?>"><?php echo $row->nama;?> | <?php echo $row->sku;?></option>
                      <?php endforeach;?>
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
                  <input type="text" class="form-control percent" id="dis" name="dis" value="0" autocomplete="off">
                  <span class="help-block"></span>
              </div>

              <div class="form-group col-sm-2">
                  <label for="lbl_hargabarang" class="form-control-label">Harga Satuan :</label>
                  <input type="text" class="form-control uang" id="hsat" name="hsat" value="0" autocomplete="off">
                  <span class="help-block"></span>
              </div>

              <div class="form-group col-sm-3" style="padding-top:25px;">
                <button type="submit" class="btn btn-primary">Simpan</button>
              </div>
          </div>
        </form>

        <br />
                        
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
                            <?php if($potong_nota > 0) {
                              $txt_ket = '<b>Belum Dipakai</b>';
                            }else {
                              $txt_ket = '';
                            } ?>
                            <tr>
                                <td width="69%" >Disc Potong Nota : <span id="txt_ket_potong_nota"><?=$txt_ket;?></td>
                                <td>Rp <?=number_format($potong_nota);?></td>
                              </tr>
                            <tr>
                                <td width="69%" >Total:</td>
                                <td id="total">0</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div><!-- /.col -->
        </div>

        <!-- this row will not appear when printing -->
        <div class="row no-print">
          <div class="col-sm-6">
              <a class="btn btn-default pull-left" href="<?=base_url($this->uri->segment(1));?>"><i class="fa fa-arrow-left"></i> Kembali</a>
            </div>
            <div class="col-sm-6">
              <button class="btn btn-success pull-right" ><i class="fa fa-credit-card"></i> Submit Payment</button>
            </div>
        </div>
       
      </div>
    </div>
  </div>
  
</div>



