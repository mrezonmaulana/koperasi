<!-- start laporan_pengeluaran per proyek -->

	<h2>Laporan Ajuan Administrasi

		<?php echo $info_filter?>
	</h2>
	
	<table width='100%' border='1'>
		<thead>
			<tr>
				<th width='1%'>No</th>
				<th width='7%'>Nilai Kontrak</th>
				<th width='7%'>Real Cost</th>
				<th width='7%'>Perusahaan</th>
				<?php if ( $bidang == 1 ) { 
					echo "<th width='7%'>Backup</th>";
					echo "<th width='7%'>Kontrak</th>";
				} else { ?>
					<th width='7%'>Backup & Kontrak</th>
				<?php } ?>
				<th width='7%'>PHO</th>
				<th width='7%'>Keuangan</th>
				<th width='7%'>PMI</th>
				<th width='7%'>BASP</th>
				<th width='7%'>BKD</th>
				<th width='7%'>Ajuan Lain</th>
				<th width='7%'>Jumlah</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$no = 1;

			$first_tpaid = '';
			$jml_data_detail = $list_detail->result_id->num_rows;
			$totals= $total_all = 0;

			if ( $jml_data_detail > 0 ) {

				
				foreach ($list_detail->result_array() as $key) {


					$total = $key['backup']+
								$key['pho']+
								$key['keuangan']+
								$key['pmi']+
								$key['basp']+
								$key['kontrak_ajuan']+
								$key['ajuan_lain']+
								$key['bkd'];
					$tot_backup = $key['backup'] + $key['kontrak_ajuan'];

					echo "
						<tr style='font-style:italic;font-weight:bold;background:#eee'><td colspan='12'> Proyek : ".$key['nama_project']." - ".$key['alamat']." , ".$key['alamat2']."</td></tr>
						<tr>
							<td align='center'>".$no."</td>
							<td align='right' class='row_nominal'>".$key['anggaran']."</td>
							<td align='right' class='row_nominal'>".$key['pagu']."</td>
							<td align='center'>".$key['nama_perusahaan']."</td>
							<td align='right' class='row_nominal'>".$key['backup']."</td>";

							if ( $bidang == 1 ) {
								echo "<td align='right' class='row_nominal'>".$key['kontrak_ajuan']."</td>";
							}

							echo "<td align='right' class='row_nominal'>".$key['pho']."</td>
							<td align='right' class='row_nominal'>".$key['keuangan']."</td>
							<td align='right' class='row_nominal'>".$key['pmi']."</td>
							<td align='right' class='row_nominal'>".$key['basp']."</td>
							<td align='right' class='row_nominal'>".$key['bkd']."</td>
							<td align='right' class='row_nominal'>".$key['ajuan_lain']."</td>
							<td align='right' class='row_nominal'>".$total."</td>
							
						</tr>
					";

					$no++;
					$totals += $total;
					
				}
				if ( $bidang == 1 ) {

					echo "<Tr style='background:#ccc;font-weight:bold;font-style:italic'>
							<td colspan='12' align='right'><b>TOTAL</b></td>
							<td align='right' class='row_nominal'><b>".$totals."</b></td>
							</tr>";

				} else {

					echo "<Tr style='background:#ccc;font-weight:bold;font-style:italic'>
							<td colspan='11' align='right'><b>TOTAL</b></td>
							<td align='right' class='row_nominal'><b>".$totals."</b></td>
							</tr>";
				}


			} else {
				if ( $bidang == 1 ) {
					echo "<tr><td colspan='13'>Tidak ada data</td></tr>";
				} else { 
					echo "<tr><td colspan='12'>Tidak ada data</td></tr>";
				}
			}


			?>
		</tbody>
	</table>

<!-- end laporan_pengeluaran per proyek -->