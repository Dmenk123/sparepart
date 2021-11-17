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
              <h2><?php echo $title; ?> <?php if (isset($no_faktur)) {
                                          echo '- Faktur : ' . $no_faktur;
                                        } ?></h2>
            </div>
          </div>
        </div>
      </div>
      <div class="kt-portlet__body">

        <!--begin: Form Invoice -->

        <form id="form-user" name="form-user">
          <div class="row">
            <div class="form-group col-sm-6">
              <input type="hidden" class="form-control" id="id_pengeluaran" name="id_pengeluaran" value="<?php $value = (isset($id_pengeluaran)) ? $id_pengeluaran : '';
                                                                                                          echo $value; ?>">
              <input type="hidden" class="form-control" id="mode" name="mode" value="<?php echo $mode; ?>">
              <label for="lbl_username" class="form-control-label">Kategori:</label>
              <select name="kategori" id="kategori" class="form-control select2">
                <option value="0">-PILIH-</option>
                <?php foreach ($kategori as $row) : ?>
                  <option value="<?php echo $row->id_kategori_trans; ?>" <?php echo ((isset($old_data)) && ($old_data->id_kategori_trans == $row->id_kategori_trans)) ? 'selected' : '' ?>><?php echo $row->nama_kategori_trans; ?></option>
                <?php endforeach; ?>
              </select>
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-6">
              <label for="lbl_namabarang" class="form-control-label">Tanggal :</label>
              <input type="text" class="form-control kt_datepicker" id="tanggal" readonly="" name="tanggal">
              <span class="help-block"></span>
            </div>
          </div>

          <div class="modal-footer">
            <a type="button" href="<?= base_url($this->uri->segment(1)) ?>" class="btn btn-secondary" data-dismiss="modal">Batal</a>
            <button type="button" class="btn btn-primary" id="btnSave" onclick="saveNewPengeluaran()">Selanjutnya <i class="fa fa-angle-double-right"></i></button>
          </div>

        </form>
        <!--end: Form Invoice -->
        <!-- coba alert -->

        <!-- <div class="jumbotron">
        <div class="container">
            <h1>Let's create some Alerts</h1>
        </div>
        </div>
        <div class="container">
        <button class="btn btn-danger" onclick="createAlert('Opps!','Something went wrong','Here is a bunch of text about some stuff that happened.','danger',true,false,'pageMessages');">Add Danger Alert</button>
        <button class="btn btn-success" onclick="createAlert('','Nice Work!','Here is a bunch of text about some stuff that happened.','success',true,true,'pageMessages');">Add Success Alert</button>
        <button class="btn btn-info" onclick="createAlert('BTDubs','','Here is a bunch of text about some stuff that happened.','info',false,true,'pageMessages');">Add Info Alert</button>
        <button class="btn btn-warning" onclick="createAlert('','','Here is a bunch of text about some stuff that happened.','warning',false,true,'pageMessages');">Add Warning Alert</button>
        </div> -->
        <!-- end coba alert -->
      </div>
    </div>
  </div>

</div>