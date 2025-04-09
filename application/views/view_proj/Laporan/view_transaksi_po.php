<!--start view_transaksi -->
<style type="text/css">
@media print {
	body {
		font-family: 'times new roman' !important;
		letter-spacing: 1.5px;
	}
	#previews {
		margin-top:.3cm;
	}

	h2 {
	padding:0px !important;
	}

	table.bdr1 thead tr th {
		border:2px solid black !important;
		font-size:9pt;
		font-weight: normal !important;
	}
	table.bdr1 tbody tr td {
		border:2px solid black !important;
		font-size:9pt;
	}
	table.bdr1 tfoot tr td {
		border:2px solid black !important;
		font-size:9pt;
	}
	table.bdr1 {
		border-bottom:2px solid black !important;
	}
}



</style>
<script type='text/javascript' src="<?php echo base_url();?>assets/plugins/nominal_js/nominal.js"></script>

<?php 

	if ( $view_only == 'no' ) {
		echo '<div id="functions">
        <ul>
                <li><a href="#" onclick="window.print();">Print</a></li>
                <li><a href="#" onclick="JavaScript:window.close();">Tutup</a></li>
        </ul>
		</div>';		
	}
?>


<div id='previews'>
	<table width='100%' class='pad'>
		<tr>
			<td width='5%' align='center' style='vertical-align: middle'><img src="<?php echo base_url()?>assets/images/warung_oksi.png" width='85'></td>
			<td style='vertical-align: top'>
				<table width='100%' class='pad'>
					<tr>
						<td style='font-size:14pt;font-weight: bold'>TEXTILE</td>
					</tr>
					<Tr>
						<td style='font-size:9pt;'>Jl. Pameuntasan no. 33, Kp. Sekebungur No.17, RT.01/RW.03, Pameuntasan, Kec. Kutawaringin</td>
					</Tr>
					<tr>
						<td style='font-size:9pt;'>Kab. Bandung, Jawa Barat</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

	<center>
	<h3> PURCHASE ORDER CELUP </h3>
	</center>

	<table width="100%" class="">
		<tr>
			<td class="label" style="" width="15%">KEPADA</td>
			<td width="40%">: <?php echo $header->nama_media;?></td>
			<td class="label" style="" width="15%">TGL PO</td>
			<td>
				: <?php echo date('d F Y',strtotime($header->tgl_trans));?>
			</td>
		</tr>
		<tr>
			<td class="label" style="" width="15%">ATTN</td>
			<td>: <?php echo $header->attn; ?></td>
			<td class="label" style="" width="15%">NO.PO</td>
			<td>
				: <?php echo $header->reff_code; ?></font>
			</td>
		</tr>
		<tr>
			<td class="label" style="" width="15%">ALAMAT</td>
			<td>: <?php echo $header->alamat_supplier;?></td>
			<td class="label" style="" width="15%">No. SJ</td>
			<td>
				: <?php echo $header->no_sj;?>
			</td>
		</tr>
		<tr>
			<td class="label" style="" width="15%">KET</td>
			<td colspan="3">
				: <?php echo $header->keterangan;?>
			</td>
		</tr>
	</table>

	<!-- <h2><?php echo $title;?> 
	<span> <?php echo $jenis_tanggal; ?> : <?php echo date('d F Y',strtotime($header->tgl_trans));?></span>
	<span> <?php echo $jenis_media; ?> : <?php echo $header->nama_media;?></span>
	<span style='float:right;margin-top:-50px;'> 
			<table class='bdr1 pad' width='100%'>
				<tr>
					<td align='center'><?php echo $jenis_trans; ?></td>
				</tr>
				<tr>
					<td><font style='font-size:14pt'><?php echo $header->reff_code; ?></font></td>		
				</tr>
			</table>
	</span>

	</h2>-->
	

	<table width='100%' class='bdr1 pad'>
		<thead>
			<tr>
				<th width='1%' rowspan="2">No</th>
				<th rowspan="2">Jenis Grey</th>
				<th rowspan="2" width="10%">No. Match</th>
				<th rowspan="2" width="10%">Warna</th>
				<th colspan="2">QTY</th>
				<th rowspan="2" width="10%">Lebar</th>
				<th rowspan="2" width="10%">Gram</th>
				<th rowspan="2" width="10%">Handfeel</th>
				<!-- <th width='5%'>Qty</th>
				<th width='10%'>Satuan</th>
				<th width='15%'>Harga Satuan</th>
				<th width='15%'>Total Harga</th> -->
			</tr>
			<tr>
				<th width="10%">ROLL</th>
				<th width="10%">KG</th>
			</tr>
		</thead>
		<tbody>
			<?php 
				$no=1;
				$totalall = 0;
				$total_vol = 0;
				$total_konv = 0;
				foreach ($detail->result_array() as $key) {

					echo "
						<tr>
						    <td align='center'>".$no.".</td>
						    <td>".$key['nama_barang']."</td>
						    <td>".$key['no_match']."</td>
						    <td>".$key['nama_warna']."</td>
						    <td align='center'>".round($key['vol'])."</td>
						    <td align='center'>".(number_format($key['konversi_satuan']*$key['vol'],2,'.',','))."</td>
						    <td align='center'>".$key['lebar']."</td>
						    <td align='center'>".$key['gram']."</td>
						    <td align='center'>".$key['handfeel']."</td>
						</tr>
					";
					$no++;
					$total_vol += $key['vol'];
					$total_konv += ($key['vol']*$key['konversi_satuan']);
				}
			?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan='4' align='right' style='text-align: center;background: #ddd'>TOTAL</td>
					<td align='right' style='text-align:center'><?php echo $total_vol;?></td>
					<td align='right' style='text-align:center;'><?php echo number_format($total_konv,2,'.',',');?></td>
					<td colspan="9" >&nbsp;</td>
			</tr>
		</tfoot>
	</table>
	<div style="margin-top:5px;width:100%;">
	<font>CATATAN : MOHON TIDAK MEMOTONG / MENYAMBUNG KAIN !</font>
	</div>
	<br style="clear:both">
	<table width="100%" class="pad" style="font-size:10pt !important">
		<tr>
			<td width="16.6%"></td>
			<td width="16.6%" align='center'>Penerima</td>
			<td width="16.6%"></td>
			<td width="16.6%"></td>
			<td width="16.6%" align='center'>Pembuat</td>
			<td width="16.6%"></td>
		</tr>
		<tr>
			<td width="16.6%"></td>
			<td width="16.6%" align='center'>&nbsp;</td>
			<td width="16.6%"></td>
			<td width="16.6%"></td>
			<td width="16.6%" align='center'>&nbsp;</td>
			<td width="16.6%"></td>
		</tr>
	
		<tr>
			<td width="16.6%"></td>
			<td width="16.6%" align='center'><font style='float:left'>(</font><font style='float:right'>)</font></td>
			<td width="16.6%"></td>
			<td width="16.6%"></td>
			<td width="16.6%" align='center'><font style='float:left'>(</font><?php echo $header->user; ?><font style='float:right'>)</font></td>
			<td width="16.6%"></td>
		</tr>

</div>

<script type="text/javascript">
$(document).ready(function(){
	$.each($('.row_nominal'),function(k,v){

		nilai = this.innerHTML;

		this.innerHTML = formatRupiah("'"+nilai+"'","Rp. ");

	});
});
</script>
<!-- end view_transaksi