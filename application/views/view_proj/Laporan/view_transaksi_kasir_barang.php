<!-- start view_transaksi_kasir_barang -->
<link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/select2/css/select2.min.css">
<script type="text/javascript" src="<?php echo base_url()?>assets/plugins/select2/js/select2.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$("select[name='tbrid']").select2();
	$("select[name='tjbid']").select2();
	$("select[name='typbid']").select2();
	$("select[name='twid']").select2();
	$("select[name='tsid']").select2();
});
</script>
<style type="text/css">
#previews {

	margin-top:20px !important;
}
</style>
<style>
    

    .select2-container--default .select2-selection--single{
       font-size:10pt !important;
    }
  
</style>
<div id='previews'>
	<h1>Laporan Penjualan Detail Barang</h1>

	<form style="width:600px;margin:auto;" method="post" action="<?php echo base_url('Laporan/view_transaksi_kasir_barang')?>" name="frm" id="frm">
		<table width="100%" class="bdr1 pad">
			<tr>
				<th width="20%" style="text-align: left">Periode S/d</th>
				<td>
					<input type="text" name="tgl1" value='<?php echo $tgl1;?>' class="reservation">
					S/d
					<input type="text" name="tgl2" value='<?php echo $tgl2;?>' class="reservation">
				</td>
			</tr>
			<tr>
				<th style="text-align: left">Nama Barang</th>
				<td>
					<select name="tbrid" id="tbrid">
						<option></option>
						<?php 
							foreach ($list->result_array() as $key) {
								if ( $key['tbrid'] == $tbrid ) {
									echo "<option value='".$key['tbrid']."' selected='selected'>".$key['nama_barang']." - ".$key['nama_warna']."</option>";
								} else {
									echo "<option value='".$key['tbrid']."'>".$key['nama_barang']." - ".$key['nama_warna']."</option>";	
								}
								
							}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<th style="text-align: left">Jenis Barang</th>
				<td>
					<select name="tjbid" id="tjbid">
						<option></option>
						<?php 
							foreach ($list_jenis->result_array() as $key) {
								if ( $key['tjbid'] == $tjbid ) {
									echo "<option value='".$key['tjbid']."' selected='selected'>".$key['kd_jenis']." - ".$key['nm_jenis']."</option>";
								} else {
									echo "<option value='".$key['tjbid']."'>".$key['kd_jenis']." - ".$key['nm_jenis']."</option>";	
								}
							}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<th style="text-align: left">Type Barang</th>
				<td>
					<select name="typbid" id="typbid">
						<option></option>
						<?php 
							foreach ($list_tipe->result_array() as $key) {
								if ( $key['typbid'] == $typbid ) {
									echo "<option value='".$key['typbid']."' selected='selected'>".$key['nama_tipe']."</option>";
								} else {
									echo "<option value='".$key['typbid']."'>".$key['nama_tipe']."</option>";	
								}
							}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<th style="text-align: left">Warna Barang</th>
				<td>
					<select name="twid" id="twid">
						<option></option>
						<?php 
							foreach ($list_warna->result_array() as $key) {
								if ( $key['twid'] == $twid ) {
									echo "<option value='".$key['twid']."' selected='selected'>".$key['nama_warna']."</option>";
								} else {
									echo "<option value='".$key['twid']."'>".$key['nama_warna']."</option>";	
								}
							}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<input type='submit' value='Cari'/>
				</td>
			</tr>
		</table>
	</form>
	<br>
	<table width="100%" class="bdr1 pad">
		<thead>
		<tr>
			<th rowspan='2'>No</th>
			<th rowspan='2'>Tgl Bill</th>
			<th rowspan='2'>No Bill</th>
			<th rowspan='2'>Nama Konsumen</th>
			<th rowspan='2'>User Bill</th>
			<th rowspan='2'>Nama Barang</th>
			<th colspan='2'>Qty</th>
			<th rowspan='2'>Harga / KG</th>
			<th rowspan='2'>Total Sblm Diskon</th>
			<th rowspan='2'>Diskon / KG</th>
			<th rowspan='2'>Diskon Total</th>
			<th rowspan='2'>Total Stlh Diskon</th>
		</tr>
		<tr>
			<th>ROLL</th>
			<th>KG</th>
		</tr>
		</thead>
		<tbody>
			<?php

				$count_data = count($list_stok->result_array());

				$total_roll = $total_kg = 0;
				if ( $count_data == 0 ) {
					echo "<tr><td colspan='13' align='center'>Tidak Ada Data</td></tr>";
				} else {
					$no=1;
					$first_tbmid = $list_stok->result_array()[0]['tbmid'];
					$total_awal = $total_all = $total_awal_roll = $total_awal_kg = $total_all_roll = $total_all_kg = 0;
					foreach ($list_stok->result_array() as $key) {

						$row_satuan = $key['satuan_list'];
						$array_satuan = explode(",",$row_satuan);
						$info_kg_roll = 0;
						foreach ($array_satuan as $k => $v) {
							
							$persatuan = explode(":",$v);
							
							for($no_awal=0;$no_awal<intval($persatuan[0]);$no_awal++){

								$info_kg_roll += $persatuan[1];

							}

						}						

					$info_roll = $info_kg = "";

					if ( $key['nama_satuan_trans'] == 'ROLL') {
						$info_roll = (fmod($key['qty'],1) !== 0.00) ? $key['qty'] : round($key['qty']);
						$info_kg  = $info_kg_roll;
					}else{
						$info_kg  = (fmod($key['qty'],1) !== 0.00) ? $key['qty'] : round($key['qty']);
					}


					$subtotal_bawah = "";

					if ( $first_tbmid != $key['tbmid']) {

						$total_baru = $total_awal;

						$total_baru_roll = $total_awal_roll;
						$total_baru_kg = $total_awal_kg;

						$subtotal_bawah = "<tr>
											   <td colspan='6' align='right' style='background:#eee;font-weight:bold'> SUBTOTAL </td>
											   <td align='center' style='background:#eee;font-weight:bold'>".$total_baru_roll."</td>
											   <td align='center' style='background:#eee;font-weight:bold'>".$total_baru_kg."</td>
											   <td colspan='4' style='background:#eee;font-weight:bold'>&nbsp;</td>
											   <td align='right' style='background:#eee;font-weight:bold'>".number_format($total_baru,2,',','.')."</td>
											</tr>";
						$total_awal = $total_awal_roll = $total_awal_kg = 0;
					}


					echo $subtotal_bawah;


					echo "<tr>";
					echo "<td>".$no."</td>";
					echo "<td>".$key['tgl_bill']."</td>";
					echo "<td>".$key['no_bill']."</td>";
					echo "<td>".$key['nama_konsumen']."</td>";
					echo "<td>".$key['user_bill']."</td>";
					echo "<td>".$key['nama_barang']."</td>";
					echo "<td align='center'>".$info_roll."</td>";
					echo "<td align='center'>".$info_kg."</td>";
					echo "<td align='right'>".number_format($key['harga_jual'],2,',','.')."</td>";
					echo "<td align='right'>".number_format($key['harga_satuan'],2,',','.')."</td>";
					echo "<td align='right'>".number_format($key['discount_amount'],2,',','.')."</td>";
					echo "<td align='right'>".number_format($key['discount_total'],2,',','.')."</td>";
					echo "<td align='right'>".number_format($key['amount_total'],2,',','.')."</td>";
					echo "</tr>";


					$no++;
					$first_tbmid = $key['tbmid'];
					$total_awal += $key['amount_total'];
					$total_all  += $key['amount_total'];

					$total_awal_roll += floatval($info_roll);
					$total_awal_kg += $info_kg;

					$total_all_roll += floatval($info_roll);
					$total_all_kg += $info_kg;
					}

					echo "<tr>
											   <td colspan='6' align='right' style='background:#eee;font-weight:bold'> SUBTOTAL </td>
											   <td align='center' style='background:#eee;font-weight:bold'>".$total_awal_roll."</td>
											   <td align='center' style='background:#eee;font-weight:bold'>".$total_awal_kg."</td>
											   <td colspan='4' style='background:#eee;font-weight:bold'>&nbsp;</td>
											   <td align='right' style='background:#eee;font-weight:bold'>".number_format($total_awal,2,',','.')."</td>
											</tr>";

					echo "<tr>
											   <td colspan='6' align='right' style='background:#eee;font-weight:bold'> TOTAL SEMUA </td>
											   <td align='center' style='background:#eee;font-weight:bold'>".number_format($total_all_roll,2,',','.')."</td>
											   <td align='center' style='background:#eee;font-weight:bold'>".number_format($total_all_kg,2,',','.')."</td>
											   <td colspan='4' style='background:#eee;font-weight:bold'>&nbsp;</td>
											   <td align='right' style='background:#eee;font-weight:bold'>".number_format($total_all,2,',','.')."</td>
											</tr>";


				}

				
			?>
		</tbody>
		<tfoot>
		</tfoot>
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
<!-- end view_transaksi_kasir_barang -->