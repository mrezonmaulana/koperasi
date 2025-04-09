<!-- start view_transaksi -->
<style type="text/css">
@media print {
	body {
		font-family: 'times new roman';
	}
	#previews {
		margin-top:.3cm;
	}


table#list_data thead tr th {
		border:1.5px solid black !important;
		font-size:11pt;
		font-weight: normal; !important;
	}

table#list_data tbody tr td {
		
		font-size:11pt !important;
		
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
			window.location.replace(url+"?is_surat_jalan=t");
		} else if ( tipe == 3 ) {
			url = url.replace('?is_surat_jalan=t','');
			window.location.replace(url+"?is_surat_jalan_ekspedisi=t");
		}else {
			url = url.replace('?is_surat_jalan=t','');
			url = url.replace('?is_surat_jalan_ekspedisi=t','');
			window.location.replace(url);
		}
		
	}

	function setMobil(){
		var nm_mobil = $("input[id='nm_mobil']").val();
		var nopol_mobil = $("input[id='nopol_mobil']").val();

		var kpd_yth = $("input[id='kpd_yth']").val();		
		var banyaknya = $("input[id='banyaknya']").val();		
		var nama_barangnya1 = $("input[id='nama_barangnya1']").val();		
		var nama_barangnya2 = $("input[id='nama_barangnya2']").val();		
		var nama_barangnya3 = $("input[id='nama_barangnya3']").val();		
		var nama_barangnya4 = $("input[id='nama_barangnya4']").val();		
		var nama_barangnya5 = $("input[id='nama_barangnya5']").val();		
		var nama_barangnya6 = $("input[id='nama_barangnya6']").val();		

		var tbmid = '<?php echo $list_header->tbmid;?>';

		$.post("<?php echo base_url();?>Laporan/update_mobil_exs",{tbmid:tbmid,nm_mobil:nm_mobil,nopol_mobil:nopol_mobil,kpd_yth:kpd_yth,banyaknya:banyaknya,barangnya1:nama_barangnya1,barangnya4:nama_barangnya4,barangnya5:nama_barangnya5,barangnya6:nama_barangnya6},function(data){

			$("input[id='nm_mobil']").css('display','none');
			$("input[id='nopol_mobil']").css('display','none');
			$("font[id='text_nm_mobil']").html(nm_mobil).css('font-weight','bold');
			$("font[id='text_nopol_mobil']").html(nopol_mobil).css('font-weight','bold');
			$("input[id='kpd_yth']").css('border','none');
			$("input[id='banyaknya']").css('border','none');
			$("input[id='nama_barangnya1']").css('border','none');
			$("input[id='nama_barangnya2']").css('border','none');
			$("input[id='nama_barangnya3']").css('border','none');
			$("input[id='nama_barangnya4']").css('border','none');
			$("input[id='nama_barangnya5']").css('border','none');
			$("input[id='nama_barangnya6']").css('border','none');
			window.print();
			$("input[id='nm_mobil']").css('display','');
			$("input[id='nopol_mobil']").css('display','');
			$("font[id='text_nm_mobil']").html('');
			$("font[id='text_nopol_mobil']").html('');
			$("input[id='kpd_yth']").css('border','1px solid #7A7A7A');
			$("input[id='banyaknya']").css('border','1px solid #7A7A7A');
			$("input[id='nama_barangnya1']").css('border','1px solid #7A7A7A');
			$("input[id='nama_barangnya2']").css('border','1px solid #7A7A7A');
			$("input[id='nama_barangnya3']").css('border','1px solid #7A7A7A');
			$("input[id='nama_barangnya4']").css('border','1px solid #7A7A7A');
			$("input[id='nama_barangnya5']").css('border','1px solid #7A7A7A');
			$("input[id='nama_barangnya6']").css('border','1px solid #7A7A7A');
		});
	}
