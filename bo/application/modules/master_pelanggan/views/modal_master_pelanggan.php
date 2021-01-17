
<div class="modal fade modal_add_form" tabindex="-1" role="dialog" aria-labelledby="add_menu" aria-hidden="true" id="modal_pelanggan_form">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal_title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        </button>
      </div>
      <div class="modal-body">
        <form id="form-user" name="form-user" class="kt-form">
        <div class="kt-wizard-v2__content" data-ktwizard-type="step-content" data-ktwizard-state="current">
            <div class="kt-heading kt-heading--md"><?= $title; ?></div>
              <div class="kt-form__section kt-form__section--first">
                <div class="kt-wizard-v2__form">
                  <div class="form-group">
                    <input type="hidden" class="form-control" id="id_pelanggan" name="id_pelanggan">
                    <label for="lbl_username" class="form-control-label">Nama Pembeli:</label>
                    <input type="text" class="form-control" id="nama_pembeli" name="nama_pembeli" autocomplete="off">
                    <span class="help-block"></span>
                  </div>
                  <div class="form-group">
                    <label for="lbl_namabarang" class="form-control-label">Alamat :</label>
                    <textarea type="text" class="form-control" id="alamat" name="alamat" autocomplete="off"></textarea>
                    <span class="help-block"></span>
                  </div>

                  <div class="form-group">
                    <label>Provinsi :</label>
                      <select name="provinsi" id="provinsi" class="form-control select2">
                          <option value="0">Silahkan Pilih Salah Satu</option>
                          <?php foreach($provinsi->result() as $row):?>
                              <option value="<?php echo $row->id_provinsi;?>"><?php echo $row->nama_provinsi;?></option>
                          <?php endforeach;?>
                      </select>
                    <span class="help-block"></span>
                  </div>
                  <div class="form-group">
                    <label for="lbl_kategori" class="form-control-label">Kota :</label>
                      <select name="kota" class="kota form-control select2">
                          <option value="0">Silahkan Pilih Salah Satu</option>
                          <?php foreach($kota->result() as $row):?>
                              <option value="<?php echo $row->id_kota;?>"><?php echo $row->nama_kota;?></option>
                          <?php endforeach;?>
                      </select>
                    <span class="help-block"></span>
                  </div>
                  <div class="form-group">
                    <label for="lbl_kategori" class="form-control-label">Kecamatan :</label>
                    <input type="text" class="form-control" id="kecamatan" name="kecamatan" autocomplete="off">
                    <span class="help-block"></span>
                  </div>
                  <div class="row">
                    <div class="form-group col-sm-6">
                      <label for="lbl_kategori" class="form-control-label">No Telp :</label>
                      <input type="text" class="form-control" id="telp" name="telp" autocomplete="off">
                      <span class="help-block"></span>
                    </div>
                    <div class="form-group col-sm-6">
                      <label for="lbl_kategori" class="form-control-label">Email :</label>
                      <input type="text" class="form-control" id="email" name="email" autocomplete="off">
                      <span class="help-block"></span>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="lbl_kategori" class="form-control-label">Nama Toko :</label>
                    <input type="text" class="form-control" id="nama_toko" name="nama_toko" autocomplete="off">
                    <span class="help-block"></span>
                  </div>
         
          <!-- end KT wizard form -->
                      </div>
              </div>
          </div>
         
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="btnSave" onclick="save()">Simpan</button>
      </div>
    </div>
  </div>
</div>
