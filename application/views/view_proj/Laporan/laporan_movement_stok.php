<!-- start laporan status stok -->
<link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/select2/css/select2.min.css">
<script type="text/javascript" src="<?php echo base_url()?>assets/plugins/select2/js/select2.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$("select[name='tbrid']").select2();
	$("select[name='tjbid']").select2();
	$("select[name='typbid']").select2();
	$("select[name='tsid']").select2();
	$("select[name='twid']").select2();
});

function cekbarang(){

	var tbrid = $("select[name='tbrid']").val();
	var tsid = $("select[name='tsid']").val();

	if ( tbrid == '' ||  tsid == '') {

		alert('Pilih Barang Dan Satuan Terlebih Dahulu');

		return false;
	}

	return true;
}
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
	<h1>Laporan Mutasi Barang</h1>

	<form style="width:600px;margin:auto;" method="post" action="<?php echo base_url('Laporan/view_movement_stok')?>" name="frm" id="frm">
		<table width="100%" class="bdr1 pad">
			<tr>
				<th width="20%" style="text-align: left">Periode</th>
				<td>
					<input type="text" name="tgl1" value='<?php echo $tgl1;?>' class="reservation">
					s/d
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
				<th style="text-align: left">List Satuan</th>
				<td>
					<select name="tsid" id="tsid">
						<option></option>
						<?php 
							foreach ($list_satuan->result_array() as $key) {
								if ( $key['tsid'] == $tsid ) {
									echo "<option value='".$key['tsid']."' selected='selected'>".$key['satuan']."</option>";
								} else {
									echo "<option value='".$key['tsid']."'>".$key['satuan']."</option>";	
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
					<input type='submit' value='Cari' onclick="return cekbarang();"/>
				</td>
			</tr>
		</table>
	</form>
	<br>
	<table width="100%" class="bdr1 pad">
		<thead>
			<th>Tgl Trans</th>
			<th>Nama Barang</th>
			<th width='15%'>Jenis</th>
			<th width='5%'>Tipe</th>
			<th width='15%'>Warna</th>
			<th>Jenis Transaksi</th>
			<th width='10%'>Satuan Besar</th>
			<th width='8%'>Jml Masuk</th>
			<th width='8%'>Jml Keluar</th>
			<th width='8%'>Saldo Stok</th>
			
		</thead>
		<tbody>
			<?php

				$stok_gerak = $list_stok_awal[0]['jml_stok_roll'];

				if ( $tbrid != ''  AND $tsid != '' ) {
					$count_data = count($list_stok->result_array());
				}else{
					$count_data = 0;
				}

				if ( $count_data == 0 ) {
					echo "<tr><td colspan='10' align='center'>Tidak Ada Data</td></tr>";
				} else {

					echo "<tr>
							 <td colspan='9' align='right'>Stok Awal</td>
							 <td align='right'>".number_format($stok_gerak,2,'.',',')."</td>
						  </tr>";



							foreach ($list_stok->result_array() as $key) {

						   $stok_gerak = $stok_gerak + $key['jml_masuk'] - $key['jml_keluar'];


							echo "<tr>";
							echo "<td align='center'>".$key['tgl_trans']."</td>";
							echo "<td>".$key['nama_barang']."</td>";
							echo "<td align='center'>".$key['kd_jenis']." - ".$key['nm_jenis']."</td>";
							echo "<td align='center'>".$key['nama_tipe']."</td>";
							echo "<td align='center'>".$key['nama_warna']."</td>";
							echo "<td align='center'>".$key['tipe_transaksi']." <br> [".$key['no_transaksi']."]</td>";
							echo "<td align='center'>".$key['nama_satuan']."</td>";
							echo "<td align='right'>".($key['jml_masuk'] != '' ? number_format($key['jml_masuk'],2,'.',',') : '')."</td>";
							echo "<td align='right'>".($key['jml_keluar'] != '' ? number_format($key['jml_keluar'],2,'.',',') : '')."</td>";
							echo "<td align='right'>".number_format($stok_gerak,2,'.',',')."</td>";
							echo "</tr>";
						}
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
<!-- end laporan status stok -->