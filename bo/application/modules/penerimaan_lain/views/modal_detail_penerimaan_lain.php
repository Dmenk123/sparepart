<div class="modal fade modal_add_form" tabindex="-1" role="dialog" aria-labelledby="add_menu" aria-hidden="true" id="modal_det_trans">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal_title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        </button>
      </div>
      <div class="modal-body">
        <div class="row invoice-info">
          <div class="col-sm-6 invoice-col">
            <table class="table table-borderless">
              <tr>
                <th>Tanggal</th>
                <td><span id="span_tgl_det"></span></td>
              </tr>
              <tr>
                <th>Kode</th>
                <td><span id="span_kode_det"></span></td>
              </tr>
            </table>
          </div><!-- /.col -->
          <div class="col-sm-6 invoice-col">
            <table class="table table-borderless">
              <tr>
                <th>Petugas</th>
                <td><span id="span_petugas_det"></span></td>
              </tr>
              <!-- <tr>
                  <th>Petugas</th>
                  <td><span id="span_petugas_det"></span></td>
                </tr> -->
            </table>
          </div><!-- /.col -->
        </div>
        <div class="row invoice-info">
          <div class="col-sm-12 invoice-col">
            <table class="table" id="tbl_konten_detail">
              <thead class="thead-light">
                <tr>
                  <th>Qty</th>
                  <th>Nama</th>
                  <th>Keterangan</th>
                  <th>Nilai</th>
                  <th>Sub Total</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div><!-- /.col -->
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
      </div>
    </div>
  </div>
</div>
<?php if ($this->uri->segment(2) == 'add_penerimaan_det') { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="add_menu" aria-hidden="true" id="modal_frame">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal_title"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          </button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <iframe src="<?= base_url('master_barang'); ?>?showmenu=false" width="100%" height="480" frameborder="0" allowtransparency="true"></iframe>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
        </div>
      </div>
    </div>
  </div>
<?php } ?>