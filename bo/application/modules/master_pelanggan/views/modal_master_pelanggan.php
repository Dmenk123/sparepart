
<div class="modal fade modal_add_form" tabindex="-1" role="dialog" aria-labelledby="add_menu" aria-hidden="true" id="modal_pelanggan_form">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal_title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        </button>
      </div>
      <div class="modal-body">
        <form id="form-user" name="form-user">
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
          <div class="row">
            <div class="form-group col-sm-4">
              <label for="lbl_hargabarang" class="form-control-label">Provinsi :</label>
                <select name="provinsi" id="provinsi" class="form-control">
                    <option value="0">-PILIH-</option>
                    <?php foreach($provinsi->result() as $row):?>
                        <option value="<?php echo $row->id_provinsi;?>"><?php echo $row->nama_provinsi;?></option>
                    <?php endforeach;?>
                </select>
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-4">
              <label for="lbl_kategori" class="form-control-label">Kota :</label>
                <select name="kota" class="kota form-control">
                    <option value="0">-PILIH-</option>
                    <?php foreach($kota->result() as $row):?>
                        <option value="<?php echo $row->id_kota;?>"><?php echo $row->nama_kota;?></option>
                    <?php endforeach;?>
                </select>
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-4">
              <label for="lbl_kategori" class="form-control-label">Kecamatan :</label>
              <input type="text" class="form-control" id="kecamatan" name="kecamatan" autocomplete="off">
              <span class="help-block"></span>
            </div>
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
          <!-- <div class="form-group">
            <label>Foto Barang</label>
            <div></div>
            <div class="custom-file">
              <input type="file" class="custom-file-input" id="foto" name="foto" accept=".jpg,.jpeg,.png">
              <label class="custom-file-label" id="label_foto" for="customFile">Pilih gambar yang akan diupload</label>
            </div>
          </div>
          <div class="form-group" id="div_preview_foto" style="display: none;">
            <label for="lbl_password_lama" class="form-control-label">Preview Foto:</label>
            <div></div>
            <img id="preview_img" src="#" alt="Preview Foto" height="200" width="200"/>
            <span class="help-block"></span>
          </div> -->
         
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="btnSave" onclick="save()">Simpan</button>
      </div>
    </div>
  </div>
</div>
