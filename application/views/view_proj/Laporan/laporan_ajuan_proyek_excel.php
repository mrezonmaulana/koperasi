<!-- start laporan_pengeluaran per proyek -->

	<h2>Laporan Ajuan Kas

		<?php echo $info_filter?>
	</h2>
	
	<table width='100%' class='bdr1 pad'>
		<thead>
			<tr>
				<th width='1%'>No</th>
				<th width='15%'>Tanggal</th>
				<th width='15%'>User Create</th>
				<th>Keterangan</th>
				<th>Rincian Ajuan</th>
				<th width='15%'>Jumlah Pemasukan</th>
				<th width='15%'>Jumlah Pengeluaran</th>
				<th width="10%">Saldo</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$no = 0;

			$first_tpaid = '';
			$jml_data_detail = $list_detail->result_id->num_rows;
			$totals= $total_all = $total_masuk = $total_keluar =  0;
			$total_before_new = $total_before;

			echo "<tr style='font-weight:bold;background:#ccc;font-style:oblique'>
					<td colspan='7' align='right'>Saldo Awal</td>
					<td class='row_nominal' align='right'>".$total_before."</td>
			</tr>";

			if ( $jml_data_detail > 0 ) {

				$first_row = $list_detail->result_array()[0]['tpaid'];
				foreach ($list_detail->result_array() as $key) {

					$nos = $tanggal_ajuan = $pengajunya = $manager = $list_proj =  $keterangan_kas = "";

					if ( $first_tpaid != $key['tpaid'] ) {

						$no++;
						$nos = $no;
						$tanggal_ajuan = date('d-m-Y',strtotime($key['tanggal_ajuan']));
						$pengajunya = $key['pengaju'];
						$manager = "DIREKTUR KEUANGAN";
						$keterangan_kas = $key['keterangan'];

					}

					$subtotal = "";
					if ( $first_row != $key['tpaid'] ) {
						$total_baru = $totals;
						$subtotal = "<tr style='font-weight:bold;background:#ccc;font-style:oblique'><td colspan='5' align='right'>TOTAL</td><td align='right' class='row_nominal'>".$total_baru."</td></tr>";

						$totals = 0;
					}
					

					echo "
						<tr>
							<td align='center'>".$nos."</td>
							<td align='center'>".$tanggal_ajuan."</td>
							<td>".$pengajunya."</td>
							<td>".$keterangan_kas."</td>
							<td>".$key['rincian']."</td>
							<td class='row_nominal' align='right'>".($key['is_out'] == 2  ? $key['nominal'] : 0)."</td>
							<td class='row_nominal' align='right'>".($key['is_out'] == 1  ? $key['nominal'] : 0)."</td>
							<td class='row_nominal' align='right'>".($key['new_nominal'] + $total_before_new)."</td>
						</tr>
					";

					$totals += $key['nominal'];
					$total_before_new += $key['new_nominal'];
					$total_all += $key['nominal'];
					$total_masuk += ($key['is_out'] == 2  ? $key['nominal'] : 0);
					$total_keluar += ($key['is_out'] == 1  ? $key['nominal'] : 0);
					$first_row = $key['tpaid'];
					$first_tpaid = $key['tpaid'];
				}

				//$subtotal = "<tr style='font-weight:bold;background:#ccc;font-style:oblique'><td colspan='5' align='right'>TOTAL</td><td align='right' class='row_nominal'>".$totals."</td></tr>";
				$subtotal = "<tr style='font-weight:bold;background:#ccc;font-style:oblique'><td colspan='5' align='right'>TOTAL SEMUA</td><td align='right' class='row_nominal'>".$total_masuk."</td>
				<td align='right' class='row_nominal'>".$total_keluar."</td>
				<td align='right' class='row_nominal'>".$total_before_new."</td></tr>";

				echo $subtotal;

				//echo "<tr style='font-weight:bold'><td colspan='5' align='right'>TOTAL PENGELUARAN</td><td align='right' class='row_nominal'>".$totals."</td></tr>";

			} else {
				echo "<tr><td colspan='7'>Tidak ada data</td></tr>";
			}


			?>
		</tbody>
	</table>


<!-- end laporan_pengeluaran per proyek -->