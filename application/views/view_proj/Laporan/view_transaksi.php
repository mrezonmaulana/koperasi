<!-- start view_transaksi -->
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
						<td style='font-size:14pt;font-weight: bold'>TEXTILE COMPANY</td>
					</tr>
					<Tr>
						<td>Jl. Pameuntasan no. 33, Kp. Sekebungur No.17, RT.01/RW.03, Pameuntasan, Kec. Kutawaringin</td>
					</Tr>
					<tr>
						<td>Kab. Bandung, Jawa Barat</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

	<h2><?php echo $title;?> 
	<span style="letter-spacing: 1.2"> <?php echo $jenis_tanggal; ?> : <?php echo date('d F Y',strtotime($header->tgl_trans));?></span>
	<span style="letter-spacing: 1.2"> <?php echo $jenis_media; ?> : <?php echo $header->nama_media;?></span>
	<span style='float:right;margin-top:-50px;'> 
			<table class='bdr1 pad' width='100%'>
				<tr>
					<td align='center'><?php echo $jenis_trans; ?></td>
				</tr>
				<tr>
					<td><font style='font-size:14pt;letter-spacing: 1.2'><?php echo $header->reff_code; ?></font></td>		
				</tr>
			</table>
			<!-- Kode Penerimaan<br>
			 -->
	</span>

	</h2>
	<hr style='border:double;'>

	<table width='100%' class='bdr1 pad'>
		<thead>
			<tr>
				<th width='1%'>No</th>
				<th>Nama Barang</th>
				<th width='5%'>Qty</th>
				<th width='10%'>Satuan</th>
				<th width='15%'>Harga Satuan</th>
				<th width='15%'>Total Harga</th>
			</tr>
		</thead>
		<tbody>
			<?php 
				$no=1;
				$totalall = 0;
				foreach ($detail->result_array() as $key) {
					echo "
						<tr>
						    <td align='center'>".$no.".</td>
						    <td>".$key['nama_barang']."</td>
						    <td align='center'>".$key['vol']."</td>
						    <td>".$key['satuan']."</td>
						    <td align='right' class='row_nominal'>".number_format($key['harga_satuan'],2,',','.')."</td>
						    <td align='right' class='row_nominal'>".number_format($key['harga_total'],2,',','.')."</td>
						</tr>
					";
					$no++;
				}
			?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan='5' align='right' style='font-weight:bold'>Total Diskon</td>
					<td align='right' class='row_nominal' style='font-weight:bold'><?php echo number_format($header->total_diskon,2,',','.');?></td>
			</tr>
			<tr>
				<td colspan='5' align='right' style='font-weight:bold'>PPN</td>
					<td align='right' class='row_nominal' style='font-weight:bold'><?php echo number_format($header->ppn_rp,2,',','.');?></td>
			</tr>
			<tr>
				<td colspan='5' align='right' style='font-weight:bold'>TOTAL</td>
					<td align='right' class='row_nominal' style='font-weight:bold'><?php echo number_format($header->total,2,',','.');?></td>
			</tr>
		</tfoot>
	</table>

</div>

<script type="text/javascript">
$(document).ready(function(){
	/*$.each($('.row_nominal'),function(k,v){

		nilai = this.innerHTML;

		this.innerHTML = formatRupiah("'"+nilai+"'","Rp. ");

	});*/
});
</script>
<!-- end view_transaksi -->