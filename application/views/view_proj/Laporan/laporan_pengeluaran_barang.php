<!-- start laporan_pengeluaran per proyek -->
<style type="text/css">
#previews {

	margin-top:20px !important;
}
</style>
<script type='text/javascript' src="<?php echo base_url();?>assets/plugins/nominal_js/nominal.js"></script>
<div id='previews'>
	<h2>Laporan PO Celup</h2>

	<form style='width:660px;margin:auto;' method='post' name='frm' id='frm' action="<?php echo base_url('Laporan/view_pengeluaran_barang')?>">
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
				<th width='20%' style="text-align: left">Supplier</th>
				<td>
					<select name='supplier' id='supplier'>
					<option></option>
					<?php  foreach($list_supplier->result_array() as $k){
						$sel_supplier = "";
						if ( $k['tspid'] == $supplier ) {
							$sel_supplier = "selected='selected'";
						}
						echo "<option value='".$k['tspid']."' ".$sel_supplier.">".$k['nama_supplier']."</option>";
					}?>				
					</select>
				</td>
			</tr>
			<tr>
				<th width='20%' style="text-align: left">No PO</th>
				<td><input type='text' name='kode_ajuan' id='kode_ajuan' value='<?php echo $kode_ajuan; ?>'style='width:100%'/></td>
			</tr>
			<tr>
				<th width='20%' style="text-align: left">Nama Barang</th>
				<td><input type='text' name='nam_barang' id='nam_barang' value='<?php echo $nam_barang; ?>'style='width:100%'/></td>
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
				<th>Tgl PO</th>
				<th>No PO</th>
				<th>Supplier</th>
				<th>User PO</th>
				<th>Keterangan</th>
				<th>Nama Barang</th>
				<th width='5%'>Satuan</th>
				<th width='8%'>Harga Satuan</th>
				<th width='5%'>Jumlah</th>
				<th width='8%'>Total</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$no = 0;

			$first_tpid = '';
			$jml_data_detail = $list_detail->result_id->num_rows;
			$totals=$total_all=$total_terima = $total_terima_all =  0;

			if ( $jml_data_detail > 0 ) {

				$first_row = $list_detail->result_array()[0]['ttbid'];
				foreach ($list_detail->result_array() as $key) {
					
					$nos = $tgl_terima = $user_pengaju = $keterangan = $no_faktur = $lokasi = $no_ajuan = $supplier = "";
					if ( $first_tpid != $key['ttbid'] ) {

							$no++;			
							$nos = $no;
							$tgl_terima = date('d-m-Y',strtotime($key['tgl_trans']));
							$user_pengaju = $key['nama_karyawan'];
							$keterangan = $key['keterangan'];
							$no_faktur = $key['no_faktur'];
							$no_ajuan = $key['reff_code'];
							$lokasi = $key['alamat'];
							$supplier = $key['nama_supplier'];
					}

					$subtotal = "";
					if ( $first_row != $key['ttbid']) {

						$data_proj = explode(":",$first_row);
						$total_baru = $totals;
						$total_terima_baru = $total_terima;
						$no_ajuannya_new = $no_ajuannya;
						$subtotal = "<tr style='font-weight:bold;background:#ccc;font-style:oblique'
											><td colspan='9' align='right'> TOTAL ".$no_ajuannya_new."</td>
											<td align='center'>".$total_terima_baru."</td>
											<td align='right' class='row_nominal'>".$total_baru."</td></tr>";

						$totals = $total_terima = 0;
						$no_ajuannya = "";
					}


					echo $subtotal;
					echo "<tr>
						     <td>".$nos."</td>
						     <td align='center'>".$tgl_terima."</td>
						     <td align='center'>".$no_ajuan."</td>
						     <td align='center'>".$supplier."</td>
						     <td align='center'>".$user_pengaju."</td>
						     <td>".$keterangan."</td>
						     <td>".$key['nama_barang']."</td>
						     <td align='center'>".$key['satuan']."</td>
						     <td align='right' class='row_nominal'>".$key['harga_satuan']."</td>
						     <td align='center'>".$key['vol']."</td>
						     <td align='right' class='row_nominal'>".$key['total']."</td>

						 </tr>";

				
				$first_tpid = $key['ttbid'];
				$first_row  = $key['ttbid'];
				$totals     += $key['total'];
				$total_all     += $key['total'];
				$total_terima += $key['vol'];
				$total_terima_all += $key['vol'];
				$no_ajuannya = $key['reff_code'];

				}

				$data_proj = explode(":",$first_row);
				$subtotal = "<tr style='font-weight:bold;background:#ccc;font-style:oblique'><td colspan='9' align='right'>TOTAL ".$no_ajuannya."</td>
									<td align='center'>".$total_terima."</td>
									<td align='right' class='row_nominal'>".$totals."</td>
							 </tr>";
				$subtotal .= "<tr style='font-weight:bold;background:#ccc;font-style:oblique'><td colspan='9' align='right'>TOTAL SEMUA</td>
								<td align='center'>".$total_terima_all."</td>
								<td align='right' class='row_nominal'>".$total_all."</td>
								</tr>";

				echo $subtotal;
			} else {
				echo "<tr><td colspan='11'>Tidak ada data</td></tr>";
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