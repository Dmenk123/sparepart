<section class="related_product_area">
    <div class="container">
        <div class="related_product_inner">
            <h2 class="single_c_title">Related Product</h2>
            <div class="row" id="related_area">
                <?php foreach ($results as $key => $value) { ?>
                    <div class="col-lg-3 col-sm-6">
                        <div class="l_product_item">
                            <div class="l_p_img">
                                <img class="img-fluid" src="<?php echo base_url();?>bo/files/img/barang_img/resize_image/<?= $value->gambar;?>" alt="">
                            </div>
                            <div class="l_p_text">
                                <ul>
                                    <li><a class="add_cart_btn" href="#">Lihat Detail</a></li>
                                </ul>
                                <h4><?= $value->nama;?></h4>
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
</section>

<script>
  
    function getPaging(elem) {
        let page = ($(elem).attr('data-ci-pagination-page'));
        
        $.ajax({
            type: "get",
            url: baseUrl+"home/get_temp_related",
            data: {page:page},
            dataType: "json",
            success: function (response) {
                if(response.status) {
                    $('div#related_area').html(response.html);
                    $('ul.pagination').html(response.links);
                }
            }
        });
    }

</script>