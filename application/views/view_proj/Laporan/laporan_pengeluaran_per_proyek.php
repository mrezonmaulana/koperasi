<!-- start laporan_pengeluaran per proyek -->
<style type="text/css">
#previews {

	margin-top:20px !important;
}
</style>
<script type='text/javascript' src="<?php echo base_url();?>assets/plugins/nominal_js/nominal.js"></script>
<div id='previews'>
	<h2>Laporan Pengeluaran Per Proyek</h2>

	<form style='width:660px;margin:auto;' method='post' name='frm' id='frm' action="<?php echo base_url('Laporan/view_pengeluaran_per_proyek')?>">
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
				<th width='20%' style="text-align: left">Pengirim</th>
				<td>
					<select name='pengirim' id='pengirim'>
					<option></option>
					<?php  foreach($list_truck->result_array() as $k){
						$sel_pengirim = "";
						if ( $k['tkid'] == $pengirim ) {
							$sel_pengirim = "selected='selected'";
						}
						echo "<option value='".$k['tkid']."' ".$sel_pengirim.">".$k['nama_kendaraan']." ( ".$k['no_polisi']." )</option>";
					}?>				
					</select>
				</td>
			</tr>
			<tr>
				<th width='20%' style="text-align: left">Jenis Proyek</th>
				<td>
					<select name='bidang' id='bidang'>
					<option></option>
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
			<tr>
				<th width='20%' style="text-align: left">Nama Proyek</th>
				<td><input type='text' name='nam_project' id='nam_project' value='<?php echo $nam_project; ?>'style='width:100%'/></td>
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
				<th>Tgl Mulai</th>
				<th>Tgl Selesai</th>
				<th>Pelaksana</th>
				<th>Jenis Proyek</th>
				<th>Nama Proyek</th>
				<th>Alamat Proyek</th>
				<th width='7%'>Pengirim</th>
				<th width='7%'>Tgl Kirim</th>
				<th width='7%'>No Faktur</th>
				<th>Item Pengeluaran</th>
				<th width='5%'>Jumlah</th>
				<th width='5%'>Satuan</th>
				<th width='8%'>Harga Satuan</th>
				<th width='8%'>Total</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$no = 0;

			$first_tpid = '';
			$jml_data_detail = $list_detail->result_id->num_rows;
			$totals=$total_all=0;

			if ( $jml_data_detail > 0 ) {

				$first_row = $list_detail->result_array()[0]['tpid'].":".$list_detail->result_array()[0]['nama_project'];
				foreach ($list_detail->result_array() as $key) {
					
					$nos = $tgl_mulai = $tgl_selesai = $pelaksana = $nama_bidang = $nama_project = $alamat_project = "";
					if ( $first_tpid != $key['tpid'] ) {

							$no++;			
							$nos = $no;
							$tgl_mulai = date('d-m-Y',strtotime($key['tgl_mulai']));
							$tgl_selesai = ($key['tgl_selesai'] != '') ? date('d-m-Y',strtotime($key['tgl_selesai'])) : '-' ;
							$pelaksana = $key['pelaksana'];
							$nama_bidang = $key['nama_bidang'];
							$nama_project = $key['nama_project'];
							$alamat_project = $key['alamat'];
							$nama_kendaraan = $key['nama_kendaraan'];
					}

					$subtotal = "";
					if ( $first_row != $key['tpid'].":".$key['nama_project'] ) {

						$data_proj = explode(":",$first_row);
						$total_baru = $totals;
						$subtotal = "<tr style='font-weight:bold;background:#ccc;font-style:oblique'><td colspan='14' align='right'> TOTAL ".$data_proj[1]." </td><td align='right' class='row_nominal'>".$total_baru."</td></tr>";

						$totals = 0;
					}

					echo $subtotal;
					echo "<tr>
						     <td>".$nos."</td>
						     <td align='center'>".$tgl_mulai."</td>
						     <td align='center'>".$tgl_selesai."</td>
						     <td>".$pelaksana."</td>
						     <td>".$nama_bidang."</td>
						     <td>".$nama_project."</td>
						     <td>".$alamat_project."</td>
						     <td>".$key['nama_kendaraan']."</td>
						     <td align='center'>".date('d-m-Y',strtotime($key['tgl_kirim']))."</td>
						     <td align='center'>".$key['no_faktur']."</td>
						     <td>".$key['nama_barang']."</td>
						     <td align='center'>".$key['vol']."</td>
						     <td align='center'>".$key['satuan']."</td>
						     <td align='right' class='row_nominal'>".$key['harga_satuan']."</td>
						     <td align='right' class='row_nominal'>".$key['total']."</td>

						 </tr>";

				
				$first_tpid = $key['tpid'];
				$first_row  = $key['tpid'].":".$key['nama_project'];
				$totals     += $key['total'];
				$total_all     += $key['total'];

				}

				$data_proj = explode(":",$first_row);
				$subtotal = "<tr style='font-weight:bold;background:#ccc;font-style:oblique'><td colspan='14' align='right'>TOTAL ".$data_proj[1]."</td><td align='right' class='row_nominal'>".$totals."</td></tr>";
				$subtotal .= "<tr style='font-weight:bold;background:#ccc;font-style:oblique'><td colspan='14' align='right'>TOTAL SEMUA</td><td align='right' class='row_nominal'>".$total_all."</td></tr>";

				echo $subtotal;
			} else {
				echo "<tr><td colspan='15'>Tidak ada data</td></tr>";
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