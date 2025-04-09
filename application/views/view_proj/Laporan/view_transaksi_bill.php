<!-- start view_transaksi -->
<style type="text/css">
@media print {
	body {
		font-family: 'times new roman';
	}
	#previews {
		margin-top:.3cm;
	}


table#list_data tr th {
		border:1.5px solid black !important;
		font-size:10pt;
	}

table#list_data tbody tr td {
		border:1.5px solid black !important;
		font-size:10pt;
	}
}


}

</style>
<script type='text/javascript' src="<?php echo base_url();?>assets/plugins/nominal_js/nominal.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		/*window.print();*/
	});

	function printSuratJalan(tipe){

		var url = location.href;
		if ( tipe == 1 ) {
			url = url.replace('?is_surat_jalan_ekspedisi=t','');
				url = url.replace('?isrekap=t','');
			url = url.replace('?isrekap=f','');
			window.location.replace(url+"?is_surat_jalan=t");
		} else if ( tipe == 3 ) {
			url = url.replace('?is_surat_jalan=t','');
				url = url.replace('?isrekap=t','');
			url = url.replace('?isrekap=f','');
			window.location.replace(url+"?is_surat_jalan_ekspedisi=t");
		}else {
			url = url.replace('?is_surat_jalan=t','');
			url = url.replace('?is_surat_jalan_ekspedisi=t','');
			url = url.replace('?isrekap=t','');
			url = url.replace('?isrekap=f','');
			window.location.replace(url);
		}
		
	}

	function setData(obj){
		var url = location.href;

		var match_param1 = url.match(/is_surat_jalan/g);
		var match_param2 = url.match(/is_surat_jalan_ekspedisi/g);

		if ( match_param1 == null && match_param2 == null ) {
			url = url.replace('?isrekap=t','');
			url = url.replace('?isrekap=f','');
			var is_rek = $("select[name='isrekap'] option:selected").val();

			window.location.replace(url+'?isrekap='+is_rek);
		}
	}

	function printMobile(){
		var prn = document.getElementById('previews').innerHTML;
		var S = "#Intent;scheme=rawbt;";
	        var P =  "package=ru.a402d.rawbtprinter;end;";
	        var textEncoded = encodeURI(prn);
	        window.location.href="intent:"+textEncoded+S+P;
	}

	function sendUrlToPrint(url){
	    var  beforeUrl = 'intent:';
	    var  afterUrl = '#Intent;';
	    // Intent call with component
	    afterUrl += 'component=ru.a402d.rawbtprinter.activity.PrintDownloadActivity;'
	    afterUrl += 'package=ru.a402d.rawbtprinter;end;';
	    document.location=beforeUrl+encodeURI(url)+afterUrl;
	    return false;
	}
</script>
<div id="functions">
        <ul style="float:left">
                <li><a href="#" onclick="window.print();return false;">Print</a></li>
                <li><a href="#" onclick="JavaScript:window.close();">Tutup</a></li>
                <li><a href="#" onclick="printSuratJalan(1);return false;">Surat Jalan</a></li>
                <li><a href="#" onclick="printSuratJalan(3);return false;">Surat Jalan Expedisi</a></li>
                <li><a href="#" onclick="printSuratJalan(2);return false;">Penjualan</a></li>
                <!-- <li><a href="#" onclick="printMobile();return false;">Print Ke Mobile</a></li> -->
        </ul>
        <span style="float:right;margin-right: 30px;" >
        	Tampilkan Data
        	<?php 

        		$is_rekap_t = $is_rekap_f = "";

        		if ($is_rekap == 't' ) {
        			$is_rekap_t = "selected='selected'";
        		}

        		if ($is_rekap == 'f' ) {
        			$is_rekap_f = "selected='selected'";
        		}



        	?>
        	<select name="isrekap" id="isrekap" style="padding:5px;font-size:10pt" onchange="setData(this);">
        		<option value='f' <?php echo $is_rekap_f; ?> >Detail</option>
        		<option value='t' <?php echo $is_rekap_t; ?> >Rekap</option>
        	</select>
        </span>
</div>

