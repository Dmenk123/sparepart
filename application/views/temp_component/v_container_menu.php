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
                    <li class="nav-item"><a class="nav-link" href="<?=base_url('home');?>">Home</a></li>
                        <li class="nav-item dropdown submenu active">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Kategori Produk <i class="fa fa-angle-down" aria-hidden="true"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <?php
                                    $kategori = $this->db->from('m_kategori')->order_by('nama_kategori', 'ASC')->get();

                                    foreach ($kategori->result() as $key => $value) {
                                        echo '<li class="nav-item"><a class="nav-link" href="'.base_url('produk/kategori?kat=').trim(strtolower(str_ireplace(' ', '-', $value->nama_kategori))).'">'.$value->nama_kategori.'</a></li>';
                                    }
                                ?>
                            </ul>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="contact.html">Kontak Kami</a></li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <form method="get" action="<?= base_url('produk/kategori?'); ?>">
                        <div class="input-group">
                            <select name="kat" id="kat" class="form-control">
                                <option value="all">Semua Kategori</option>
                                <?php foreach ($kategori->result() as $key => $value) { ?>
                                <option value="<?=trim(strtolower(str_ireplace(' ', '-', $value->nama_kategori)));?>"><?= $value->nama_kategori; ?></option>
                                <?php } ?>
                            </select>
                            <input type="text" name="q" class="form-control" placeholder="Cari disini ..." aria-label="Search">
                            <span class="input-group-btn">
                            <button style="cursor:pointer;" class="btn btn-secondary" type="submit"><i class="icon-magnifier"></i></button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </nav>
    </div>
</header>