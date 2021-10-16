
<div class="modal fade modal_add_form" tabindex="-1" role="dialog" aria-labelledby="add_menu" aria-hidden="true" id="modal_stok_form">
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
            <div class="form-group col-sm-12">
              <input type="hidden" class="form-control" id="id_stok" name="id_stok">
              <label for="lbl_username" class="form-control-label">Nama Barang:</label>
              <select name="id_barang" id="id_barang" class="form-control select2">
                  <option value="0">-PILIH-</option>
                  <?php foreach($barang as $row):?>
                      <option value="<?php echo $row->id_barang;?>"><?php echo $row->nama;?> | <?php echo $row->sku;?></option>
                  <?php endforeach;?>
              </select>
              <span class="help-block"></span>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-sm-12">
              <label for="lbl_username" class="form-control-label">Gudang:</label>
              <select name="id_gudang" id="id_gudang" class="form-control select2">
                  <option value="0">-PILIH-</option>
                  <?php foreach($gudang as $row):?>
                      <option value="<?php echo $row->id_gudang;?>"><?php echo $row->nama_gudang;?></option>
                  <?php endforeach;?>
              </select>
              <span class="help-block"></span>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-sm-4">
              <label for="lbl_kategori" class="form-control-label">Stok Awal :</label>
              <input type="number" class="form-control" id="sawal" name="sawal" autocomplete="off">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-4">
              <label for="lbl_kategori" class="form-control-label">Stok Min :</label>
              <input type="number" class="form-control" id="smin" name="smin" autocomplete="off">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-4">
              <label for="lbl_kategori" class="form-control-label">HPP Stok Awal :</label>
              <input type="text" class="form-control uang" id="hpp" name="hpp" autocomplete="off">
              <span class="help-block"></span>
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
