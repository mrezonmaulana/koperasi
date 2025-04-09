<!-- start laporan_pengeluaran per proyek -->
<style type="text/css">
#previews {

	margin-top:20px !important;
}
</style>
<script type='text/javascript' src="<?php echo base_url();?>assets/plugins/nominal_js/nominal.js"></script>
<div id='previews'>
	<h2>Laporan Pembayaran Pinjaman Anggota</h2>

	<form style='width:660px;margin:auto;' method='post' name='frm' id='frm' action="<?php echo base_url('Laporan/view_cicilan_anggota')?>">
		<input type='hidden' name='is_excel' id='is_excel'/>
		<table width='100%' class='bdr1 pad'>
			<tr>
				<th width='20%' style="text-align: left">Tanggal</th>
				<td>
					<input type="text" name="tgl1" value='<?php echo $tgl1;?>' class="reservation">
					s/d
					<input type="text" name="tgl2" value='<?php echo $tgl2;?>' class="reservation">
				</td>
			</tr>
		
			<tr>
				<td colspan='2'>
					<input type='button' value='Cari' onclick="document.getElementById('is_excel').value=0;document.frm.submit();">
					<input type='button' value='Excel' onclick="document.getElementById('is_excel').value=1;document.frm.submit();">
					<input type="button" value="Tutup" onclick="window.close()"/>
				</td>
			</tr>
		</table>


	</form>

	<br><br><br>
	<table width='100%' class='bdr1 pad'>
		<thead>
			<tr>
				<th width='1%'>No</th>
				<th width='10%'>Tgl Bayar</th>
				<th width="10%">No. Pembayaran</th>
				<th width="10%">No. Pinjaman</th>
				<th>Anggota</th>
				<th>No. Telp</th>
				<th>Pengurus</th>
				<th width="10%">Nominal</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			   $no=1;
			   $total = 0;
			   foreach ($list_report->result_array() as $key => $value) {
			   	echo "<tr>
			   			 <td align='center' >".$no."</td>
			   		     <td  align='center'>".date('d M Y',strtotime($value['tanggal']))."</td>
			   		     <td align='center'>".$value['no_reff']."</td>
			   		     <td align='center'>".$value['no_reff_pinjaman']."</td>
			   		     <td align='center'>".$value['nama_karyawan']."</td>
			   		     <td align='center'>".$value['no_telp']."</td>
			   		     <td align='center'>".$value['pengurus']."</td>
			   		     <td align='right' class='row_nominal'>".$value['nominal_net_cicilan']."</td>
			   		  </tr>";

			   		  $no++;
			   		  $total += $value['nominal_net_cicilan'];
			   }

			   if ( $total > 0 ) {
			   	   echo "<tr><td colspan='7' align='right'><b>TOTAL</b></td><td align='right' class='row_nominal'>".$total."</td>";
			   }
			?>
		</tbody>
	</table>
</div>
<script type="text/javascript">
 $(function() {
    $('.reservation').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true
    }, 
    function(start, end, label) {
        var years = moment().diff(start, 'years');
        
    });
});

 $(document).ready(function(){
	$.each($('.row_nominal'),function(k,v){

		nilai = this.innerHTML;

		this.innerHTML = formatRupiah("'"+nilai+"'","Rp. ");

	});
});
</script>
<!-- end laporan_pengeluaran per proyek -->