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
              <h2>Form Retur Penerimaan Pembelian</h>
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
                  <td><span><?= (isset($data_header->tanggal)) ? tanggal_indo($data_header->tanggal) : '-'; ?><span</td>
                </tr>
                <tr>
                  <th>Kode</th>
                  <td>
                    <span><?= (isset($data_header->kode_retur)) ? $data_header->kode_retur : '-'; ?></span>
                    <span id="span-id-retur" class="hidden"><?= (isset($data_header->id)) ? $data_header->id : '-'; ?></span>
                    <span id="span-kode-retur" class="hidden"><?= (isset($data_header->kode_retur)) ? $data_header->kode_retur : '-'; ?></span>
                  </td>
                </tr>
              </table>
            </address>
          </div>

          <div class="col-sm-6 invoice-col">
            <table class="table table-borderless">
              <tr>
                <th>Petugas</th>
                <td><span><?= (isset($data_header->nama_user)) ? $data_header->nama_user : ""; ?></span></td>
              </tr>
              <tr>
                <th>Jenis Retur</th>
                <td><span><?= (isset($data_header->jenis_retur) && $data_header->jenis_retur == '1') ? 'Ganti Barang' : "Potong Nota"; ?></span></td>
              </tr>
              <tr>
                <th>Supplier</th>
                <td><span><?= (isset($data_header->nama_perusahaan)) ? $data_header->nama_perusahaan : ""; ?></span></td>
              </tr>
            </table>
          </div><!-- /.col -->
        </div><!-- /.row -->

        <br />

        <!-- Table row -->
        <div class="kt-portlet">
          <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
              <h3 class="kt-portlet__head-title">
                Data Penerimaan Pembelian ( Kode : <?= $data_header->kode_penerimaan; ?> )
              </h3>
            </div>
          </div>
          <div class="kt-portlet__body">

            <!--begin::Section-->
            <div class="kt-section">
              <span class="kt-section__info">
                Klik Tombol <b>Gunakan Data</b> pada tabel penerimaan dibawah, untuk diproses pada <b>Transaksi Retur.</b>
              </span>
              <div class="kt-section__content">
                <table class="table">
                  <thead class="thead-light">
                    <tr>
                      <th style="text-align:center">No</th>
                      <th style="text-align:center">Barang</th>
                      <th style="text-align:center">Gudang</th>
                      <th style="text-align:center">Qty</th>
                      <th style="text-align:center">Harga</th>
                      <th style="text-align:center">Total</th>
                      <th style="text-align:center">Qty (Retur)</th>
                      <th style="text-align:center">#</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($data_penerimaan as $key => $value) {
                       echo "<tr>
                          <th scope='row'>".($key+1)."</th>
                          <td>$value->nama_barang</td>
                          <td>$value->nama_gudang</td>
                          <td>$value->qty</td>
                          <td align='right'>".number_format($value->harga,0,',','.')."</td>
                          <td align='right'>".number_format($value->harga*$value->qty,0,',','.')."</td>
                          <td width='10%'>
                            <input type ='text' class='form-control numberinput' id='inputQty-".$value->id_penerimaan_det."' name='inputQty-".$value->id_penerimaan_det."' value='' />
                            <input type ='hidden' class='form-control' id='inputIdStok-".$value->id_penerimaan_det."' name='inputIdStok-".$value->id_penerimaan_det."' value='".$value->id_stok."' />
                          </td>
                          <td width='15%' align='right'><button class='btn btn-sm btn-primary' onclick='gunakanDataPenerimaan(".$value->id_penerimaan_det.")'>Gunakan Data</button></td>
                        </tr>";
                    } ?>
                  </tbody>
                </table>
              </div>
            </div>
            <!--end::Section-->

            
            <!--begin::Section-->
            <div class="kt-section">
            <span class="kt-section__info">
                <b>Tabel dibawah ini adalah data Penerimaan Pembelian yang akan dilakukan Retur</b>
              </span>
              <div class="kt-section__content">
                <table class="table">
                  <thead class="thead-dark">
                    <tr>
                      <th style="text-align:center">No</th>
                      <th style="text-align:center">Barang</th>
                      <th style="text-align:center">Gudang</th>
                      <th style="text-align:center">Qty</th>
                      <th style="text-align:center">Harga</th>
                      <th style="text-align:center">Total</th>
                      <th style="text-align:center">#</th>
                    </tr>
                  </thead>
                  <tbody id="tbody"></tbody>
                </table>
              </div>
            </div>
            <!--end::Section-->
          </div>
        </div>
                  
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