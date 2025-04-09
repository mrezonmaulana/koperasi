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

	table#list_data tfoot tr td {
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
				url = url.replace('&isrekap=t','');
			url = url.replace('&isrekap=f','');
			window.location.replace(url+"?is_surat_jalan=t");
		} else if ( tipe == 3 ) {
			url = url.replace('?is_surat_jalan=t','');
				url = url.replace('&isrekap=t','');
			url = url.replace('&isrekap=f','');
			window.location.replace(url+"?is_surat_jalan_ekspedisi=t");
		}else {
			url = url.replace('?is_surat_jalan=t','');
			url = url.replace('?is_surat_jalan_ekspedisi=t','');
			url = url.replace('&isrekap=t','');
			url = url.replace('&isrekap=f','');
			window.location.replace(url);
		}
		
	}
	function setMobil(){
		var nm_mobil = $("input[name='nm_mobil']").val();
		var nopol_mobil = $("input[name='nopol_mobil']").val();
		var tbmid = '<?php echo $list_header->tbmid;?>';

		$.post("<?php echo base_url();?>Laporan/update_mobil",{tbmid:tbmid,nm_mobil:nm_mobil,nopol_mobil:nopol_mobil},function(data){

			$("input[name='nm_mobil']").css('display','none');
			$("input[name='nopol_mobil']").css('display','none');
			$("font[id='text_nm_mobil']").html(nm_mobil).css('font-weight','bold');
			$("font[id='text_nopol_mobil']").html(nopol_mobil).css('font-weight','bold');
			window.print();
			$("input[name='nm_mobil']").css('display','');
			$("input[name='nopol_mobil']").css('display','');
			$("font[id='text_nm_mobil']").html('');
			$("font[id='text_nopol_mobil']").html('');
		});
	}

	function setData(obj){
		var url = location.href;

		var match_param1 = url.match(/is_surat_jalan/g);
		var match_param2 = url.match(/is_surat_jalan_ekspedisi/g);

		if ( match_param1 != null) {
			url = url.replace('&isrekap=t','');
			url = url.replace('&isrekap=f','');
			var is_rek = $("select[name='isrekap'] option:selected").val();

			window.location.replace(url+'&isrekap='+is_rek);
		}
	}
</script>
<div id="functions">
        <ul style="float:left">
                <li><a href="#" onclick="setMobil();return false;">Print</a></li>
                <li><a href="#" onclick="JavaScript:window.close();">Tutup</a></li>
                <li><a href="#" onclick="printSuratJalan(1);return false;">Surat Jalan</a></li>
                <li><a href="#" onclick="printSuratJalan(3);return false;">Surat Jalan Expedisi</a></li>
                <li><a href="#" onclick="printSuratJalan(2);return false;">Penjualan</a></li>
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
	<table width='100%' class='pad'>
		<tr>
			<td align='left' style="vertical-align: middle" width="6%">
					<img src="<?php echo base_url();?>assets/images/warung_oksi_menu.png" width='80'>
			</td>
			<td align='left' width="60%" style="vertical-align: middle;letter-spacing: 1.2">
				<font style="font-size:20pt">TEXTILE</font><br><br>Jl. Pameuntasan No.17 Rt. 01 Rw.03 Ds. Pameuntasan <br> Kec. Kutawaringin, Bandung <br> Telp. (022) 85870975, 
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
	<h2>SURAT JALAN</h2>
	<table width='100%' class='pad' id="list_data">
			<tr>
				<td colspan="3" style="border:none !important">
					Kami kirimkan barang-barang dibawah ini dengan Kendaraan <font id='text_nm_mobil'/></font><input type='text' name='nm_mobil' id='nm_mobil' style='text-align:left;padding-left:5px;' value='<?php echo $list_header->nm_mobil;?>'/> Nopol <font id='text_nopol_mobil'/></font><input type='text' name='nopol_mobil' id='nopol_mobil' style='text-align:left;padding-left:5px;' value='<?php echo $list_header->nopol_mobil;?>'/>
				</td>
			</tr>
			<tr style="font-size:7pt !important">
				<th colspan="2">Banyaknya</th>
				<th rowspan="2">Nama Barang</th>
			</tr>
			<tr>
				<th width="10%">ROLL</th>
				<Th width="10%">KG</Th>
			</tr>
		<tbody style="font-size:7pt !important">
			<?php 
				foreach ($list_detail->result_array() as $key => $value) {

					if ( $is_rekap == 't'){

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

						$list_sat = ($value['satuan_list'] != "" ) ? "<br><font style='color:#414141'>[ ".$detail_satuan." ]</font>" : "";

					}else{
						$list_sat= "";
					}


					$info_roll = $info_kg = "";

					if ( $is_rekap == 't' ) {

						if ( $value['nama_satuan_trans'] == 'ROLL') {
							$info_roll = (fmod($value['qty'],1) !== 0.00) ? $value['qty'] : round($value['qty']);
							$info_kg  = $info_kg_roll;
						}else{
							$info_kg  = (fmod($value['qty'],1) !== 0.00) ? $value['qty'] : round($value['qty']);
						}

					} else {

						if ( $value['tsid'] == $value['tsid_stok'] ) {
							$info_roll = (fmod($value['qty'],1) !== 0.00) ? $value['qty'] : round($value['qty']);
						}else{
							$info_kg   = (fmod($value['qty'],1) !== 0.00) ? $value['qty'] : round($value['qty']);
						}

					}



					echo "<Tr>";
					//echo "<td>".((fmod($value['qty'],1) !== 0.00) ? $value['qty'] : round($value['qty']))."x ".$value['nama_satuan_trans']."</td>";
					echo "<td align='center'>".$info_roll."</td>";
					echo "<td align='center'>".$info_kg."</td>";
					echo "<td>".$value['nama_barang']." ".$list_sat."</td>";
					echo "</Tr>";
				}
			?>
		</tbody>
	</table>

	<table width="100%" class="pad">
		<tr>
			<td align='center' width="33.3%"> Tanda Terima </td>
			<td align='center' width="33.3%" rowspan="4" style="vertical-align: middle">
				&nbsp;
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