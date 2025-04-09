<!-- start laporan_pengeluaran per proyek -->

	<h2>Laporan Penerimaan Barang

		<?php echo $info_filter?>
	</h2>
	
	<table width='100%' border='1'>
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
											<td align='right' class='row_nominal'>".$total_baru."</td></tr>";
						$subtotal .= "<tr style='font-weight:bold;font-style:oblique'
											><td colspan='11' align='right'> TOTAL DISKON  ".$kode_terima_new_new."</td>
											<td align='center'>&nbsp;</td>
											<td align='right' class='row_nominal'>".$total_diskon_baru."</td></tr>";
						$subtotal .= "<tr style='font-weight:bold;font-style:oblique'
											><td colspan='11' align='right'> PPN  ".$kode_terima_new_new."</td>
											<td align='center'>&nbsp;</td>
											<td align='right' class='row_nominal'>".$ppn_rp_baru."</td></tr>";
						$subtotal .= "<tr style='font-weight:bold;background:#ccc;font-style:oblique'
											><td colspan='11' align='right'> GRAND TOTAL  ".$kode_terima_new_new."</td>
											<td align='center'>&nbsp;</td>
											<td align='right' class='row_nominal'>".$total_amount_baru."</td></tr>";																																	

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
						     <td align='right' class='row_nominal'>".$key['harga_satuan']."</td>
						     <td align='center'>".$key['vol']."</td>
						     <td align='right' class='row_nominal'>".$key['total']."</td>

						 </tr>";

				
				$first_tpid = $key['ttbid'];
				$first_row  = $key['ttbid'];
				$totals     += $key['total'];
				$total_all     += $key['total_amount'];
				$total_terima += $key['vol'];
				$total_terima_all += $key['vol'];
				$ppn_rp = $key['ppn_rp'];
				$total_diskon = $key['total_diskon']*-1;
				$total_amount = $key['total_amount'];
				$kode_terima_new = $key['grn'];

				}

				$data_proj = explode(":",$first_row);
				$subtotal = "<tr style='font-weight:bold;font-style:oblique'><td colspan='11' align='right'>TOTAL SEBELUM DISKON ".$kode_terima_new."</td>
									<td align='center'>".$total_terima."</td>
									<td align='right' class='row_nominal'>".$totals."</td>
							 </tr>";
				$subtotal .= "<tr style='font-weight:bold;font-style:oblique'
											><td colspan='11' align='right'> TOTAL DISKON  ".$kode_terima_new."</td>
											<td align='center'>&nbsp;</td>
											<td align='right' class='row_nominal'>".$total_diskon."</td></tr>";
						$subtotal .= "<tr style='font-weight:bold;font-style:oblique'
											><td colspan='11' align='right'> PPN   ".$kode_terima_new."</td>
											<td align='center'>&nbsp;</td>
											<td align='right' class='row_nominal'>".$ppn_rp."</td></tr>";
						$subtotal .= "<tr style='font-weight:bold;background:#ccc;font-style:oblique'
											><td colspan='11' align='right'> GRAND TOTAL  ".$kode_terima_new."</td>
											<td align='center'>&nbsp;</td>
											<td align='right' class='row_nominal'>".$total_amount."</td></tr>";	
				$subtotal .= "<tr style='font-weight:bold;background:#ccc;font-style:oblique'><td colspan='11' align='right'>TOTAL SEMUA</td>
								<td align='center'>".$total_terima_all."</td>
								<td align='right' class='row_nominal'>".$total_all."</td>
								</tr>";

				echo $subtotal;
			} else {
				echo "<tr><td colspan='13'>Tidak ada data</td></tr>";
			}


			?>
		</tbody>
	</table>


<!-- end laporan_pengeluaran per proyek -->