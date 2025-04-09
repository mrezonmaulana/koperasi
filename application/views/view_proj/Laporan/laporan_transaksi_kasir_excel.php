<!-- start laporan_transaksi_kasir.php !-->

	<h1>Laporan Transaksi Kasir

		<?php echo $info_filter?>
	</h1>

	<table width='100%' border='1'>
		<thead>
			<tr>
				<th width='1%'>No</th>
				<th width="10%">Tgl Trans</th>
				<th width="10%">No Bill</th>
				<th width='10%'>Petugas Kasir</th>
				<th>Nama Pelanggan</th>
				<th width='10%'>Total Tagihan</th>
				<th width='10%'>Total Bayar</th>
				<th width="10%">Sisa Tagihan</th>
			</tr>
		</thead>
		<tbody>
			<?php 
				$no=1;
				$total_tagihan = 0;
				$total_bayar = 0;
				$sisa_tagihan = 0;
				$jml_data_detail = $list_penerimaan->result_id->num_rows;

				if ($jml_data_detail > 0 ) {

					foreach ($list_penerimaan->result_array() as $key => $value) {

					$ket_bayar = $value['cara_bayar'];

					if ( $value['cara_bayar'] == 'DEBET') {

						$ket_bayar = $value['cara_bayar'].' [ '.$value['nama_edc'].' ( No. Batch : '.$value['no_batch'].' , No. Kartu : '.$value['no_kartu'].' ) ]';
					}

					echo "
						<tr>
							<td>".$no."</td>
							<td align='center'>".date('d M Y',strtotime($value['tgl_trans']))."</td>
							<td align='center'>".$value['no_bill']."</td>
							<td align='center'>".$value['user']."</td>
							<td align='center'>".$value['nama_konsumen']."</td>
							<td align='right' class='row_nominal'>".number_format($value['total_amount'],2,',','.')."</td>
							<td align='right' class='row_nominal'>".number_format($value['amount_bayar_partial'],2,',','.')."</td>
							<td align='right' class='row_nominal'>".number_format(($value['total_amount'])-$value['amount_bayar_partial'],2,',','.')."</td>
						</tr>
					";
					$no++;
					$total_tagihan += $value['total_amount'];
					$total_bayar += $value['amount_bayar_partial'];
					$sisa_tagihan += (($value['total_amount'])-$value['amount_bayar_partial']);
				}

				echo "<tr style='background:#eee;font-weight:bold'>
						<td colspan='5' align='right'>TOTAL</td>
						<td align='right' class='row_nominal'>".number_format($total_tagihan,2,',','.')."</td>
						<td align='right' class='row_nominal'>".number_format($total_bayar,2,',','.')."</td>
						<td align='right' class='row_nominal'>".number_format($sisa_tagihan,2,',','.')."</td>
						
					 </tr>";

				} else {
					echo "<tr><td colspan='9'>Tidak ada data</td></tr>";
				}

				
			?>
		</tbody>
	</table>
<!-- end laporan_transaksi_kasir.php !-->