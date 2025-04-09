<!-- start laporan_pengeluaran per proyek -->

	<h2>Laporan Rekap Proyek

		<?php echo $info_filter?>
	</h2>
	
	<table width='100%' border='1'>
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

<!-- end laporan_pengeluaran per proyek -->