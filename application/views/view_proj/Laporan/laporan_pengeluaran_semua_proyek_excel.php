<!-- start laporan_pengeluaran per proyek -->

	<h2>Laporan Pengeluaran Semua Proyek

		<?php echo $info_filter?>
	</h2>
	
	<table width='100%' border='1'>
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


<!-- end laporan_pengeluaran per proyek -->