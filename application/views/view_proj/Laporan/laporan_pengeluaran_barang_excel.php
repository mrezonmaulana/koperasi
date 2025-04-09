<!-- start laporan_pengeluaran per proyek -->

	<h2>Laporan PO Celup

		<?php echo $info_filter?>
	</h2>
	
	<table width='100%' border='1'>
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
						$subtotal = "<tr style='font-weight:bold;background:#ccc;font-style:oblique'
											><td colspan='9' align='right'> TOTAL</td>
											<td align='center'>".$total_terima_baru."</td>
											<td align='right' class='row_nominal'>".$total_baru."</td></tr>";

						$totals = $total_terima = 0;
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

				}

				$data_proj = explode(":",$first_row);
				$subtotal = "<tr style='font-weight:bold;background:#ccc;font-style:oblique'><td colspan='9' align='right'>TOTAL</td>
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


<!-- end laporan_pengeluaran per proyek -->