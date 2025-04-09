<!-- start laporan_pengeluaran per proyek -->
<style type="text/css">
#previews {

	margin-top:20px !important;
}
</style>
<script type='text/javascript' src="<?php echo base_url();?>assets/plugins/nominal_js/nominal.js"></script>
<div id='previews'>
	<h2>Laporan Rekap Proyek</h2>

	<form style='width:660px;margin:auto;' method='post' name='frm' id='frm' action="<?php echo base_url('Laporan/view_rekap_proyek')?>">
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
				<th width='7%'>Tgl Mulai</th>
				<th width='7%'>Tgl Selesai</th>
				<th width='7%'>Tgl Pembayaran</th>
				<th width='17%'>Nama Proyek</th>
				<th width='5%'>Pelaksana</th>
				<th width='5%'>Kepala Tukang</th>
				<th>Alamat</th>
				<th width='10%'>Nilai Kontrak</th>
				<th width='10%'>Real Cost</th>
				<th width='7%'> Total Pengeluaran </th>
				<th width='7%'> Total Pengeluaran + Potongan Lain</th>
				<th width='7%'> Presentase Pengeluaran</th>
				<th width='10%'> Jml Bayar</th>
				<th width='10%'>Status Pembayaran</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$no = 1;

			$first_tpaid = '';
			$jml_data_detail = $list_detail->result_id->num_rows;
			$totals= $total_all = $total_bayar = 0;

			if ( $jml_data_detail > 0 ) {

				
				foreach ($list_detail->result_array() as $key) {

					$total_pengeluaran = $key['totnom_barang'] + $key['totnom_kas'];
					$total_pengeluaran_pot = $key['totnom_barang'] + $key['totnom_kas'] + $key['pot_other_amount'];
					$persentase = round(($total_pengeluaran / $key['pagu'])*100,2);

					echo "
						<tr>
							<td align='center'>".$no."</td>
							<td align='center'>".$key['tgl_mulai']."</td>
							<td align='center'>".$key['tgl_selesai']."</td>
							<td align='center'>".$key['tgl_bayar']."</td>
							<td>".$key['nama_project']." ( ".$key['nama_bidang']." )</td>
							<td align='center'>".$key['pelaksana']."</td>
							<td align='center'>".$key['kepala_tukang']."</td>
							<td>".$key['alamat']."</td>
							<td align='right' class='row_nominal'>".$key['anggaran']."</td>
							<td align='right' class='row_nominal'>".$key['pagu']."</td>
							<td align='right' class='row_nominal'>".$total_pengeluaran."</td>
							<td align='right' class='row_nominal'>".$total_pengeluaran_pot."</td>
							<td align='right' >".$persentase." %</td>
							<td align='right' class='row_nominal'>".$key['nom_bayar']."</td>

							<td align='center'>".(($key['tpid_bayar'] > 0) ? 'Sudah Dibayar' : 'Belum Dibayar')."</td>
						</tr>
					";

					$no++;
					$total_bayar += $key['nom_bayar'];
					
				}

				echo "<tr>
						<td colspan='13' align='right'>Total</td>
						<td class='row_nominal' align='right'>".$total_bayar."</td>
						<td>&nbsp;</td>
				</tr>";

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