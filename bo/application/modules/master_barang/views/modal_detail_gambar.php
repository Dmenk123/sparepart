
<style>
    .mySlides {
  display: none;
  text-align:center;
}

.cursor {
  cursor: pointer;
}

/* Next & previous buttons */
.prev-gambar,
.next-gambar {
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
.next-gambar {
  right: 0;
  border-radius: 3px 0 0 3px;
}

/* On hover, add a black background color with a little bit see-through */
.prev-gambar:hover,
.next-gambar:hover {
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
.column {
  float: left;
  width: 25%;
}
</style>
<div class="modal fade modal_add_form" tabindex="-2" role="dialog" aria-labelledby="add_menu" aria-hidden="true" id="modal_detail_gambar">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal_title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        </button>
      </div>
      <div class="modal-body" id="modal_body">
        
      </div>
     
    </div>
  </div>
</div>
