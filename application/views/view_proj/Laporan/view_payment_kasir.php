<!-- start view_payment_kasir.html -->
<style type="text/css">
#previews {

	margin-top:20px !important;
}
</style>
<div id='previews'>
	<h1>Laporan Pembayaran Kasir</h1>
	<form style='width:660px;margin:auto;' method='post' name='frm' id='frm' action="<?php echo base_url('Laporan/view_payment_kasir')?>">
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
				<td colspan='2'>
					<input type='button' value='Cari' onclick="document.getElementById('is_excel').value='no';document.frm.submit();">
					<input type="button" value="Tutup" onclick="window.close()"/>
				</td>
			</tr>
		</table>
	</form>
	<br><br><br>
	<table width="100%" class='bdr1 pad'>
		<thead>
			<tr>
				<th width='1%'>No</th>
				<th width='10%'>No Bill</th>
				<th width='10%'>Tgl Bayar</th>
				<th width="10%">Petugas Bayar</th>
				<th width='10%'>Cara Bayar</th>
				<th>Atas Nama</th>
				<th>Nama Bank</th>
				<th width='10%'>Nominal</th>
			</tr>
		</thead>
		<tbody>
			<?php 
				$no = 1;
				$total_all = 0;
				foreach ($list_payment->result_array() as $key ) {
					echo "
					<tr>
						<td>".$no."</td>
						<td>".$key['no_bill']."</td>
						<td>".$key['create_time']."</td>
						<td>".$key['nama_user']."</td>
						<td>".$key['cara_bayar']."</td>
						<td>".$key['atas_nama']."</td>
						<td>".$key['nama_edc']."</td>
						<td align='right'>".number_format($key['amount'],2,',','.')."</td>
					</tr>
					";
					$no++;

					$total_all += $key['amount'];
				}

				echo "<tr style='background:#eee;font-weight:bold'><td colspan='7' align='right'>TOTAL</td><td align='right'>".number_format($total_all,2,',','.')."</td></tr>";
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
<!-- end view_payment_kasir.html -->