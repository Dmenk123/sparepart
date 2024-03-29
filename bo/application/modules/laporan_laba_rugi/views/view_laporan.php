<!-- begin:: Content -->
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

  <!-- begin:: Content Head -->
  <div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
      <div class="kt-subheader__main">
        <h3 class="kt-subheader__title">
          <?= $this->template_view->nama('judul').' - '.$title; ?>
        </h3>
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
        <div class="kt-portlet__head-toolbar">
          <div class="kt-portlet__head-wrapper">
           
          </div>
        </div>
      </div>
      <div class="kt-portlet__body">

        <!--begin: Datatable -->
        <form id="submit_form" method="get" action="<?= base_url($this->uri->segment(1))?>" >
          <!-- Horizontal Form -->
         
          <div class="form-group row">
              <label class="col-lg-3 col-form-label text-left">Pilih Periode</label>
              <div class="col-lg-7">
                <select name="model" class="form-control input" id="model" data-id="model" onChange="changeModel()" >
                  <option selected value="">Silahkan Pilih</option>
                  <option value="2" <?php if ( $this->input->get('model') == 2) { echo 'selected'; } ?>>Per Tahun</option>
                  <option value="1" <?php if ( $this->input->get('model') == 1) { echo 'selected'; } ?>>Per Bulan</option>
                  <option value="3" <?php if ( $this->input->get('model') == 3) { echo 'selected'; } ?>>Per Hari</option>
                </select>
              </div>
          </div>
          <div class="form-group row div_tanggal_mulai" style="display:none;">
              <label class="col-lg-3 col-form-label text-left">Tanggal Mulai</label>
              <div class="col-lg-7">
                  <input type="text" class="form-control kt_datepicker" id="tanggal_awal" name="start" value="<?php echo $this->input->get('start') ?? null;?>">
              </div>
          </div>
          <div class="form-group row div_tanggal_akhir" style="display:none;">
              <label class="col-lg-3 col-form-label text-left">Tanggal Akhir</label>
              <div class="col-lg-7">
                  <input type="text" class="form-control kt_datepicker" id="tanggal_akhir" name="end" value="<?php echo $this->input->get('end') ?? null;?>">
              </div>
          </div>
          <div class="form-group row div_bulan" style="display:none;">
              <label class="col-lg-3 col-form-label text-left">Bulan</label>
              <div class="col-lg-7">
                <select name="bulan" class="form-control input" id="bulan" data-id="bulan" >
                  <option selected value="">Silahkan Pilih</option>
                  <option value="1" <?php if ( $this->input->get('bulan') == 1) { echo 'selected'; } ?>>Januari</option>
                  <option value="2" <?php if ( $this->input->get('bulan') == 2) { echo 'selected'; } ?>>Februari</option>
                  <option value="3" <?php if ( $this->input->get('bulan') == 3) { echo 'selected'; } ?>>Maret</option>
                  <option value="4" <?php if ( $this->input->get('bulan') == 4) { echo 'selected'; } ?>>April</option>
                  <option value="5" <?php if ( $this->input->get('bulan') == 5) { echo 'selected'; } ?>>Mei</option>
                  <option value="6" <?php if ( $this->input->get('bulan') == 6) { echo 'selected'; } ?>>Juni</option>
                  <option value="7" <?php if ( $this->input->get('bulan') == 7) { echo 'selected'; } ?>>Juli</option>
                  <option value="8" <?php if ( $this->input->get('bulan') == 8) { echo 'selected'; } ?>>Agustus</option>
                  <option value="9" <?php if ( $this->input->get('bulan') == 9) { echo 'selected'; } ?>>September</option>
                  <option value="10" <?php if ( $this->input->get('bulan') == 10) { echo 'selected'; } ?>>Oktober</option>
                  <option value="11" <?php if ( $this->input->get('bulan') == 11) { echo 'selected'; } ?>>November</option>
                  <option value="12" <?php if ( $this->input->get('bulan') == 12) { echo 'selected'; } ?>>Desember</option>
                </select>
              </div>
          </div>
          <div class="form-group row div_bulan" style="display:none;">
              <label class="col-lg-3 col-form-label text-left">Tahun</label>
              <div class="col-lg-7">
                <select name="tahun" class="form-control input" id="tahun" data-id="tahun" >
                  <option selected value="">Silahkan Pilih</option>
                  <?php for ($i=2020;$i<=date("Y");$i++) { ?>
                  <option value="<?=$i?>" <?php if ( $this->input->get('tahun') == $i) { echo 'selected'; } ?>><?=$i?></option>
                  <?php } ?>
                </select>
              </div>
          </div>
          <div class="form-group row div_tahun" style="display:none;">
              <label class="col-lg-3 col-form-label text-left">Tahun</label>
              <div class="col-lg-7">
                <select name="tahun2" class="form-control input" id="tahun2" data-id="tahun2" >
                  <option selected value="">Silahkan Pilih</option>
                  <?php for ($i=2020;$i<=date("Y");$i++) { ?>
                  <option value="<?=$i?>" <?php if ( $this->input->get('tahun2') == $i) { echo 'selected'; } ?> ><?=$i?></option>
                  <?php } ?>
                </select>
              </div>
          </div>
          <div class="row">
              <label class="col-lg-3"></label>
              <div class="col-lg-9">
                  <div class="form-group form-button">
                      <button type="button" class="btn btn-fill btn-success" onclick="save()">Tampilkan</button>
                      <button type="button" onclick="cetak()" class="btn btn-fill btn-danger">Cetak</button>
                  </div>
              </div>
          </div>
        </form>

        <?php
          if ($this->input->get('model')) {
        ?>
            <table class="table table-striped- table-bordered table-hover table-checkable" id="tabel_laporan">
            <thead>
              <tr>
                <td colspan="4" style="text-align:center;line-height:2px;">
                  <table class="table table-borderless">
                    <tr>
                      <td><?= strtoupper($data_profil->nama_usaha); ?></td>
                    </tr>
                    <tr>
                      <td>Laporan Laba - Rugi</td>
                    </tr>
                    <tr>
                      <td><?=$txt_judul;?></td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <th>Kategori</th>
                <th>Sub Kategori</th>
                <th>Transaksi</th>
                <th>Total</th>
              </tr>
            </thead>
              <tbody>
                <tr>
                  <th colspan="4">Pendapatan</th>
                </tr>
                <tr>
                  <td></td>
                  <td>Penjualan Bersih</td>
                  <td align="right">
                    <?php 
                      $idx = array_search('Penjualan', array_column($data, 'nama_kategori_trans'));
                      echo number_format($data[$idx]->penerimaan,0,',','.');
                    ?>
                  </td>
                </tr>
                <tr>
                  <td></td>
                  <td>Piutang Penjualan</td>
                  <td style="text-decoration: underline;" align="right">
                    <?php 
                      echo number_format($data[$idx]->piutang,0,',','.');
                    ?>
                  </td>
                  <td></td>
                </tr>
                <tr>
                  <td></td>
                  <th>Total Pendapatan</th>
                  <td></td>
                  <td  align="right">
                    <?php 
                      echo "<b>".number_format($data[$idx]->penerimaan - $data[$idx]->piutang,0,',','.')."</b>";
                    ?>
                  </td>
                </tr>
                <!-- <?php foreach ($data as $key => $value) {
                  echo "<tr>
                    <td></td>
                  </tr>";
                } ?> -->
                
              </tbody>      
          </table>
        <?php
          }
        ?>
        

        <!--end: Datatable -->
      </div>
    </div>
  </div>
  
</div>