</script>
<div id="functions">
        <ul>
                <li><a href="#" onclick="setMobil();return false;">Print</a></li>
                <li><a href="#" onclick="JavaScript:window.close();">Tutup</a></li>
                <li><a href="#" onclick="printSuratJalan(1);return false;">Surat Jalan</a></li>
                <li><a href="#" onclick="printSuratJalan(3);return false;">Surat Jalan Expedisi</a></li>
                <li><a href="#" onclick="printSuratJalan(2);return false;">Penjualan</a></li>
        </ul>
</div>

<div id='previews'>
	<table width='100%' class='pad'>
		<tr>
			<td align='left' style="vertical-align: middle" width="6%">
					<img src="<?php echo base_url();?>assets/images/warung_oksi_menu.png" width='80'>
			</td>
			<td align='left' width="50%" style="vertical-align: middle;letter-spacing: 1.2;">
				<font style="font-size:20pt">TEXTILE</font><br><br>Jl. Pameuntasan No.17 Rt. 01 Rw.03 Ds. Pameuntasan <br> Kec. Kutawaringin, Bandung <br> Telp. (022) 85870975, Fax. (022) 85870975
			</td>
			<td align='right' style="vertical-align: top;font-size:10pt !important">
				<table width="100%">
					<tr>
						<td width="35%">Bandung</td>
						<td><?php echo date('d M Y',strtotime($list_header->bill_date)); ?></td>
					</tr>
					<tr>
						<td width="35%">Nama Ekspedisi</td>
						<td><input type='text' name='kpd_yth' id='kpd_yth' value='<?php echo $list_header->kpd_yth?>'/></td>
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
		<thead>
			<tr>
				<td colspan="2" style="font-size:10pt !important">
					Kami kirimkan barang-barang dibawah ini dengan Kendaraan <font id='text_nm_mobil'/></font><input type='text' name='nm_mobil_exs' id='nm_mobil' style='text-align:left;padding-left:5px;' value='<?php echo $list_header->nm_mobil_exs;?>'/> Nopol <font id='text_nopol_mobil'/></font><input type='text' name='nopol_mobil_exs' id='nopol_mobil' style='text-align:left;padding-left:5px;' value='<?php echo $list_header->nopol_mobil_exs;?>'/>
				</td>
			</tr>
			<tr>
				<th width='10%' style="font-weight:none !important">Banyaknya</th>
				<th style="font-weight:none !important">Nama Barang</th>
			</tr>
		</thead>
		<tbody style="font-size:11pt !important">
			<tr>
				<td  style="border:2px solid black">
					<input type='text' name="banyaknya" id="banyaknya" value='<?php echo $list_header->banyaknya;?>' style='width:100%;text-align: center;padding-left:5px;'/>
				</td>
				<td style="border:2px solid black">
					<input type='text' name="nama_barangnya1" id="nama_barangnya1" value='<?php echo $list_header->barangnya1;?>' style='width:100%;padding-left:5px;'/>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<input type='text' name="nama_barangnya2" id="nama_barangnya2" style='width:100%;padding-left:5px;' readonly='readonly' value='To : <?php echo $list_header->nama_konsumen;?> , Telp : <?php echo $list_header->telp_konsumen; ?>'/>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<input type='text' name="nama_barangnya3" id="nama_barangnya3" style='width:100%;padding-left:5px;' readonly='readonly' value='<?php echo $list_header->alamat_konsumen;?>'/>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<input type='text' name="nama_barangnya4" id="nama_barangnya4" value='<?php echo $list_header->barangnya4;?>'style='width:100%;padding-left:5px;'/>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<input type='text' name="nama_barangnya5" id="nama_barangnya5" value='<?php echo $list_header->barangnya5;?>' style='width:100%;padding-left:5px;'/>
				</td>
			</tr>
			<tr>
				<td colspan="2" >
					<input type='text' name="nama_barangnya6" id="nama_barangnya6" value='<?php echo $list_header->barangnya6;?>'style='width:100%;padding-left:5px;'/>
				</td>
			</tr>
		</tbody>
	</table>

	<table width="100%" class="pad" style="font-size:10pt !important">
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