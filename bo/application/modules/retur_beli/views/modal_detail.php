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
              <tr>
                <th>Jenis Retur</th>
                <td><span id="span_jenis_det"></span></td>
              </tr>
              <tr>
                <th>Supplier</th>
                <td><span id="span_supplier_det"></span></td>
              </tr>
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
                  <th>Gudang</th>
                  <th>Harga</th>
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