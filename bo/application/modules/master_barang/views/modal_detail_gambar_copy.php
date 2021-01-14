<style>
* {
  box-sizing: border-box;
}

.row > .column {
  padding: 0 8px;
}

.row:after {
  content: "";
  display: table;
  clear: both;
}

.column {
  float: left;
  width: 25%;
}
    .modal-gambar {
  display: none;
  position: fixed;
  align: center;
  z-index: 9999;
  padding-top: 100px;
  left: 0;
  top: 0;
  width: 60%;
  height: 60%;
  overflow: auto;
  background-color: black;
}

/* Modal Content */
.modal-gambar-content {
  position: relative;
  background-color: #fefefe;
  margin: auto;
  padding: 0;
  width: 90%;
  max-width: 900px;
}

/* The Close Button */
.close {
  color: white;
  position: absolute;
  top: 10px;
  right: 25px;
  font-size: 35px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #999;
  text-decoration: none;
  cursor: pointer;
}

.mySlides {
  display: none;
}

.cursor {
  cursor: pointer;
}

/* Next & previous buttons */
.prev,
.next {
  cursor: pointer;
  position: absolute;
  top: 50%;
  width: auto;
  padding: 16px;
  margin-top: -50px;
  color: white;
  font-weight: bold;
  font-size: 20px;
  transition: 0.6s ease;
  border-radius: 0 3px 3px 0;
  user-select: none;
  -webkit-user-select: none;
}

/* Position the "next button" to the right */
.next {
  right: 0;
  border-radius: 3px 0 0 3px;
}

/* On hover, add a black background color with a little bit see-through */
.prev:hover,
.next:hover {
  background-color: rgba(0, 0, 0, 0.8);
}

/* Number text (1/3 etc) */
.numbertext {
  color: #f2f2f2;
  font-size: 12px;
  padding: 8px 12px;
  position: absolute;
  top: 0;
}

img {
  margin-bottom: -4px;
}

.caption-container {
  text-align: center;
  background-color: black;
  padding: 2px 16px;
  color: white;
}

.demo {
  opacity: 0.6;
}

.active,
.demo:hover {
  opacity: 1;
}

img.hover-shadow {
  transition: 0.3s;
}

.hover-shadow:hover {
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
}
</style>
<div id="modal_detail_gambar" class="modal-gambar">
  <span class="close cursor" onclick="closeModal()">&times;</span>
  <div class="modal-gambar-content" id="content">

    <div class="mySlides">
      <div class="numbertext">1 / 4</div>
      <img src="<?php echo base_url();?>files/img/barang_img/coba-1604410559.jpg" style="width:100%">
    </div>

    <div class="mySlides">
      <div class="numbertext">2 / 4</div>
      <img src="<?php echo base_url();?>files/img/barang_img/coba-1602775328.jpg" style="width:100%">
    </div>

    <div class="mySlides">
      <div class="numbertext">3 / 4</div>
      <img src="<?php echo base_url();?>files/img/barang_img/coba-1604410559.jpg" style="width:100%">
    </div>
    
    <div class="mySlides">
      <div class="numbertext">4 / 4</div>
      <img src="<?php echo base_url();?>files/img/barang_img/coba-1602775328.jpg" style="width:100%">
    </div>
    
    <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
    <a class="next" onclick="plusSlides(1)">&#10095;</a>

    <div class="caption-container">
      <p id="caption"></p>
    </div>


    <div cass="row">
        <div class="column col-sm-3">
        <img class="demo cursor" src="<?php echo base_url();?>files/img/barang_img/coba-1604410559.jpg" style="width:100%" onclick="currentSlide(1)" alt="Nature and sunrise">
        </div>
        <div class="column col-sm-3">
        <img class="demo cursor" src="<?php echo base_url();?>files/img/barang_img/coba-1602775328.jpg" style="width:100%" onclick="currentSlide(2)" alt="Snow">
        </div>
        <div class="column col-sm-3">
        <img class="demo cursor" src="<?php echo base_url();?>files/img/barang_img/coba-1604410559.jpg" style="width:100%" onclick="currentSlide(3)" alt="Mountains and fjords">
        </div>
        <div class="column col-sm-3">
        <img class="demo cursor" src="<?php echo base_url();?>files/img/barang_img/coba-1602775328.jpg" style="width:100%" onclick="currentSlide(4)" alt="Northern Lights">
        </div>
    </div>
  </div>
</div>