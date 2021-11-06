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
            <div class="kt-portlet__head-actions row">
            </div>
          </div>
        </div>
      </div>
      <div class="kt-portlet__body">

        <!--begin: Datatable -->
        <form id="form-user" name="form-user">
        <div class="row">
            <div class="form-group col-sm-4">
              <label for="lbl_username" class="form-control-label">Tgl Mulai :</label>
              <!-- <div class="col-sm-3"> -->
              <input type="text" class="form-control kt_datepicker" id="start" name="start" autocomplete="off">
              <!-- </div> -->
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-4">
              <label for="lbl_username" class="form-control-label">Tgl Akhir :</label>
              <!-- <div class="col-sm-3"> -->
              <input type="text" class="form-control kt_datepicker" id="end" name="end" autocomplete="off" >
              <!-- </div> -->
              <span class="help-block"></span>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-sm-4">
              <label for="lbl_username" class="form-control-label">Nama Pelanggan:</label>
              <!-- <div class="col-sm-3"> -->
              <select name="id_pelanggan" id="id_pelanggan" class="form-control select2" onchange="getBarang(this)">
                  <option value="">Pilih Pelanggan</option>
                  <?php foreach($pelanggan->result() as $row):?>
                      <option value="<?php echo $row->id_pelanggan;?>"><?php echo $row->nama_pembeli;?></option>
                  <?php endforeach;?>
              </select>
              <!-- </div> -->
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-4">
              <label for="lbl_username" class="form-control-label">Nama Barang :</label>
              <!-- <div class="col-sm-3"> -->
              <select name="id_barang" id="id_barang" class="form-control select2" >
                  <option value="">Pilih Barang</option>
              </select>
              <!-- </div> -->
              <span class="help-block"></span>
            </div>
          </div>
          <div class="kt-portlet__foot">
            <div class="kt-form__actions">
              <button type="button" class="btn btn-success" id="filters">Submit</button>
              <button type="reset" class="btn btn-secondary">Cancel</button>
            </div>
          </div>
         
        </form>

        <div class="row">
          <div class="col-sm-5">
            <table class="table table-striped- table-bordered table-hover table-checkable" id="tabel_mon_pelanggan">
              <thead>
                <tr>
                  <th style="width: 5%;">No</th>
                  <th>Nama Pelanggan</th>
                  <th>Nama Barang</th>
                  <th>qty</th>
                  <th>Harga</th>
                  <th>Tanggal Order</th>
                </tr>
              </thead>
                <tbody>
                </tbody>      
            </table>
          </div>
          <div class="col-sm-7">
            <canvas id="line-chart" width="800" height="450"></canvas>
          </div>
        <div>

        <!--end: Datatable -->
      </div>
    </div>
  </div>
  
</div>



