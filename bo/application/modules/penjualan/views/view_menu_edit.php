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
  margin-right:.3em;
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
        <div class="kt-portlet__head-toolbar" >
          <div class="kt-portlet__head-wrapper">
            <div class="row" style="text-align:left!important;">
              <h2>INVOICE</h>
            </div>
          </div>
        </div>
      </div>
      <div class="kt-portlet__body">

        <!--begin: Form Invoice -->
        <div class="page-content-inner">
        <section class="panel panel-with-borders">
            <div class="panel-heading">
                <div class="row">
                    <div class="step-block step-success">
                       <h3>Detail Data</h3>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <input type="hidden" name="mode" value="edit">
                        <div class="row" style="line-height:15px;">
                            <label class="col-lg-2 col-form-label text-left">Nomor Order</label>
                            <label class="col-lg-10 col-form-label text-left"><?php $value = (isset($invoice->order_id))?$invoice->order_id:""; echo $value;?></label>
                        </div>
                        <div class="row">
                            <label class="col-lg-2 col-form-label text-left">Nomor Faktur</label>
                            <label class="col-lg-10 col-form-label text-left"><?php $value = (isset($invoice->no_faktur))?$invoice->no_faktur:""; echo $value;?></label>
                        </div>
                        <div class="row">
                            <label class="col-lg-2 col-form-label text-left">Nama Toko</label>
                            <label class="col-lg-10 col-form-label text-left"><?php $value = (isset($invoice->nama_toko))?$invoice->nama_toko:""; echo $value;?></label>
                        </div>
                        <div class="row">
                            <label class="col-lg-2 col-form-label text-left">Alamat</label>
                            <label class="col-lg-10 col-form-label text-left"><?php $value = (isset($invoice->alamat))?$invoice->alamat:""; echo $value;?></label>
                        </div>
                        <div class="row">
                            <label class="col-lg-2 col-form-label text-left">Tgl Jatuh Tempo</label>
                            <label class="col-lg-10 col-form-label text-left"><?php $value = (isset($invoice->tgl_jatuh_tempo))?$invoice->tgl_jatuh_tempo:""; echo $value;?></label>
                        </div>
                        <div class="row">
                            <label class="col-lg-2 col-form-label text-left">Nama Sales</label>
                            <label class="col-lg-10 col-form-label text-left"><?php $value = (isset($invoice->username))?$invoice->username:""; echo $value;?></label>
                        </div>
                        
                        <div class="col-lg-10" style="text-align: left">
                            <button type="button" class="btn btn-success editkepala">Edit Data Invoice</button>
                            <button type="button" class="btn btn-success" onclick="editorder(<?php echo $invoice->order_id;?>)">Edit Order</button>
                            <button type="button" class="btn btn-danger" onclick="simpanedit()">Simpan Perubahan</button>
                        </div>
                        
                    </div>
                </div>
            </div>
        </section>
    </div>
    
       <!--end: Form Invoice -->
      </div>
    </div>
  </div>
  
</div>



