<!-- start laporan_pengeluaran per proyek -->
<style type="text/css">
#previews {

	margin-top:20px !important;
}
</style>
<script type='text/javascript' src="<?php echo base_url();?>assets/plugins/nominal_js/nominal.js"></script>
<div id='previews'>
	<h2>Laporan Penerimaan Barang</h2>

	<form style='width:660px;margin:auto;' method='post' name='frm' id='frm' action="<?php echo base_url('Laporan/view_penerimaan_barang')?>">
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
				<th width='20%' style="text-align: left">Nama Barang</th>
				<td><input type='text' name='nam_barang' id='nam_barang' value='<?php echo $nam_barang; ?>'style='width:100%'/></td>
			</tr>
			<tr>
				<th width='20%' style="text-align: left">No Terima</th>
				<td><input type='text' name='kd_terima' id='kd_terima' value='<?php echo $kd_terima; ?>'style='width:100%'/></td>
			</tr>
			<tr>
				<th width='20%' style="text-align: left">No PO</th>
				<td><input type='text' name='kd_ajuan' id='kd_ajuan' value='<?php echo $kd_ajuan; ?>'style='width:100%'/></td>
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
				<th width='10%'>Tgl Terima</th>
				<th width='12%'>No. Terima</th>
				<th width='12%'>No. PO</th>
				<th>Supplier</th>
				<th>Keterangan</th>
				<th width='7%'>No Faktur</th>
				<th width='7%'>User Terima</th>
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
					
					$nos = $tgl_terima = $nama_supplier = $keterangan = $no_faktur = $kode_terima = $kode_po = $user_terima =  "";
					if ( $first_tpid != $key['ttbid'] ) {

							$no++;			
							$nos = $no;
							$tgl_terima = date('d-m-Y',strtotime($key['tgl_trans']));
							$nama_supplier = $key['nama_supplier'];
							$kode_terima = $key['grn'];
							$kode_po = $key['po'];
							$keterangan = $key['keterangan'];
							$no_faktur = $key['no_faktur'];
							$user_terima = $key['user_terima'];
					}

					$subtotal = "";
					if ( $first_row != $key['ttbid']) {

						$data_proj = explode(":",$first_row);
						$total_baru = $totals;
						$total_terima_baru = $total_terima;
						$ppn_rp_baru = $ppn_rp;
						$total_diskon_baru = $total_diskon;
						$total_amount_baru = $total_amount;
						$kode_terima_new_new = $kode_terima_new;

						$subtotal = "<tr style='font-weight:bold;font-style:oblique'
											><td colspan='11' align='right'> TOTAL SEBELUM DISKON ".$kode_terima_new_new."</td>
											<td align='center'>".$total_terima_baru."</td>
											<td align='right' class='row_nominal'>".number_format($total_baru,2,',','.')."</td></tr>";
						$subtotal .= "<tr style='font-weight:bold;font-style:oblique'
											><td colspan='11' align='right'> TOTAL DISKON  ".$kode_terima_new_new."</td>
											<td align='center'>&nbsp;</td>
											<td align='right' class='row_nominal'>".number_format($total_diskon_baru,2,',','.')."</td></tr>";
						$subtotal .= "<tr style='font-weight:bold;font-style:oblique'
											><td colspan='11' align='right'> PPN  ".$kode_terima_new_new."</td>
											<td align='center'>&nbsp;</td>
											<td align='right' class='row_nominal'>".number_format($ppn_rp_baru,2,',','.')."</td></tr>";
						$subtotal .= "<tr style='font-weight:bold;background:#ccc;font-style:oblique'
											><td colspan='11' align='right'> GRAND TOTAL  ".$kode_terima_new_new."</td>
											<td align='center'>&nbsp;</td>
											<td align='right' class='row_nominal'>".number_format($total_amount_baru,2,',','.')."</td></tr>";																																	

						$totals = $total_terima = $ppn_rp = $total_diskon = $total_amount= 0;
						$kode_terima_new = "";
					}

					echo $subtotal;
					echo "<tr>
						     <td>".$nos."</td>
						     <td align='center'>".$tgl_terima."</td>
						     <td align='center'>".$kode_terima."</td>
						     <td align='center'>".$kode_po."</td>
						     <td align='center'>".$nama_supplier."</td>
						     <td>".$keterangan."</td>
						     <td align='center'>".$no_faktur."</td>
						     <td align='center'>".$user_terima."</td>
						     <td>".$key['nama_barang']."</td>
						     <td align='center'>".$key['satuan']."</td>
						     <td align='right' class='row_nominal'>".number_format($key['harga_satuan'],2,',','.')."</td>
						     <td align='center'>".round($key['vol'])."</td>
						     <td align='right' class='row_nominal'>".number_format($key['total'],2,',','.')."</td>

						 </tr>";

				
				$first_tpid = $key['ttbid'];
				$first_row  = $key['ttbid'];
				$totals     += $key['total'];
				$total_all     += $key['total'];
				$total_terima += $key['vol'];
				$total_terima_all += $key['vol'];
				$ppn_rp = $key['ppn_rp'];
				$total_diskon = $key['total_diskon'];
				$total_amount = $key['total_amount'];
				$kode_terima_new = $key['grn'];

				}

				$data_proj = explode(":",$first_row);
				$subtotal = "<tr style='font-weight:bold;font-style:oblique'><td colspan='11' align='right'>TOTAL SEBELUM DISKON ".$kode_terima_new."</td>
									<td align='center'>".$total_terima."</td>
									<td align='right' class='row_nominal'>".number_format($totals,2,',','.')."</td>
							 </tr>";
				$subtotal .= "<tr style='font-weight:bold;font-style:oblique'
											><td colspan='11' align='right'> TOTAL DISKON  ".$kode_terima_new."</td>
											<td align='center'>&nbsp;</td>
											<td align='right' class='row_nominal'>".number_format($total_diskon,2,',','.')."</td></tr>";
						$subtotal .= "<tr style='font-weight:bold;font-style:oblique'
											><td colspan='11' align='right'> PPN   ".$kode_terima_new."</td>
											<td align='center'>&nbsp;</td>
											<td align='right' class='row_nominal'>".number_format($ppn_rp,2,',','.')."</td></tr>";
						$subtotal .= "<tr style='font-weight:bold;background:#ccc;font-style:oblique'
											><td colspan='11' align='right'> GRAND TOTAL  ".$kode_terima_new."</td>
											<td align='center'>&nbsp;</td>
											<td align='right' class='row_nominal'>".number_format($total_amount,2,',','.')."</td></tr>";	
				$subtotal .= "<tr style='font-weight:bold;background:#ccc;font-style:oblique'><td colspan='11' align='right'>TOTAL SEMUA</td>
								<td align='center'>".$total_terima_all."</td>
								<td align='right'>".number_format($total_all,2,',','.')."</td>
								</tr>";

				echo $subtotal;
			} else {

				echo "<tr><td colspan='13'>Tidak ada data</td></tr>";
				
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
	/*$.each($('.row_nominal'),function(k,v){

		nilai = this.innerHTML;

		this.innerHTML = formatRupiah("'"+nilai+"'","Rp. ");

	});*/
});
</script>
<!-- end laporan_pengeluaran per proyek -->