<div id='previews'>
	<table width='100%' class='pad' style="font-size:10pt !important">
		<tr>
			<td align='left' style="vertical-align: middle" width="6%">
					<img src="<?php echo base_url();?>assets/images/warung_oksi_menu.png" width='75'>
			</td>
			<td align='left' width="60%" style="vertical-align: middle;letter-spacing: 1.2">
				Jual Beli bahan kaos Grosir & Eceran Cotton, TC, PE, Hyget, Dll <br> <hr style="border:1.4px solid black;"> Jl. Pameuntasan No.17 Rt. 01 Rw.03 Ds. Pameuntasan <br> Kec. Kutawaringin, Bandung <br> Telp. (022) 85870975, 
				Fax. (022) 85870975
			</td>
			<td align='right' style="vertical-align: top;">
				<table width="100%">
					<tr>
						<td width="35%">Bandung</td>
						<td><?php echo date('d M Y',strtotime($list_header->bill_date)); ?></td>
					</tr>
					<tr>
						<td width="35%">Kpd Yth,</td>
						<td><?php echo $list_header->nama_konsumen; ?></td>
					</tr>
					<tr>
						<td width="35%">Alamat,</td>
						<td><?php echo $list_header->alamat_konsumen; ?></td>
					</tr>
					<tr>
						<td width="35%">No Faktur,</td>
						<td><?php echo str_pad($list_header->tbmid,5,'0',STR_PAD_LEFT) ?></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
	<br>
	<table width='100%' class='pad' id="list_data">
			<tr>
				<th colspan="2">Qty</th>
				<th rowspan="2">Item</th>
				<th rowspan="2" width="10%">Harga (Kg)</th>
				<th rowspan="2" width="12%">Tot. Sblm Diskon</th>
				<th rowspan="2" width="12%">Diskon (Kg)</th>
				<th rowspan="2" width="12%">Diskon Total</th>
				<th rowspan="2" width='12%'>Tot. Stlh Diskon</th>
			</tr>
			<tr>
				<th width="5%">ROLL</th>
				<th width="5%">KG</th>
			</tr>
		
		<tbody>
			<?php 
				foreach ($list_detail->result_array() as $key => $value) {

					if ( $is_rekap == 'f' ) {

						$info_roll = $info_kg = "";

						if ( $value['tsid'] == $value['tsid_stok'] ) {
							$info_roll = (fmod($value['qty'],1) !== 0.00) ? $value['qty'] : round($value['qty']);
						}else{
							$info_kg   = (fmod($value['qty'],1) !== 0.00) ? $value['qty'] : round($value['qty']);
						}

						echo "<Tr>";
						//echo "<td colspan='2'>".((fmod($value['qty'],1) !== 0.00) ? $value['qty'] : round($value['qty']))."x ".$value['nama_satuan_trans']."</td>";
						echo "<td align='center'>".$info_roll."</td>";
						echo "<td align='center'>".$info_kg."</td>";
						echo "<td>".$value['nama_barang']."</td>";
						echo "<td align='right' class='row_nominal'>".$value['harga_jual']."</td>";
						echo "<td align='right' class='row_nominal'>".($value['harga_satuan']*$value['qty'])."</td>";
						echo "<td align='right' class='row_nominal'>".$value['discount_amount']."</td>";
						echo "<td align='right' class='row_nominal'>".($value['discount_amount']*$value['qty']*$value['isikeciljual'])."</td>";
						echo "<td align='right' class='row_nominal'>".$value['amount_total']."</td>";
						echo "</Tr>";

					} else {

						$row_satuan = $value['satuan_list'];
						$array_satuan = explode(",",$row_satuan);

						$detail_satuan = "";
						$first_satuan = 0;
						$info_kg_roll = 0;
						foreach ($array_satuan as $k => $v) {
							
							$persatuan = explode(":",$v);
							
							for($no_awal=0;$no_awal<intval($persatuan[0]);$no_awal++){

								if ( $first_satuan == 0 ) $koma = "";
								else $koma = ", ";

								$detail_satuan .= $koma.$persatuan[1];
								$info_kg_roll += $persatuan[1];

								$first_satuan = 1;
							}

						}

						$list_sat = ($value['satuan_list'] != "" ) ? " <br><font style='color:#414141'>[ ".$detail_satuan." ]</font>" : "";

						$info_roll = $info_kg = "";

						if ( $value['nama_satuan_trans'] == 'ROLL') {
							$info_roll = (fmod($value['qty'],1) !== 0.00) ? $value['qty'] : round($value['qty']);
							$info_kg  = $info_kg_roll;
						}else{
							$info_kg  = (fmod($value['qty'],1) !== 0.00) ? $value['qty'] : round($value['qty']);
						}

						echo "<Tr>";
						//echo "<td colspan='2'>".()." ".$value['nama_satuan_trans']."</td>";
						echo "<td align='center'>".$info_roll."</td>";
						echo "<td align='center'>".$info_kg."</td>";
						echo "<td>".$value['nama_barang']." ".$list_sat."</td>";
						echo "<td align='right' class='row_nominal'>".$value['harga_jual']."</td>";
						echo "<td align='right' class='row_nominal'>".($value['harga_satuan'])."</td>";
						echo "<td align='right' class='row_nominal'>".$value['discount_amount']."</td>";
						echo "<td align='right' class='row_nominal'>".($value['discount_total'])."</td>";
						echo "<td align='right' class='row_nominal'>".$value['amount_total']."</td>";
						echo "</Tr>";

					}
				}
			?>

			<tr>
				<td colspan="7" align="left" style="vertical-align: middle">

					<font style='float:right'>Total</font>	
				</td>
				<td align='right'  class='row_nominal' style="vertical-align: middle"><?php echo $list_header->total_tagihan?></td>
			</tr>
			<tr>
				<td colspan="7" align="left" style="vertical-align: middle">

					<font style='float:right'>Ongkir</font>	
				</td>
				<td align='right'  class='row_nominal' style="vertical-align: middle"><?php echo $list_header->ongkir_bill?></td>
			</tr>
			<tr>
				<td colspan="7" align="left" style="vertical-align: middle">

					<font style='float:right'>Total Tagihan</font>	
				</td>
				<td align='right'  class='row_nominal' style="vertical-align: middle"><?php echo $list_header->ongkir_bill + $list_header->total_tagihan;?></td>
			</tr>
			<tr>
				<td colspan="7" align="left" style="vertical-align: middle">

					<font style='float:right'>Total Dibayar</font>	
				</td>
				<td align='right'  class='row_nominal' style="vertical-align: middle"><?php echo $list_header->amount_bayar_partial;?></td>
			</tr>
			<tr>
				<td colspan="7" align="left" style="vertical-align: middle">

					<font style='float:right'>Sisa Tagihan</font>	
				</td>
				<td align='right'  class='row_nominal' style="vertical-align: middle"><?php echo $list_header->ongkir_bill + $list_header->total_tagihan - $list_header->amount_bayar_partial;?></td>
			</tr>
			<!-- <tr>
				<td colspan="5" align="left" style="border:none !important">
					<b>Kasir : <?php echo $list_header->user_bayar; ?></b>
					<font style='float:right'>Diskon</font>	
				</td>
				<td align='right' style="border:none !important" class='row_nominal'><?php echo $list_header->total_diskon?></td>
			</tr>
			<tr>
				<td colspan="5" align="left" style="border:none !important">
					<b>Payment Method :</b>
					<font style='float:right;'>PPN (<?php echo $list_header->ppn_persen?>%)</font>	
				</td>
				<td align='right' style="border:none !important" class='row_nominal'><?php echo $list_header->ppn_rp?></td>
			</tr>
			<tr>
				<td colspan="5" align="left" style="border:none !important">
					<font style='font-style:italic;'><?php echo $list_header->cara_bayar; ?> <?php echo $list_header->nama_edc; ?> </font>
					<?php if ( $list_header->int_cara_bayar == '2' OR $list_header->int_cara_bayar == '3'  ) { 

						echo "(No.CC : ".$list_header->no_kartu." , No.Batch : ".$list_header->no_batch.")";
					}
					?>
					<font style='float:right' >Total</font>	
				</td>
				<td align='right' style="border:none !important" class='row_nominal'><?php echo $list_header->total_amount?></td>
			</tr>
			<tr>
				<td colspan="5" align="left" style="border:none !important">
					&nbsp;
					<font style='float:right' >Total Bayar</font>	
				</td>
				<td align='right' style="border:none !important" class='row_nominal'><?php echo $list_header->total_bayar_pasien?></td>
			</tr>

			<tr>
				<td colspan="5" align="left" style="border:none !important">
					<?php if ($list_header->cara_bayar == 'CASH'){

						echo "<font style='float:right' >Kembalian</font>	";

					}?>
					
				</td>
				<td align='right' style="border:none !important" class='row_nominal'>
						<?php if ($list_header->cara_bayar == 'CASH'){

						echo $list_header->kembalian;

						}?>
				</td>
			</tr> -->

		</tbody>
	</table>

	<table width="100%" class="pad">
		<tr>
			<td align='center' width="33.3%"> Tanda Terima </td>
			<td align='center' width="33.3%" rowspan="4" style="vertical-align: middle">
				<div style="padding:5px;font-size:9pt">
					<ul>
						<li>Perhatian !!!</li>
						<li>Barang yang sudah dibeli tidak dapat ditukar / </li>
						<li>dikembalikan kecuali ada perjanjian</li>
					</ul>
				</div>
			</td>
			<td align='center' width="33.3%"> Hormat Kami</td>
		</tr>

		<tr>
			<td align='center' width="33.3%"> &nbsp; </td>
			<td align='center' width="33.3%"> &nbsp; </td>
		</tr>

		<tr>
			<td align='center' width="33.3%"> &nbsp; </td>
			<td align='center' width="33.3%"> &nbsp;</td>
		</tr>
		<tr>
			<td align='center' width="33.3%"><div style="width:70%"><font style="float:left">(</font><font style="float:right">)</font></div></td>
			<td align='center' width="33.3%"><div style="width:70%"><font style="float:left">(</font><font style="float:right">)</font></div></td>
		</tr>
	</table>
</div>
<a href="#" onclick="return sendUrlToPrint('https://dnntextile.transdisys.com/brosur5.pdf');">cetak pdf</a>
<script type="text/javascript">

number_format = function (number, decimals, dec_point, thousands_sep) {
        number = number.toFixed(decimals);

        var nstr = number.toString();
        nstr += '';
        x = nstr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? dec_point + x[1] : '';
        var rgx = /(\d+)(\d{3})/;

        while (rgx.test(x1))
            x1 = x1.replace(rgx, '$1' + thousands_sep + '$2');

        return x1 + x2;
    }


$(document).ready(function(){
	$.each($('.row_nominal'),function(k,v){

		nilai = this.innerHTML;

		//this.innerHTML = formatRupiah("'"+nilai+"'","Rp. ");
		this.innerHTML = number_format(parseFloat(nilai),2,',','.');

	});
});
</script>
<!-- end view_transaksi -->