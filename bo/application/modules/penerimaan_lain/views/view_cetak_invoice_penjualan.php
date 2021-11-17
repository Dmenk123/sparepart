<html>
<head>
  <title>Cetak Invoice</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
</head>
<style>
@media print {
    html, body {
        display: block; 
        font-family: "Courier New";
        margin: 0!important;
        padding-top:-20px!important;
    }

    @page {
      size: 28.59cm 13.97cm;
    }

    .logo {
      width: 30%;
    }


}

hr {
        border-top: 1px dotted;
        margin-top: 5px;!important;
        margin-bottom: 5px;!important;
    }
</style>
<body style="margin:40px; 40px;">
    <h2 style="text-align:center;">Nota Penjualan</h2>
    <div class="row col-sm-12">
        <div class="col-sm-6">
            <p>
                PT. KARUNIA BINTANG ABADI
                <br>
                <br>
                SURABAYA
                <br>
                Tel. , Fax,
                <table>
                    <tbody>
                        <tr>
                            <td>Kepada</td>
                            <td>:</td>
                            <td>DS RACING STORE SURABAYA</td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>:</td>
                            <td>JL. EMBONG MALANG NO.35 SURABAYA</td>
                        </tr>
                    </tbody>
                </table>
            </p>
        </div>
        <div class="col-sm-6">
            <p style="margin-left:250px;">
                SURABAYA, 21-12-2020
                <br>
                <br>
                <br>
            </p>
                <table style="margin-left:210px;">
                    <tbody >
                        <tr>
                            <td>No. Faktur</td>
                            <td>:</td>
                            <td>AR-L21061</td>
                        </tr>
                        <tr>
                            <td>Jatuh Tempo</td>
                            <td>:</td>
                            <td>21-12-2020</td>
                        </tr>
                        <tr>
                            <td>Nama Sales</td>
                            <td>:</td>
                            <td>ALSYAFINOLAH</td>
                        </tr>
                    </tbody>
                </table>
        </div>
    </div>
    <div>
        <hr class="new3">
        <hr class="new3" style="padding-top:-10px;">
        <table width="100%">
            <thead>
                <tr>
                    <td>No.</td>
                    <td>Nama Barang</td>
                    <td>Quantity</td>
                    <td>Harga</td>
                    <td>Dsc</td>
                    <td>Sub Total</td>
                </tr>
            </thead>
        </table>
        <hr class="new3">
        <table width="100%">
            <tbody>
                <?php
                    $no = 0;
                    foreach ($invoice as $key => $value) { $no++;?>
                        <tr>
                            <td><?php echo $no;?></td>
                            <td><?php echo $value->sku;?></td>
                            <td><?php echo $value->nama;?></td>
                            <td><?php echo $value->qty;?></td>
                            <td><?php echo $value->harga_awal;?></td>
                            <td><?php echo $value->besaran_diskon;?></td>
                            <td><?php echo $value->sub_total;?></td>
                        </tr>
                <?php    }
                ?>
            </tbody>
        </table>
 
    </div>
</body>

</html>