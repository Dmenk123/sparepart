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
    margin-right: .3em;
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
        <div class="kt-portlet__head-toolbar">
          <div class="kt-portlet__head-wrapper">
            <div class="row" style="text-align:left!important;">
              <h2>Form Pengeluaran Lain-Lain</h>
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
              <i class="fa fa-globe"></i> <?= $profil->nama_usaha; ?>.
              <!-- <small class="pull-right">Alamat : <?= $profil->alamat; ?></small> -->
            </h2>
          </div><!-- /.col -->
        </div>
        <!-- info row -->
        <div class="row invoice-info">
          <div class="col-sm-6 invoice-col">
            <address>
              <table class="table table-borderless">
                <tr>
                  <th>Tanggal</th>
                  <td><?= (isset($data_header->tanggal)) ? tanggal_indo($data_header->tanggal) : '-'; ?></td>
                </tr>
                <tr>
                  <th>Kode</th>
                  <td><?= (isset($data_header->kode)) ? $data_header->kode : '-'; ?></span></td>
                </tr>
              </table>
            </address>
          </div>

          <div class="col-sm-6 invoice-col">
            <table class="table table-borderless">
              <tr>
                <th>Petugas</th>
                <td><?= (isset($data_header->nama_user)) ? $data_header->nama_user : ""; ?></span></td>
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
            <div class="form-group col-sm-5">
              <label for="lbl_namabarang" class="form-control-label">Nama Barang/Jasa : </label>
              <input type="hidden" value="<?= (isset($data_header->id)) ? $data_header->id : ""; ?>" name="id_pengeluaran_lain" id="id_pengeluaran_lain">
              <select name="id_barang" id="id_barang" class="form-control select2">
                <option value="">-PILIH-</option>
                <?php
                foreach ($barang as $key => $value) {
                  echo "<option value='$value->id_barang'>$value->nama</option>";
                }
                ?>
              </select>
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-1">
              <label for="lbl_hargabarang" class="form-control-label">Qty :</label>
              <input type="number" class="form-control" id="qty" name="qty" autocomplete="off">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-3">
              <label for="lbl_hargabarang" class="form-control-label">Nilai Rupiah :</label>
              <input type="text" class="form-control uang" id="nilai" name="nilai" autocomplete="off" align="right">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-3">
              <label for="lbl_hargabarang" class="form-control-label">Keterangan :</label>
              <input type="text" class="form-control" id="keterangan" name="keterangan" autocomplete="off" align="right">
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
                  <th>Keterangan</th>
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
              <table class="table" width="100%">
                <tbody>
                  <tr>
                    <td width="69%">Total:</td>
                    <td id="total" align="right">0</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div><!-- /.col -->
        </div><!-- /.row -->

        <!-- this row will not appear when printing -->
        <div class="row no-print">
          <div class="form-group col-sm-6" style="padding-top:25px;">
            <a class="btn btn-default pull-left" href="<?= base_url($this->uri->segment(1)); ?>"><i class="fa fa-arrow-left"></i> Kembali</a>
          </div>
          <div class="form-group col-sm-6" style="padding-top:25px;">
            <button class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> Submit Payment</button>
          </div>
        </div>
        <!--end: Form Invoice -->

      </div>
    </div>
  </div>

</div>