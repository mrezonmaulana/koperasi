<!-- start laporan status stok -->
<link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/select2/css/select2.min.css">
<script type="text/javascript" src="<?php echo base_url()?>assets/plugins/select2/js/select2.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$("select[name='tbrid']").select2();
	$("select[name='tjbid']").select2();
	$("select[name='typbid']").select2();
	$("select[name='twid']").select2();
	$("select[name='tsid']").select2();
	$("select[name='tipe_lap']").select2();
});

function printData(){
	document.getElementById('frm').style.display='none';
	$(".stockop").css('display','');
	window.print();
	document.getElementById('frm').style.display='';
	$(".stockop").css('display','none');
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
	<h1>Laporan Status Stok <span>Gunakan Salah satu filter yang ada, agar report status bisa tampil</span></h1>

	<form style="width:600px;margin:auto;" method="post" action="<?php echo base_url('Laporan/view_status_stok')?>" name="frm" id="frm">
		<input type="hidden" name="is_excel" id="is_excel"/>
		<table width="100%" class="bdr1 pad">
			<tr>
				<th width="20%" style="text-align: left">Periode S/d</th>
				<td>
					<input type="text" name="tgl1" value='<?php echo $tgl1;?>' class="reservation">
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
				<th style="text-align: left">Satuan</th>
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
				<th style="text-align: left">Tipe Laporan</th>
				<td>
					<?php 
						$tipe_lap1 = $tipe_lap2 = "";

						if ( $tipe_lap == 1 ) {
							$tipe_lap1 = "selected='selected'";
						}

						if ( $tipe_lap == 2 ) {
							$tipe_lap2 = "selected='selected'";
						}
					?>
					<select name="tipe_lap" id="tipe_lap">
						<option value='1' <?php echo $tipe_lap1;?> >Rekap</option>
						<option value='2' <?php echo $tipe_lap2;?> >Detail</option>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<input type='button' value='Cari' onclick="document.getElementById('is_excel').value=0;this.form.submit();"/>
					<input type="button" value="Excel" onclick="document.getElementById('is_excel').value=1;this.form.submit();"/>
					<input type="button" value="Cetak" onclick="printData();"/>
				</td>
			</tr>
		</table>
	</form>
	<br>
	<table width="100%" class="bdr1 pad">
		<thead>
			<th>Nama Barang</th>
			<th width='15%'>Warna</th>
			<!-- <th width='15%'>Jenis</th>
			<th width='5%'>Tipe</th>
			<th width='7%'>Gram</th>
			<th width='7%'>Lebar</th> -->
			<th width="7%">Nama Satuan</th>
			<th width='4%'>JML ROLL</th>
			<th width='4%'>JML KG</th>
			<th width="7%" class='stockop' style="display:none">Stok Fisik Dalam Roll</th>
			<th width="4%" class='stockop' style="display:none">Kuantiti Penyesuaian</th>
		</thead>
		<tbody>
			<?php

				$count_data = count($list_stok->result_array());

				$total_roll = $total_kg = 0;
				if ( $count_data == 0 ) {
					echo "<tr><td colspan='10' align='center'>Tidak Ada Data</td></tr>";
				} else {
					foreach ($list_stok->result_array() as $key) {
					echo "<tr>";
					echo "<td>".$key['nama_barang']."</td>";
					echo "<td align='center'>".$key['nama_warna']."</td>";
					/*echo "<td align='center'>".$key['kd_jenis']." - ".$key['nm_jenis']."</td>";
					echo "<td align='center'>".$key['nama_tipe']."</td>";
					echo "<td align='center'>".$key['gram']."</td>";
					echo "<td align='center'>".$key['lebar']."</td>";*/
					echo "<td align='center'>".$key['nama_sat']."</td>";
					echo "<td align='right'>".number_format($key['jml_stok_roll'],2,'.',',')."</td>";
					echo "<td align='right'>".number_format($key['jml_stok_kg'],2,'.',',')."</td>";
					echo "<td class='stockop' style='display:none'></td>";
					echo "<td class='stockop' style='display:none'></td>";
					echo "</tr>";

					$total_roll += $key['jml_stok_roll'];
					$total_kg += $key['jml_stok_kg'];
					}
				}

				
			?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan='3'style="background: #eee;font-weight: bold" align="right">TOTAL</td>
				
				<td style="background: #eee;font-weight: bold" align="right"><?php echo number_format($total_roll,2,',','.');?></td>
				<td style="background: #eee;font-weight: bold" align="right"><?php echo number_format($total_kg,2,',','.');?></td>
				<td colspan="2" class='stockop' style='display:none'>&nbsp;</td>
				
			</tr>
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
<!-- end laporan status stok