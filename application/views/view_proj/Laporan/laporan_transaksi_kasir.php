<!-- start laporan_transaksi_kasir.php !-->
<style type="text/css">
#previews {

	margin-top:20px !important;
}
</style>
<script type='text/javascript' src="<?php echo base_url();?>assets/plugins/nominal_js/nominal.js"></script>
<div id='previews'>
	<h1>Laporan Transaksi Kasir</h1>

	<form style='width:660px;margin:auto;' method='post' name='frm' id='frm' action="<?php echo base_url('Laporan/view_transaksi_kasir')?>">
		<input type='hidden' name='is_excel' id='is_excel'/>
		<table width="100%" class='bdr1 pad'>
			<tr>
				<th width='20%' style="text-align: left">Tanggal</th>
				<td>
					<input type="text" name="tgl1" value='<?php echo $tgl1;?>' class="reservation">
					s/d
					<input type="text" name="tgl2" value='<?php echo $tgl2;?>' class="reservation">
				</td>
			</tr>
			<tr>
				<th style="text-align: left">No. Payment</th>
				<td><input type='text' name='no_pay' id='no_pay' style="width:40%" value='<?php echo $no_pay;?>'/></td>
			</tr>
			<tr>
				<th style="text-align: left">Status Bil</th>
				<td>
					<?php 

					$status_bill_f = $status_bill_t = $status_parti = "";

					if ( $status_bill == 'belum lunas') {
						$status_bill_f = 'selected="selected"';
					}

					if ( $status_bill == 'sudah lunas') {
						$status_bill_t = 'selected="selected"';
					}


					if ( $status_bill == 'pembayaran partial') {
						$status_parti = 'selected="selected"';
					}
					?>
					<select name="status_bill" id="status_bill">
						<option></option>
						<option value='belum lunas' <?php echo $status_bill_f; ?>>Belum Lunas</option>
						<option value='pembayaran partial' <?php echo $status_parti; ?>>Pembayaran Partial</option>
						<option value='sudah lunas' <?php echo $status_bill_t; ?>>Sudah Lunas</option>
					</select>
				</td>
			</tr>
			<tr>
				<th style="text-align: left">Petugas Kasir</th>
				<td>
					<?php 
						foreach ($list_kasir->result_array() as $key => $value) {

							if ( in_array($value['teid'], $id_kasir)) {
								echo "<input type='checkbox' name='id_kasir[]' value='".$value['teid']."' checked='checked'> ".$value['nama_karyawan']."<br>";
							}else{
								echo "<input type='checkbox' name='id_kasir[]' value='".$value['teid']."'> ".$value['nama_karyawan']."<br>";
							}


						}
					?>
				</td>
			</tr>
			<tr>
				<td colspan='2'>
					<input type='button' value='Cari' onclick="document.getElementById('is_excel').value='no';document.frm.submit();">
					<input type='button' value='Excel' onclick="document.getElementById('is_excel').value='yes';document.frm.submit();">
					<input type="button" value="Tutup" onclick="window.close()"/>
				</td>
			</tr>
		</table>
	</form>
	<br><br><br>
	<table width='100%' class='bdr1 pad'>
		<thead>
			<tr>
				<th width='1%'>No</th>
				<th width="10%">Tgl Trans</th>
				<th width="10%">No Bill</th>
				<th width='10%'>Petugas Kasir</th>
				<th>Nama Pelanggan</th>
				<th width='10%'>Total Tagihan</th>
				<th width='10%'>Total Bayar</th>
				<th width="10%">Sisa Tagihan</th>
			</tr>
		</thead>
		<tbody>
			<?php 
				$no=1;
				$total_tagihan = 0;
				$total_bayar = 0;
				$sisa_tagihan = 0;
				$jml_data_detail = $list_penerimaan->result_id->num_rows;

				if ($jml_data_detail > 0 ) {

					foreach ($list_penerimaan->result_array() as $key => $value) {

					$ket_bayar = $value['cara_bayar'];

					if ( $value['cara_bayar'] == 'DEBET') {

						$ket_bayar = $value['cara_bayar'].' [ '.$value['nama_edc'].' ( No. Batch : '.$value['no_batch'].' , No. Kartu : '.$value['no_kartu'].' ) ]';
					}

					echo "
						<tr>
							<td>".$no."</td>
							<td align='center'>".date('d M Y',strtotime($value['tgl_trans']))."</td>
							<td align='center'>".$value['no_bill']."</td>
							<td align='center'>".$value['user']."</td>
							<td align='center'>".$value['nama_konsumen']."</td>
							<td align='right' class='row_nominal'>".number_format($value['total_amount'],2,',','.')."</td>
							<td align='right' class='row_nominal'>".number_format($value['amount_bayar_partial'],2,',','.')."</td>
							<td align='right' class='row_nominal'>".number_format(($value['total_amount'])-$value['amount_bayar_partial'],2,',','.')."</td>
						</tr>
					";
					$no++;
					$total_tagihan += $value['total_amount'];
					$total_bayar += $value['amount_bayar_partial'];
					$sisa_tagihan += (($value['total_amount'])-$value['amount_bayar_partial']);
				}

				echo "<tr style='background:#eee;font-weight:bold'>
						<td colspan='5' align='right'>TOTAL</td>
						<td align='right' class='row_nominal'>".number_format($total_tagihan,2,',','.')."</td>
						<td align='right' class='row_nominal'>".number_format($total_bayar,2,',','.')."</td>
						<td align='right' class='row_nominal'>".number_format($sisa_tagihan,2,',','.')."</td>
						
					 </tr>";

				} else {
					echo "<tr><td colspan='9'>Tidak ada data</td></tr>";
				}

				
			?>
		</tbody>
	</table>
</div>
<script type="text/javascript">

 $(function() {
    $('.reservation').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true
    }, 
    function(start, end, label) {
        var years = moment().diff(start, 'years');
        
    });
});
</script>
<!-- end laporan_transaksi_kasir.php !-->