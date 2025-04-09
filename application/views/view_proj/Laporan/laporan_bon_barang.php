<!-- start laporan bon barang -->
<script type='text/javascript' src="<?php echo base_url();?>assets/plugins/nominal_js/nominal.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	var url = location.href;
	url     = url.split('/');
	var tpid_faktur= url[5];
	var id_fak = tpid_faktur.split('_');

	if ( id_fak[1] == 0 ) {
		document.getElementById('tbl_info').style.display = 'none';
		document.getElementById('is_type_bon').style.display = 'none';
	}	
});
function verifBon(obj)
{
	var url = location.href;
	url     = url.split('/');
	var tpid_faktur= url[5];
	
	$.post("<?php echo base_url('Laporan/verifBon')?>",{id:tpid_faktur,tipe:obj.value},function(data){
		window.location.reload();
	});
}
</script>
<div id="functions">
        <ul>
                <li><a href="#" onclick="window.print();">Print</a></li>
                <li><a href="#" onclick="JavaScript:window.close();">Tutup</a></li>
                <li>
                	<select name='is_type_bon' id='is_type_bon' onchange='verifBon(this);'>
                		<option></option>
                		<option value='1'>Bon Putih</option>
                		<option value='2'>Bon Merah</option>
                		<option value='3'>Bon Putih & Merah</option>
                	</select>
                </li>
        </ul>
</div>
<?php 
	$tgl = "";
	$no_fakturs = "";
	$no_proj = "";
	foreach ($list_project_barang->result_array() as $s) {
		$tgl = $s['tgl_kirim'];
		$no_fakturs = $s['no_faktur'];
		$no_proj = $s['nama_project'];

	}
	?>
<div id='previews'>
	<table width='100%' class='pad'>
		<tr>
			<td width='50%' style='font-size:9pt;font-weight: bold'>PT. PUTRA MANGLID JAYA (PMJ) <br> <span>Paket : <?php echo $no_proj; ?></span></td>
			<td width='50%' align='right'>

				<table width='100%' class='pad' id='tbl_info'>
					<tr>
						<td width='60%' align='right'>Tanggal</td>
						<td align='left'>: <?php echo date('d M Y',strtotime($tgl));?></td>
					</tr>
					
					<tr>
						<td width='60%' align='right'>No Faktur</td>
						<td align='left'>: <?php echo $no_fakturs;?></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

	<table width='100%' class='bdr1 pad'>
		<thead>
			<tr >
				<th  style="background:white !important" widtd='1%'>No</th>
				<th style="background:white !important" >Nama Barang</th>
				<th style="background:white !important"  widtd='23%'>Harga</th>
				<th  style="background:white !important" widtd='1%'>Jumlah</th>
				<th  style="background:white !important" widtd='23%'>Total</th>
			</tr>
		</thead>
		<tbody>
			<?php 
				$no = 1;
				$total = 0;
				$type_bon = "";
			    foreach ($list_project_barang->result_array() as $key) {
			    	echo "
			    	<tr>
			    		<td>".$no."</td>
			    		<td>".$key['nama_barang']."</td>
			    		<td align='right' class='row_ajuan'>".$key['harga_satuan']."</td>
			    		<td align='center'>".$key['vol']."</td>
			    		<td align='right' class='row_ajuan'>".$key['total']."</td>
			    	</tr>
			    	";

			    	$total += $key['total'];
			    	$type_bon = $key['type_bon'];
			    	$no++;
			    }
			?>

			<script type="text/javascript">
				document.getElementById('is_type_bon').value = "<?php echo $type_bon?>";
			</script>
		</tbody>
		<tfoot>
			<tr>
				<td colspan='4' align='right'>Total</td>
				<td align='right' id='total'><?php echo $total;?></td>
			</tr>
		</tfoot>
	</table>
</div>

<script type="text/javascript">
$(document).ready(function(){
	$.each($('.row_ajuan'),function(k,v){

		nilai = this.innerHTML;

		this.innerHTML = formatRupiah("'"+nilai+"'","Rp. ");

	});

	var sisa_sbm_aju = document.getElementById('total').innerHTML;
	document.getElementById('total').innerHTML = formatRupiah("'"+sisa_sbm_aju+"'","Rp. ");
});	
</script>
<!-- end laporan bon barang -->