
<div class="modal fade modal_add_form" tabindex="-1" role="dialog" aria-labelledby="add_menu" aria-hidden="true" id="modal_log">
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
            <label for="lbl_username" class="form-control-label">Nama Barang:</label>
            <select name="id_barang" id="id_barang" class="form-control select2">
                <option value="">-PILIH-</option>
                <?php foreach($barang->result() as $row):?>
                    <option value="<?php echo $row->id_barang;?>"><?php echo $row->nama.' | '.$row->sku;?></option>
                <?php endforeach;?>
            </select>
            <span class="help-block"></span>
          </div>
          <div class="form-group">
            <label for="lbl_namabarang" class="form-control-label">Harga Jual :</label>
            <input type="text" class="form-control" id="harga_jual" name="harga_jual" autocomplete="off">
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
