<!--================Categories Product Area =================-->
<section class="categories_product_main p_80">
    <div class="container">
        <div class="categories_main_inner">
            <div class="row row_disable">
                <div class="col-lg-9 float-md-right">
                    <div class="showing_fillter">
                        <div class="row m0">
                            <div class="first_fillter">
                                <h4>Menampilkan 1 dari Total 30</h4>
                            </div>
                            <div class="secand_fillter">
                                <h4>Urut :</h4>
                                <select class="selectpicker" onchange="changeSort(this, getQueryStringValue)">
                                    <option value="snama" <?php if($this->input->get('sort') == 'snama') { echo 'selected'; }?>>Nama</option>
                                    <option value="snew" <?php if($this->input->get('sort') == 'snew') { echo 'selected'; }?>>Terbaru</option>
                                    <option value="sold" <?php if($this->input->get('sort') == 'sold') { echo 'selected'; }?>>Terlama</option>
                                    <option value="sminprice" <?php if($this->input->get('sort') == 'sminprice') { echo 'selected'; }?>>Harga Terendah</option>
                                    <option value="smaxprice" <?php if($this->input->get('sort') == 'smaxprice') { echo 'selected'; }?>>Harga Tertinggi</option>
                                </select>
                            </div>
                            <div class="third_fillter col-md-4">
                                <h4>Menampilkan : </h4>
                                <select class="selectpicker" onchange="changeTampil(this, getQueryStringValue)">
                                    <option value="9" <?php if($this->input->get('tampil') == '9') { echo 'selected'; }?>>9</option>
                                    <option value="12" <?php if($this->input->get('tampil') == '12') { echo 'selected'; }?>>12</option>
                                    <option value="15" <?php if($this->input->get('tampil') == '15') { echo 'selected'; }?>>15</option>
                                    <option value="18" <?php if($this->input->get('tampil') == '18') { echo 'selected'; }?>>18</option>
                                    <option value="24" <?php if($this->input->get('tampil') == '24') { echo 'selected'; }?>>24</option>
                                </select>
                            </div>
                            <!-- <div class="four_fillter">
                                <h4>View</h4>
                                <a class="active" href="#"><i class="icon_grid-2x2"></i></a>
                                <a href="#"><i class="icon_grid-3x3"></i></a>
                            </div> -->
                        </div>
                    </div>
                    <div class="categories_product_area">
                        <div class="row" id="produk_area">
                            <?php foreach ($results as $key => $value) { ?>
                            <div class="col-lg-4 col-sm-6">
                                <div class="l_product_item">
                                    <div class="l_p_img">
                                        <img src="<?php echo base_url();?>bo/files/img/barang_img/resize_image/<?= $value->gambar;?>" alt="">
                                        <!-- <h5 class="new"><?= $value->nama;?></h5> -->
                                    </div>
                                    <div class="l_p_text">
                                        <ul>
                                            <li><a class="add_cart_btn" href="<?= base_url('produk/produk_detail/'.$value->sku); ?>">Lihat Detail</a></li>
                                        </ul>
                                        <h4><?= $value->nama;?></h4>
                                        <!-- <h5><del>$45.50</del>  $40</h5> -->
                                        <h5><?= "Rp " . number_format($value->harga,0,',','.');?></h5>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>

                        <nav aria-label="Page navigation example" class="pagination_area">
                            <?php echo $links; ?>
                        </nav>
                    </div>
                </div>

                <div class="col-lg-3 float-md-right">
                    <div class="categories_sidebar">
                        <aside class="l_widgest l_p_categories_widget">
                            <div class="l_w_title">
                                <h3>Kategori</h3>
                            </div>
                            <ul class="navbar-nav">
                                <!-- <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Women’s Fashion
                                    <i class="icon_plus" aria-hidden="true"></i>
                                    <i class="icon_minus-06" aria-hidden="true"></i>
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <li class="nav-item"><a class="nav-link" href="#">Hoodies & Sweatshirts</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#">Jackets & Coats</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#">Blouses & Shirts</a></li>
                                    </ul>
                                </li> -->
                                <?php
                                    $kategori = $this->db->from('m_kategori')->order_by('nama_kategori', 'ASC')->get();

                                    foreach ($kategori->result() as $key => $value) {
                                        echo '<li class="nav-item">
                                            <a class="nav-link" href="'.base_url('produk/kategori?kat=').trim(strtolower(str_ireplace(' ', '-', $value->nama_kategori))).'">'.$value->nama_kategori.'
                                                <i class="icon_plus" aria-hidden="true"></i>
                                            <i class="icon_minus-06" aria-hidden="true"></i>
                                            </a>
                                        </li>';
                                    }
                                ?>
                            </ul>
                        </aside>

                        <aside class="l_widgest l_fillter_widget">
                            <div class="l_w_title">
                                <h3>Filter Harga</h3>
                            </div>
                            <div id="slider-range" class="ui_slider ui-slider ui-corner-all ui-slider-horizontal ui-widget ui-widget-content"><div class="ui-slider-range ui-corner-all ui-widget-header" style="left: 0%; width: 100%;"></div><span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default" style="left: 0%;"></span><span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default" style="left: 100%;"></span></div>
                            <label for="amount">Harga:</label>
                            <input type="text" id="amount" readonly="">
                        </aside>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--================End Categories Product Area =================-->

<script>
  
    function getPaging(elem) {
        let page = ($(elem).attr('data-ci-pagination-page'));
        let tampil = "<?php echo $this->input->get('tampil');?>";
        let kat = "<?php echo $this->input->get('kat');?>";
        let sort = "<?php echo $this->input->get('sort');?>";
        $.ajax({
            type: "get",
            url: baseUrl+"produk/get_temp_produk_item",
            data: {page:page, tampil:tampil, kat:kat, sort:sort},
            dataType: "json",
            success: function (response) {
                if(response.status) {
                    $('div#produk_area').html(response.html);
                    $('ul.pagination').html(response.links);
                }
            }
        });
    }

    function changeSort(elem, cb) {
        let newStr = elem.value;
        let uriVal = cb('sort');
        let txtRep = 'sort='+uriVal;
        let txtRepNew = 'sort='+newStr;
        let fullUri = window.location.search;
        
        //console.log(fullUri);
        
        if(uriVal == '') {
            let uriKat = cb('kat');
            if(uriKat == '') {
                // jika uri terdapat tanda tanya
                if(fullUri.search(/\?/) >= 0){
                    window.location = baseUrl+"produk/kategori"+fullUri+'&'+txtRepNew;
                }else{
                    window.location = baseUrl+"produk/kategori"+fullUri+'?'+txtRepNew;
                }
            }else{
                window.location = baseUrl+"produk/kategori"+fullUri+'&'+txtRepNew;
            }
        }else{
            newStr = fullUri.replace(txtRep, txtRepNew);
            window.location = baseUrl+"produk/kategori"+newStr;    
        }
    }

    function changeTampil(elem, cb) {
        let newStr = elem.value;
        let uriVal = cb('tampil');
        let txtRep = 'tampil='+uriVal;
        let txtRepNew = 'tampil='+newStr;
        let fullUri = window.location.search;
        
        //console.log(fullUri);
        
        if(uriVal == '') {
            let uriKat = cb('kat');
            if(uriKat == '') {
                if(fullUri.search(/\?/) >= 0){
                    window.location = baseUrl+"produk/kategori"+fullUri+'&'+txtRepNew;
                }else{
                    window.location = baseUrl+"produk/kategori"+fullUri+'?'+txtRepNew;
                }
            }else{
                window.location = baseUrl+"produk/kategori"+fullUri+'&'+txtRepNew;
            }
        }else{
            newStr = fullUri.replace(txtRep, txtRepNew);
            window.location = baseUrl+"produk/kategori"+newStr;    
        }
    }
 
    function getQueryStringValue (key) {  
        return decodeURIComponent(window.location.search.replace(new RegExp("^(?:.*[&\\?]" + encodeURIComponent(key).replace(/[\.\+\*]/g, "\\$&") + "(?:\\=([^&]*))?)?.*$", "i"), "$1"));  
    }  

</script>