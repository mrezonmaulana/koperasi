<!-- start laporan_pengeluaran per proyek -->
<style type="text/css">
#previews {

	margin-top:20px !important;
}
</style>
<script type='text/javascript' src="<?php echo base_url();?>assets/plugins/nominal_js/nominal.js"></script>
<div id='previews'>
	<h2>Laporan Pengeluaran Semua Proyek</h2>

	<form style='width:660px;margin:auto;' method='post' name='frm' id='frm' action="<?php echo base_url('Laporan/view_pengeluaran_semua_proyek')?>">
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
				<th>Nama Proyek</th>
				<th width='10%'>Pelaksana</th>
				<th width='10%'>Nilai Kontrak</th>
				<th width='10%'>Real Cost</th>
				<th width='10%'>Pengeluaran Barang</th>
				<th width='10%'>Pengeluaran Ajuan Proyek</th>
				<th width='10%'>Pengeluaran Ajuan Administrasi</th>
				<th width='10%'>Total Pengeluaran</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$no = 1;

			$first_tpid = '';
			$jml_data_detail = $list_detail->result_id->num_rows;
			$total_barang=$total_ajuan_proyek=$total_adm=$total_kontrak = $total_realcost = $total_keluar = $total_all_pengeluaran = 0;

			if ( $jml_data_detail > 0 ) {

				
				foreach ($list_detail->result_array() as $key) {
					
					$total_keluar = $key['total_barang'] + $key['total_ajuan'] + $key['total_adm'];
					echo "
						<tr>
							<td>".$no."</td>
							<td>".$key['nama_project']."</td>
							<td>".$key['pelaksana']."</td>
							<td class='row_nominal' align='right'>".$key['total_nilai_kontrak']."</td>
							<td class='row_nominal' align='right'>".$key['real_cost']."</td>
							<td class='row_nominal' align='right'>".($key['total_barang'])."</td>
							<td class='row_nominal' align='right'>".($key['total_ajuan'])."</td>
							<td class='row_nominal' align='right'>".($key['total_adm'])."</td>
							<td class='row_nominal' align='right'>".($total_keluar)."</td>
						</tr>
					";

					$no++;
					$total_barang += $key['total_barang'];
					$total_realcost += $key['real_cost'];
					$total_kontrak += $key['total_nilai_kontrak'];
					$total_ajuan_proyek += $key['total_ajuan'];
					$total_adm += $key['total_adm'];
					$total_all_pengeluaran += $total_keluar;
				}

				echo "<tr style='font-weight:bold;background:#ccc;font-style:oblique'>
							<td colspan='3' align='right'>TOTAL</td>
							<td align='right' class='row_nominal'>".$total_kontrak."</td>
							<td align='right' class='row_nominal'>".$total_realcost."</td>
							<td align='right' class='row_nominal'>".$total_barang."</td>
							<td align='right' class='row_nominal'>".$total_ajuan_proyek."</td>
							<td align='right' class='row_nominal'>".$total_adm."</td>
							<td align='right' class='row_nominal'>".$total_all_pengeluaran."</td>
						</tr>";

			} else {
				echo "<tr><td colspan='7'>Tidak ada data</td></tr>";
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