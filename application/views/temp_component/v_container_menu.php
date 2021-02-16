<header class="shop_header_area">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#"><img src="<?php echo base_url();?>assets/fo/img/logo.png" alt=""></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <div class="col-6">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown submenu active">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Kategori Produk <i class="fa fa-angle-down" aria-hidden="true"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <?php
                                    $kategori = $this->db->from('m_kategori')->where(['deleted_at' => null])->order_by('nama', 'ASC')->get();

                                    foreach ($kategori->result() as $key => $value) {
                                        echo '<li class="nav-item"><a class="nav-link" href="'.base_url('produk/kategori/').trim(strtolower($value->nama)).'">'.$value->nama.'</a></li>';
                                    }
                                ?>
                            </ul>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="#">Blog</a></li>
                        <li class="nav-item"><a class="nav-link" href="contact.html">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <select name="carikat" id="carikat" class="form-control">
                            <option value="all">Semua Kategori</option>
                            <?php foreach ($kategori->result() as $key => $value) { ?>
                            <option value="<?=trim(strtolower($value->nama));?>"><?= $value->nama; ?></option>
                            <?php } ?>
                        </select>
                        <input type="text" class="form-control" placeholder="Search" aria-label="Search">
                        <span class="input-group-btn">
                        <button class="btn btn-secondary" type="button"><i class="icon-magnifier"></i></button>
                        </span>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</header>