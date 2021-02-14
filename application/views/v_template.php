<!DOCTYPE html>
<html lang="en">
    <!-- header -->
    <?php $this->load->view('temp_component/v_header'); ?>
<body>
	<!-- Preloader Starts -->
    <!-- <div class="preloader" id="preloader">
        <div class="logopreloader">
            <img src="<?= base_url('assets/images/saras.png'); ?>" alt="logo-white">
        </div>
        <div class="loader" id="loader"></div>
	</div> -->

	<!--================Top Header Area =================-->
	<?php if(isset($container_header)) { $this->load->view($container_header); }?>
    <!--================End Top Header Area =================-->
    
	<!--================Menu Area =================-->
	<?php if(isset($container_menu)) { $this->load->view($container_menu); }?>
	<!--================End Menu Area =================-->
    
	<!--================Slider Area =================-->
	<?php if(isset($container_slider)) { $this->load->view($container_slider); }?>
	<!--================End Slider Area =================-->

	<!--================Feature Area =================-->
	<?php if(isset($container_feature)) { $this->load->view($container_feature); }?>
	<!--================End Feature Area =================-->

	<!--================latest Area =================-->
	<?php if(isset($container_latest)) { $this->load->view($container_latest); }?>
	<!--================End latest Area =================-->

	<!--================Feature Big Add Area =================-->
	<?php if(isset($container_adv_big)) { $this->load->view($container_adv_big); }?>
	<!--================End latest Area =================-->

	<!--================Product_listing Area =================-->
	<?php if(isset($container_product_listing)) { $this->load->view($container_product_listing); }?>
	<!--================End Product listing Area =================-->

	<!--================Product_listing Area =================-->
	<?php if(isset($container_product_related)) { $this->load->view($container_product_related); }?>
	<!--================End Product listing Area =================-->

	<!--================Form Blog Area =================-->
	<?php if(isset($container_blog)) { $this->load->view($container_blog); }?>
	<!--================End blog Area =================-->

    <!-- load modal per module -->
	<?php if(isset($modal)) { $this->load->view($modal); }?>
	
	<!--================ Footer Area =================-->
	<?php $this->load->view('temp_component/v_footer'); ?>


    <!-- load js per modul -->
	<?php if(isset($js)) { ?>
        <?php if(is_array($js)){ ?>
        <?php foreach ($js as $keys => $values) { ?>
        	<script src="<?= base_url('assets/js_module/'.$values); ?>" type="text/javascript"></script>
        <?php } ?>
        <?php }else{ ?>
        	<script src="<?= base_url('assets/js_module/'.$js); ?>" type="text/javascript"></script>
        <?php } ?> 
	<?php } ?>
    
</body>

</html>