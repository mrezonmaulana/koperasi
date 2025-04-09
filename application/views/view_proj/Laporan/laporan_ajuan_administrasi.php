<!-- start laporan_pengeluaran per proyek -->
<style type="text/css">
#previews {

	margin-top:20px !important;
}
</style>
<script type='text/javascript' src="<?php echo base_url();?>assets/plugins/nominal_js/nominal.js"></script>
<div id='previews'>
	<h2>Laporan Ajuan Administrasi</h2>

	<form style='width:660px;margin:auto;' method='post' name='frm' id='frm' action="<?php echo base_url('Laporan/view_ajuan_administrasi')?>">
		<input type='hidden' name='is_excel' id='is_excel'/>
		<table width='100%' class='bdr1 pad'>
			<tr>
				<th width='20%' style="text-align: left">Tanggal</th>
				<td>
					<input type="text" name="tgl1" value='<?php echo $tgl1;?>' class="reservation">
					s/d
					<input type="text" name="tgl2" value='<?php echo $tgl2;?>' class="reservation">
				</td>
			</tr>
			<tr>
				<th width='20%' style="text-align: left">Pelaksana</th>
				<td>
					<select name='pelaksana' id='pelaksana'>
					<option></option>
					<?php  foreach($list_pelaksana->result_array() as $k){
						$sel_pelaksana = "";
						if ( $k['teid'] == $pelaksana ) {
							$sel_pelaksana = "selected='selected'";
						}
						echo "<option value='".$k['teid']."' ".$sel_pelaksana.">".$k['nama_karyawan']."</option>";
					}?>				
					</select>
				</td>
			</tr>
			<tr>
				<th width='20%' style="text-align: left">Jenis Proyek</th>
				<td>
					<select name='bidang' id='bidang'>
					<?php  foreach($list_bidang->result_array() as $k){
						$sel_bidang = "";
						if ( $k['tbid'] == $bidang ) {
							$sel_bidang = "selected='selected'";
						}
						echo "<option value='".$k['tbid']."' ".$sel_bidang.">".$k['nama_bidang']."</option>";
					}?>				
					</select>
			</td>
			</tr>
			<tr style='display:none'>
				<th style='text-align: left'>Status Pembayaran</th>
				<td>
					<?php $sel_bayar_f = $sel_bayar_t = "";
						if ( $status_pembayaran =='f'){
							$sel_bayar_f = "selected='selected'";
						}elseif ( $status_pembayaran =='t'){
							$sel_bayar_t = "selected='selected'";
						}
					?>
					<select name='status_pembayaran' id='status_pembayaran'>
						<option></option>
						<option value='f' <?php echo $sel_bayar_f;?> >Belum Lunas</option>
						<option value='t' <?php echo $sel_bayar_t;?> >Sudah Lunas</option>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan='2'>
					<input type='button' value='Cari' onclick="document.getElementById('is_excel').value=0;document.frm.submit();">
					<input type='button' value='Excel' onclick="document.getElementById('is_excel').value=1;document.frm.submit();">
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
				<th width='7%'>Nilai Kontrak</th>
				<th width='7%'>Real Cost</th>
				<th width='7%'>Perusahaan</th>
				<?php if ( $bidang == 1 ) { 
					echo "<th width='7%'>Backup</th>";
					echo "<th width='7%'>Kontrak</th>";
				} else { ?>
					<th width='7%'>Backup & Kontrak</th>
				<?php } ?>
				<th width='7%'>PHO</th>
				<th width='7%'>Keuangan</th>
				<th width='7%'>PMI</th>
				<th width='7%'>BASP</th>
				<th width='7%'>BKD</th>
				<th width='7%'>Ajuan Lain</th>
				<th width='7%'>Jumlah</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$no = 1;

			$first_tpaid = '';
			$jml_data_detail = $list_detail->result_id->num_rows;
			$totals= $total_all = 0;

			if ( $jml_data_detail > 0 ) {

				
				foreach ($list_detail->result_array() as $key) {


					$total = $key['backup']+
								$key['pho']+
								$key['keuangan']+
								$key['pmi']+
								$key['basp']+
								$key['kontrak_ajuan']+
								$key['ajuan_lain']+
								$key['bkd'];
					$tot_backup = $key['backup'] + $key['kontrak_ajuan'];

					echo "
						<tr style='font-style:italic;font-weight:bold;background:#eee'><td colspan='12'> Proyek : ".$key['nama_project']." - ".$key['alamat']." , ".$key['alamat2']."</td></tr>
						<tr>
							<td align='center'>".$no."</td>
							<td align='right' class='row_nominal'>".$key['anggaran']."</td>
							<td align='right' class='row_nominal'>".$key['pagu']."</td>
							<td align='center'>".$key['nama_perusahaan']."</td>
							<td align='right' class='row_nominal'>".$key['backup']."</td>";

							if ( $bidang == 1 ) {
								echo "<td align='right' class='row_nominal'>".$key['kontrak_ajuan']."</td>";
							}

							echo "<td align='right' class='row_nominal'>".$key['pho']."</td>
							<td align='right' class='row_nominal'>".$key['keuangan']."</td>
							<td align='right' class='row_nominal'>".$key['pmi']."</td>
							<td align='right' class='row_nominal'>".$key['basp']."</td>
							<td align='right' class='row_nominal'>".$key['bkd']."</td>
							<td align='right' class='row_nominal'>".$key['ajuan_lain']."</td>
							<td align='right' class='row_nominal'>".$total."</td>
							
						</tr>
					";

					$no++;
					$totals += $total;
					
				}
				if ( $bidang == 1 ) {

					echo "<Tr style='background:#ccc;font-weight:bold;font-style:italic'>
							<td colspan='12' align='right'><b>TOTAL</b></td>
							<td align='right' class='row_nominal'><b>".$totals."</b></td>
							</tr>";

				} else {

					echo "<Tr style='background:#ccc;font-weight:bold;font-style:italic'>
							<td colspan='11' align='right'><b>TOTAL</b></td>
							<td align='right' class='row_nominal'><b>".$totals."</b></td>
							</tr>";
				}


			} else {
				if ( $bidang == 1 ) {
					echo "<tr><td colspan='13'>Tidak ada data</td></tr>";
				} else { 
					echo "<tr><td colspan='12'>Tidak ada data</td></tr>";
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

 $(document).ready(function(){
	$.each($('.row_nominal'),function(k,v){

		nilai = this.innerHTML;

		this.innerHTML = formatRupiah("'"+nilai+"'","Rp. ");

	});
});
</script>
<!-- end laporan_pengeluaran per proyek -->