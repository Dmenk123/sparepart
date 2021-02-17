<!--================Categories Product Area =================-->
<section class="categories_product_main p_80">
    <div class="container">
        <div class="categories_main_inner">
            <div class="row row_disable">
                <div class="col-lg-9 float-md-right">
                    <div class="showing_fillter">
                        <div class="row m0">
                            <div class="first_fillter">
                                <h4>Showing 1 to 12 of 30 total</h4>
                            </div>
                            <div class="secand_fillter">
                                <h4>SORT BY :</h4>
                                <select class="selectpicker">
                                    <option>Name</option>
                                    <option>Name 2</option>
                                    <option>Name 3</option>
                                </select>
                            </div>
                            <div class="third_fillter">
                                <h4>Show : </h4>
                                <select class="selectpicker">
                                    <option>09</option>
                                    <option>10</option>
                                    <option>10</option>
                                </select>
                            </div>
                            <div class="four_fillter">
                                <h4>View</h4>
                                <a class="active" href="#"><i class="icon_grid-2x2"></i></a>
                                <a href="#"><i class="icon_grid-3x3"></i></a>
                            </div>
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
                                        <h5><?= "Rp " . number_format($value->harga,2,',','.');?></h5>
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
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Men’s Fashion
                                        <i class="icon_plus" aria-hidden="true"></i>
                                    <i class="icon_minus-06" aria-hidden="true"></i>
                                    </a>
                                </li>
                                <li class="nav-item dropdown">
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
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Phone & Accessories 
                                        <i class="icon_plus" aria-hidden="true"></i>
                                    <i class="icon_minus-06" aria-hidden="true"></i>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Electronic Appliance
                                        <i class="icon_plus" aria-hidden="true"></i>
                                    <i class="icon_minus-06" aria-hidden="true"></i>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link disabled" href="#">Computer & Networking
                                        <i class="icon_plus" aria-hidden="true"></i>
                                        <i class="icon_minus-06" aria-hidden="true"></i>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link disabled" href="#">TV, Audiio & Gaming
                                        <i class="icon_plus" aria-hidden="true"></i>
                                        <i class="icon_minus-06" aria-hidden="true"></i>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link disabled" href="#">Office Supplies
                                        <i class="icon_plus" aria-hidden="true"></i>
                                        <i class="icon_minus-06" aria-hidden="true"></i>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link disabled" href="#">All Categories
                                        <i class="icon_plus" aria-hidden="true"></i>
                                        <i class="icon_minus-06" aria-hidden="true"></i>
                                    </a>
                                </li>
                            </ul>
                        </aside>

                        <!-- <aside class="l_widgest l_menufacture_widget">
                            <div class="l_w_title">
                                <h3>Manufacturer</h3>
                            </div>
                            <ul>
                                <li><a href="#">Nigel Cabourn.</a></li>
                                <li><a href="#">Cacharel.</a></li>
                                <li><a href="#">Calibre (Menswear)</a></li>
                                <li><a href="#">Calvin Klein.</a></li>
                                <li><a href="#">Camilla and Marc</a></li>
                            </ul>
                        </aside>
                        <aside class="l_widgest l_feature_widget">
                            <div class="l_w_title">
                                <h3>Featured Products</h3>
                            </div>
                            <div class="media">
                                <div class="d-flex">
                                    <img src="img/product/featured-product/f-p-5.jpg" alt="">
                                </div>
                                <div class="media-body">
                                    <h4>Jeans with <br /> Frayed Hems</h4>
                                    <h5>$45.05</h5>
                                </div>
                            </div>
                            <div class="media">
                                <div class="d-flex">
                                    <img src="img/product/featured-product/f-p-6.jpg" alt="">
                                </div>
                                <div class="media-body">
                                    <h4>Crysp Denim<br />Montana</h4>
                                    <h5>$45.05</h5>
                                </div>
                            </div>
                        </aside> -->

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
        let perPage = "<?php echo $this->input->get('per_page');?>";
        let kat = "<?php echo $this->input->get('kat');?>";
        let sortBy = "<?php echo $this->input->get('sortBy');?>";
        $.ajax({
            type: "get",
            url: baseUrl+"produk/get_temp_produk_item",
            data: {page:page, perPage:perPage, kat:kat, sortBy:sortBy},
            dataType: "json",
            success: function (response) {
                if(response.status) {
                    $('div#produk_area').html(response.html);
                    $('ul.pagination').html(response.links);
                }
            }
        });
    }

</script>