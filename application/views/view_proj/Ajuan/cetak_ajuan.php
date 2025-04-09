<script type='text/javascript' src="<?php echo base_url();?>assets/plugins/nominal_js/nominal.js"></script>
<div id="functions">
        <ul>
                <li><a href="#" onclick="window.print();">Print</a></li>
                <li><a href="#" onclick="JavaScript:window.close();">Tutup</a></li>
        </ul>
</div>
<div id='previews'>
	
	<table width='100%' class='pad'>
		<tr>
			<td colspan="3">Tanggal Ajuan. <?php echo date('d F Y',strtotime($list_ajuan->tanggal_ajuan));?></td>
		</tr>
		<Tr><td>&nbsp;</td></Tr>
		
		<tr>
			<td width='5%'>User</td>
			<td width='1%'>:</td>
			<td><?php echo $list_ajuan->pengaju; ?></td>
		</tr>
		<tr>
			<td width='5%'>Nomor</td>
			<td width='1%'>:</td>
			<td><?php echo str_pad($list_ajuan->tpaid,5,"0",STR_PAD_LEFT).'/DN/KAS/'.date('m',strtotime($list_ajuan->tanggal_ajuan)).'/'.date('Y',strtotime($list_ajuan->tanggal_ajuan));?></td>
		</tr>
		<tr>
			<td width='5%'>Lampiran</td>
			<td width='1%'>:</td>
			<td>-</td>
		</tr>
		<tr>
			<td width='5%'>Perihal</td>
			<td width='1%'>:</td>
			<td><?php echo $list_ajuan->keterangan;?></td>
		</tr>
		<Tr><td>&nbsp;</td></Tr>
	    <tr>
			<Td colspan='3'>
				<p>
					<b>Detail Ajuan</b>
				</p>
			</Td>
	    </tr>
	    <tr>
	    	<td colspan='3'>
	    		<table width='100%' class='bdr1'>
	    			 <thead>
	    			 	<tr>
	    			 		<th width='1%'>No</th>
	    			 		<th>Deskripsi</th>
	    			 		<th width='20%' align='center'>Nominal</th>
	    			 	</tr>
	    			 </thead>
	    			 <tbody>
	    			 	<?php 
	    			 		$no_kas = 1;
	    			 		foreach ($list_ajuan_kas->result_array() as $k ) {

	    			 			echo "
	    			 				<tr>
	    			 				<td align='center'>".$no_kas.".</td>
	    			 				<td>".$k['description']."</td>
	    			 				<td align='right' class='row_ajuan'>".$k['nominal']."</td>
	    			 				</tr>
	    			 			";

	    			 			

	    			 			$no_kas++;
	    			 		}
	    			 	?>
	    			 </tbody>
	    			 <tfoot>
	    			 	
	    			 	<tr style='font-weight: bold'>
	    			 		<td colspan='2' align='right'>Total Ajuan Kas</td>
	    			 		<td align='right' class='row_ajuan'><?php echo $list_ajuan->total_ajuan;?></td>
	    			 	</tr>
	    			 </tfoot>
	    		</table>
	    	</td>
	    </tr>
	</table>
	<br>
	<table width='100%' class='pad'>
		<tr>
			<td width="33%">&nbsp;</td>
			<td width="33%">&nbsp;</td>
			<td width="33%" align='center'>Yang Menyetujui</td>
		</tr>
		<tr>
			<td width="33%">&nbsp;</td>
			<td width="33%">&nbsp;</td>
			<td width="33%" align='center'>&nbsp;</td>
		</tr>
		<tr>
			<td width="33%">&nbsp;</td>
			<td width="33%">&nbsp;</td>
			<td width="33%" align='center'>&nbsp;</td>
		</tr>
		<tr>
			<td width="33%">&nbsp;</td>
			<td width="33%">&nbsp;</td>
			<td width="33%" align='center'>( ................................ )</td>
		</tr>
	</table>
</div>

<script type="text/javascript">
$(document).ready(function(){
	$.each($('.row_ajuan'),function(k,v){

		nilai = this.innerHTML;

		this.innerHTML = formatRupiah("'"+nilai+"'","Rp. ");

	});

	var sisa_sbm_aju = document.getElementById('sisa_sebelum_ajuan').innerHTML;
	document.getElementById('sisa_sebelum_ajuan').innerHTML = formatRupiah("'"+sisa_sbm_aju+"'","Rp. ");
});	
</script>