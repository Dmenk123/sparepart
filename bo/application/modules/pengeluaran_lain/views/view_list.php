<!-- begin:: Content -->
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

  <!-- begin:: Content Head -->
  <div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
      <div class="kt-subheader__main">
        <h3 class="kt-subheader__title">
          <?= $this->template_view->nama('judul') . ' - ' . $title; ?>
        </h3>
      </div>
    </div>
  </div>
  <!-- end:: Content Head -->

  <!-- begin:: Content -->
  <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

    <div class="kt-portlet kt-portlet--mobile">
      <div class="kt-portlet__head kt-portlet__head--lg">
        
        <div class="kt-portlet__head-toolbar">
          <div class="kt-portlet__head-wrapper">
            <div class="kt-portlet__head-actions row">
              <form method="get" id="formFilter">
                <div class="form-row">
                  <div class="col">
                    <select name="bulan" id="bulan" class="form-control select2" style="width:100%;">
                      <option value="">Bulan Laporan</option>
                      <?php 
                      for ($i=1; $i <= 12; $i++) { 
                        if((int)$this->input->get('bulan') == $i) {
                          echo "<option value='$i' selected>".bulan_indo($i)."</option>";
                        }else{
                          echo "<option value='$i'>".bulan_indo($i)."</option>";
                        }
                      }
                      ?>
                    </select>
                  </div>
                  <div class="col">
                    <select name="tahun" id="tahun" class="form-control select2">
                      <option value="">Tahun Laporan</option>
                      <?php 
                      for ($i=(int)date('Y')+20; $i >= (int)date('Y')-20; $i--) { 
                        if ((int)$this->input->get('tahun') == $i) {
                          echo "<option value='$i' selected>$i</option>";
                        }else{
                          echo "<option value='$i'>$i</option>";
                        }
                      }
                      ?>
                    </select>
                  </div>
                  <div class="col">
                    <select name="kategori" id="kategori" class="form-control select2">
                      <option value="">Semua Kategori</option>
                      <?php 
                        foreach ($kategori as $key => $value) {
                          if ((int)$this->input->get('kategori') == $value->id_kategori_trans) {
                            echo "<option value='$value->id_kategori_trans' selected>$value->nama_kategori_trans</option>";
                          }else{
                            echo "<option value='$value->id_kategori_trans'>$value->nama_kategori_trans</option>";
                          }
                        
                        }
                      ?>
                    </select>
                  </div>
                  <button type="submit" form="formFilter" class="btn btn-success btn-bold btn-sm"><i class="la la-filter"></i>Filter</button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="kt-portlet__head-toolbar" style="float-right;"><?= $this->template_view->getAddButton(false, 'add_menu', 'new_pengeluaran', 'Tambah Pengeluaran'); ?></div>
      </div>
      <div class="kt-portlet__body">

        <!--begin: Datatable -->
        <table class="table table-striped- table-bordered table-hover table-checkable" id="tabel_pengeluaran">
          <thead>
            <tr>
              <th style="width: 5%;">No</th>
              <th>Tanggal</th>
              <th>Kode</th>
              <th>Kategori</th>
              <th>User</th>
              <th>Total Rupiah</th>
              <th>Metode</th>
              <th style="width: 5%;">Aksi</th>
            </tr>
          </thead>
        </table>

        <!--end: Datatable -->
      </div>
    </div>
  </div>

</div>