<!-- start cetak_ajuankas -->
<script type='text/javascript' src="<?php echo base_url();?>assets/plugins/nominal_js/nominal.js"></script>
<div id="functions">
        <ul>
                <li><a href="#" onclick="window.print();">Print</a></li>
                <li><a href="#" onclick="JavaScript:window.close();">Tutup</a></li>
        </ul>
</div>

<div id='previews'>
<div style='border:1px solid #ddd'>
	<table width='100%' class='bdr1pad'>
		<tr>
			<td align='center' width='33%' rowspan="2" style='vertical-align: middle;font-size:11pt'>
				<div style="width:220px;">
					<span style="float:left">
					<img src="<?php echo base_url();?>assets/images/warung_oksi_menu.png" width="50">
					</span>	
					<span style="float:left;margin-top:15px;margin-left:5px;font-weight: bold">
					TEXTILE COMPANY
					</span><br style="clear: both">
				</div>
			</td>
			<td align='center' width='33%' rowspan="2" style='vertical-align: middle'><h3>BON SEMENTARA</h3></td>
			<td width='33%' >No : <?php echo str_pad($list_ajuankas->takid,5,"0",STR_PAD_LEFT).'/DN/KASBON/'.date('m',strtotime($list_ajuankas->tanggal_ajuan)).'/'.date('Y',strtotime($list_ajuankas->tanggal_ajuan));?></td>
		</tr>
		<tr>
			<td>Tanggal : <?php echo date('d F Y',strtotime($list_ajuankas->tanggal_ajuan)); ?></td>
		</tr>
	</table>
	<br>
	<table width='50%' class='pad' style='margin:auto'>
		<tr>
			<td width='30%'>Karyawan</td>
			<td>: <input type='text' style='border-top:none;border-left:none;border-right:none;border-bottom: 1px solid dotted;' readonly="" value="<?php echo $list_ajuankas->nama_pengaju;?>" /></td>
		</tr>
		<tr>
			<td>Jumlah</td>
			<td>: <input type='text' style='border-top:none;border-left:none;border-right:none;border-bottom: 1px solid dotted;' readonly="" id='jumlah' value="<?php echo $list_ajuankas->jumlah;?>"/></td>
		</tr>
		<tr>
			<td>Keperluan</td>
			<td>: <input type='text' style='border-top:none;border-left:none;border-right:none;border-bottom: 1px solid dotted;' readonly="" value="<?php echo $list_ajuankas->keterangan;?>" /></td>
		</tr>
	</table>
</div>
	<table width='100%' class='bdr5 pad'>
		<tr>
			<td width='33%' align='center'>Penerima</td>
			<td width='33%' align='center'>Mengetahui</td>
			<td width='33%' align='center'>Menyetujui</td>
		</tr>

		<tr>
			<td width='33%'>&nbsp;</td>
			<td width='33%'>&nbsp;</td>
			<td width='33%'>&nbsp;</td>
		</tr>

		<tr>
			<td width='33%'>&nbsp;</td>
			<td width='33%'>&nbsp;</td>
			<td width='33%'>&nbsp;</td>
		</tr>

		<tr>
			<td width='33%' align='center'><?php echo $list_ajuankas->nama_pengaju;?></td>
			<td width='33%' align='center'>Kepala Bagian</td>
			<td width='33%' align='center'>Manager Keuangan</td>
		</tr>
	</table>
</div>
<script type="text/javascript">
$(document).ready(function(){

	var sisa_sbm_aju = document.getElementById('jumlah').value;
	document.getElementById('jumlah').value = formatRupiah("'"+sisa_sbm_aju+"'","Rp. ");

});
</script>
<!-- end cetak_ajuankas -->