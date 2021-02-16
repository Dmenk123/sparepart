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


	<!--================Content Area =================-->
	
	<?php if(isset($content)) { 
		foreach ($content as $k => $v) {
			$this->load->view($v);
		}
	}?>
	<!--================End Content Area =================-->
    

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