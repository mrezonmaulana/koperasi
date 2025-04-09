<!-- start laporan_pengeluaran per proyek -->

	<h2>Laporan Ajuan Kasbon

		<?php echo $info_filter?>
	</h2>
	
	<table width='100%' border='1'>
		<thead>
			<tr>
				<th width='1%'>No</th>
				<th width='15%'>Tanggal</th>
				<th width='15%'>Nama Pengaju</th>
				<th>Keterangan</th>
				<th width='15%'>Jumlah</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$no = 0;

			$first_tpaid = '';
			$jml_data_detail = $list_detail->result_id->num_rows;
			$totals=$total_all=0;

			if ( $jml_data_detail > 0 ) {

				foreach ($list_detail->result_array() as $key) {

					echo "
						<tr>
							<td align='center'>".$no."</td>
							<td align='center'>".$key['tanggal_ajuan']."</td>
							<td>".$key['pengaju']."</td>
							<td>".$key['keterangan']."</td>
							<td class='row_nominal' align='right'>".$key['jumlah']."</td>
						</tr>
					";

					$no++;

					$totals += $key['jumlah'];
					
				}

				$subtotal = "<tr style='font-weight:bold;background:#ccc;font-style:oblique'><td colspan='4' align='right'>TOTAL</td><td align='right' class='row_nominal'>".$totals."</td></tr>";

				echo $subtotal;

				//echo "<tr style='font-weight:bold'><td colspan='5' align='right'>TOTAL PENGELUARAN</td><td align='right' class='row_nominal'>".$totals."</td></tr>";

			} else {
				echo "<tr><td colspan='5'>Tidak ada data</td></tr>";
			}


			?>
		</tbody>
	</table>


<!-- end laporan_pengeluaran per proyek -->