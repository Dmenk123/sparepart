<div class="modal fade modal_add_form" tabindex="-1" role="dialog" aria-labelledby="add_menu" aria-hidden="true" id="modal_det_jual">
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
                <th>Faktur</th>
                <td><span id="span_faktur"></span></td>
              </tr>
              <tr>
                <th>Tanggal Penjualan</th>
                <td><span id="span_tgl_jual"></span></td>
              </tr>
              <tr>
                <th>Pelanggan</th>
                <td><span id="span_pelanggan"></span></td>
              </tr>
            </table>
          </div><!-- /.col -->
          <div class="col-sm-6 invoice-col">
            <table class="table table-borderless">
              <tr>
                <th>Sales</th>
                <td><span id="span_sales"></span></td>
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
                  <th>Product</th>
                  <th>Price</th>
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
          <iframe src="<?= base_url('master_barang'); ?>" width="100%" height="480" frameborder="0" allowtransparency="true"></iframe>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
      </div>
    </div>
  </div>
</div>

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
          <iframe src="<?= base_url('master_barang'); ?>" width="100%" height="480" frameborder="0" allowtransparency="true"></iframe>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
      </div>
    </div>
  </div>
</div>