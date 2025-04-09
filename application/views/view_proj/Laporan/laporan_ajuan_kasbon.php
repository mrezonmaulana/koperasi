<!-- start laporan_pengeluaran per proyek -->
<style type="text/css">
#previews {

	margin-top:20px !important;
}
</style>
<script type='text/javascript' src="<?php echo base_url();?>assets/plugins/nominal_js/nominal.js"></script>
<div id='previews'>
	<h2>Laporan Ajuan Kasbon</h2>

	<form style='width:660px;margin:auto;' method='post' name='frm' id='frm' action="<?php echo base_url('Laporan/view_ajuan_kasbon')?>">
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
				<th width='20%' style="text-align: left">Pengaju</th>
				<td>
					<select name='pengaju' id='pengaju'>
					<option></option>
					<?php  foreach($list_pelaksana->result_array() as $k){
						$sel_pengaju = "";
						if ( $k['teid'] == $pengaju ) {
							$sel_pengaju = "selected='selected'";
						}
						echo "<option value='".$k['teid']."' ".$sel_pengaju.">".$k['nama_karyawan']."</option>";
					}?>				
					</select>
				</td>
			</tr>
			<tr style="display:none">
				<th width='20%' style="text-align: left">Kepala Tukang</th>
				<td>
					<select name='kepala_tukang' id='kepala_tukang'>
					<option></option>
					<?php  foreach($list_kepalatukang->result_array() as $k){
						$sel_kepalatukang = "";
						if ( $k['teid'] == $kepala_tukang ) {
							$sel_kepalatukang = "selected='selected'";
						}
						echo "<option value='".$k['teid']."' ".$sel_kepalatukang.">".$k['nama_karyawan']."</option>";
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
				<th width='15%'>Tanggal</th>
				<th width='15%'>Nama Pengaju</th>
				<th>Keterangan</th>
				<th width='15%'>Jumlah</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$no = 0;

			$first_tpaid = '';
			$jml_data_detail = $list_detail->result_id->num_rows;
			$totals=$total_all=0;

			if ( $jml_data_detail > 0 ) {

				foreach ($list_detail->result_array() as $key) {

					echo "
						<tr>
							<td align='center'>".$no."</td>
							<td align='center'>".$key['tanggal_ajuan']."</td>
							<td>".$key['pengaju']."</td>
							<td>".$key['keterangan']."</td>
							<td class='row_nominal' align='right'>".$key['jumlah']."</td>
						</tr>
					";

					$no++;

					$totals += $key['jumlah'];
					
				}

				$subtotal = "<tr style='font-weight:bold;background:#ccc;font-style:oblique'><td colspan='4' align='right'>TOTAL</td><td align='right' class='row_nominal'>".$totals."</td></tr>";

				echo $subtotal;

				//echo "<tr style='font-weight:bold'><td colspan='5' align='right'>TOTAL PENGELUARAN</td><td align='right' class='row_nominal'>".$totals."</td></tr>";

			} else {
				echo "<tr><td colspan='5'>Tidak ada data</td></tr>";
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