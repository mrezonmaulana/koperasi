<!-- start laporan_pengeluaran per proyek -->

	<h2>Laporan Pengeluaran Per Proyek

		<?php echo $info_filter?>
	</h2>
	
	<table width='100%' border='1'>
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
			$totals=$total_all = 0;

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


<!-- end laporan_pengeluaran per proyek -->