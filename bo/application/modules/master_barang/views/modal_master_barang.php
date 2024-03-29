
<div class="modal fade modal_add_form" tabindex="-1" role="dialog" aria-labelledby="add_menu" aria-hidden="true" id="modal_barang_form">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal_title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        </button>
      </div>
      <div class="modal-body">
        <form id="form-user" name="form-user">
          <div class="row">
            <div class="form-group col-sm-4">
              <input type="hidden" class="form-control" id="id_barang" name="id_barang">
              <label for="lbl_username" class="form-control-label">Kode SKU:</label>
              <input type="text" class="form-control" id="sku" name="sku" autocomplete="off">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-8">
            <label for="lbl_namabarang" class="form-control-label">Nama Barang:</label>
            <input type="text" class="form-control" id="nama" name="nama" autocomplete="off">
            <span class="help-block"></span>
          </div>
          </div>
          <div class="row">
            <div class="form-group col-sm-4">
              <label for="lbl_hargabarang" class="form-control-label">Harga Barang :</label>
              <input type="text" class="form-control uang" id="harga" name="harga" autocomplete="off">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-4">
              <label for="lbl_kategori" class="form-control-label">Merk Kategori :</label>
              <select name="kategori" id="kategori" class="form-control">
                  <option value="0">-PILIH-</option>
                  <?php foreach($kategori->result() as $row):?>
                      <option value="<?php echo $row->id_kategori;?>"><?php echo $row->nama_kategori;?></option>
                  <?php endforeach;?>
              </select>
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-4">
              <label for="lbl_satuan" class="form-control-label">Satuan :</label>
              <select name="satuan" id="satuan" class="form-control">
                  <option value="">-PILIH-</option>
                  <?php foreach($satuan->result() as $row):?>
                      <option value="<?php echo $row->id_satuan;?>"><?php echo $row->nama_satuan;?></option>
                  <?php endforeach;?>
              </select>
              <span class="help-block"></span>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-sm-6">
              <label for="lbl_kategori" class="form-control-label">Shopee Link :</label>
              <input type="text" class="form-control" id="shopee" name="shopee" autocomplete="off">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-6">
              <label for="lbl_kategori" class="form-control-label">Tokopedia Link :</label>
              <input type="text" class="form-control" id="tokopedia" name="tokopedia" autocomplete="off">
              <span class="help-block"></span>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-sm-6">
              <label for="lbl_kategori" class="form-control-label">Bukalapak Link :</label>
              <input type="text" class="form-control" id="bukalapak" name="bukalapak" autocomplete="off">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-6">
              <label for="lbl_kategori" class="form-control-label">Lazada Link :</label>
              <input type="text" class="form-control" id="lazada" name="lazada" autocomplete="off">
              <span class="help-block"></span>
            </div>
          </div>
          <div class="form-group">
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
          </div>
          <div class="form-group">
            <label>Foto Barang Kedua</label>
            <div></div>
            <div class="custom-file">
              <input type="file" class="custom-file-input" id="foto_kedua" name="foto_kedua" accept=".jpg,.jpeg,.png">
              <label class="custom-file-label" id="label_foto" for="customFile">Pilih gambar yang akan diupload</label>
            </div>
          </div>
          <div class="form-group" id="div_preview_foto_kedua" style="display: none;">
            <label for="lbl_password_lama" class="form-control-label">Preview Foto:</label>
            <div></div>
            <img id="preview_img_kedua" src="#" alt="Preview Foto" height="200" width="200"/>
            <span class="help-block"></span>
          </div>
          <div class="form-group">
            <label>Foto Barang Ketiga</label>
            <div></div>
            <div class="custom-file">
              <input type="file" class="custom-file-input" id="foto_ketiga" name="foto_ketiga" accept=".jpg,.jpeg,.png">
              <label class="custom-file-label" id="label_foto" for="customFile">Pilih gambar yang akan diupload</label>
            </div>
          </div>
          <div class="form-group" id="div_preview_foto_ketiga" style="display: none;">
            <label for="lbl_password_lama" class="form-control-label">Preview Foto:</label>
            <div></div>
            <img id="preview_img_ketiga" src="#" alt="Preview Foto" height="200" width="200"/>
            <span class="help-block"></span>
          </div>
          <div class="form-group">
            <label>Foto Barang Keempat</label>
            <div></div>
            <div class="custom-file">
              <input type="file" class="custom-file-input" id="foto_keempat" name="foto_keempat" accept=".jpg,.jpeg,.png">
              <label class="custom-file-label" id="label_foto" for="customFile">Pilih gambar yang akan diupload</label>
            </div>
          </div>
          <div class="form-group" id="div_preview_foto_keempat" style="display: none;">
            <label for="lbl_password_lama" class="form-control-label">Preview Foto:</label>
            <div></div>
            <img id="preview_img_keempat" src="#" alt="Preview Foto" height="200" width="200"/>
            <span class="help-block"></span>
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
