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
            </div>
          </div>
        </div>

        <div class="kt-portlet__head-toolbar" style="float-right;"><?= $this->template_view->getAddButton(false, 'add_menu', 'new_pengeluaran', 'Tambah Pengeluaran'); ?></div>
      </div>
      <div class="kt-portlet__body">
        <form method="get" class="kt-form kt-form--fit kt-margin-b-20" id="formFilter">
          <div class="row kt-margin-b-20">
            <div class="col-lg-4 kt-margin-b-10-tablet-and-mobile">
              <label>Bulan :</label>
              <select name="bulan" id="bulan" class="form-control select2">
                <?php
                $is_all_bln = true;
                if ($this->input->get('bulan') == 'all') {
                  echo '<option value="all" selected>Bulan Laporan</option>';
                } else {
                  echo '<option value="all">Bulan Laporan</option>';
                  $is_all_bln = false;
                }

                for ($i = 1; $i <= 12; $i++) {
                  if ((int)$this->input->get('bulan') == $i) {
                    echo "<option value='$i' selected>" . bulan_indo($i) . "</option>";
                  } else {
                    if ((int)date('m') == $i && $is_all_bln == false) {
                      echo "<option value='$i' selected>" . bulan_indo($i) . "</option>";
                    } else {
                      echo "<option value='$i'>" . bulan_indo($i) . "</option>";
                    }
                  }
                }
                ?>
              </select>
            </div>
            <div class="col-lg-4 kt-margin-b-10-tablet-and-mobile">
              <label>Tahun :</label>
              <select name="tahun" id="tahun" class="form-control select2">
                <?php
                $is_all_thn = true;
                if ($this->input->get('tahun') == 'all') {
                  echo '<option value="all" selected>Tahun Laporan</option>';
                } else {
                  echo '<option value="all">Tahun Laporan</option>';
                  $is_all_thn = false;
                }

                $is_selected = false;
                for ($i = (int)date('Y') + 20; $i >= (int)date('Y') - 20; $i--) {
                  if ((int)$this->input->get('tahun') == $i) {
                    echo "<option value='$i' selected>$i</option>";
                    $is_selected = true;
                  } else {
                    if ((int)date('Y') == $i && $is_all_thn == false && $is_selected == false) {
                      echo "<option value='$i' selected>$i</option>";
                    } else {
                      echo "<option value='$i'>$i</option>";
                    }
                  }
                }
                ?>
              </select>
            </div>
            <div class="col-lg-4 kt-margin-b-10-tablet-and-mobile">
              <label>Kategori :</label>
              <select name="kategori" id="kategori" class="form-control select2">
                <option value="all">Semua Kategori</option>
                <?php
                foreach ($kategori as $key => $value) {
                  if ((int)$this->input->get('kategori') == $value->id_kategori_trans) {
                    echo "<option value='$value->id_kategori_trans' selected>$value->nama_kategori_trans</option>";
                  } else {
                    echo "<option value='$value->id_kategori_trans'>$value->nama_kategori_trans</option>";
                  }
                }
                ?>
              </select>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <button type="submit" class="btn btn-primary btn-brand--icon" id="formFilter">
                <span>
                  <i class="la la-search"></i>
                  <span>Filter</span>
                </span>
              </button>
              &nbsp;&nbsp;
              <!-- <button class="btn btn-secondary btn-secondary--icon" id="kt_reset">
                <span>
                  <i class="la la-close"></i>
                  <span>Reset</span>
                </span>
              </button> -->
            </div>
          </div>

          <div class="kt-separator kt-separator--md kt-separator--dashed"></div>
        </form>

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
              <th style="width: 5%;">Aksi</th>
            </tr>
          </thead>
        </table>

        <!--end: Datatable -->
      </div>
    </div>
  </div>

</div>