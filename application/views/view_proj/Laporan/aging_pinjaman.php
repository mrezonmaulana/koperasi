<!-- start laporan_pengeluaran per proyek -->
<style type="text/css">
#previews {

	margin-top:20px !important;
}
</style>
<script type='text/javascript' src="<?php echo base_url();?>assets/plugins/nominal_js/nominal.js"></script>
<div id='previews'>
	<h2>Laporan Umur Pinjaman</h2>

	<form style='width:660px;margin:auto;' method='post' name='frm' id='frm' action="<?php echo base_url('Laporan/view_aging_pinjaman')?>">
		<input type='hidden' name='is_excel' id='is_excel'/>
		<table width='100%' class='bdr1 pad'>
			<tr>
				<th width='20%' style="text-align: left">Tanggal S/d</th>
				<td>
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
				<th width='10%'>Tgl Pinjaman</th>
				<th width="10%">No. Pinjaman</th>
				<th width="10%">Jumlah Pinjaman</th>
				<th width="10%">Tenor</th>
				<th>Anggota</th>
				<th>No. Telp</th>
				<th width="10%">Sisa Piutang</th>
				<th width="10%">Sisa Tenor</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			   $no=1;
			   $total = 0;
			   foreach ($list_report->result_array() as $key => $value) {
			   	echo "<tr>
			   			 <td align='center' >".$no."</td>
			   		     <td  align='center'>".date('d M Y',strtotime($value['tanggal_pinjam']))."</td>
			   		     <td align='center'>".$value['no_reff_pinjaman']."</td>
			   		     <td align='right' class='row_nominal'>".$value['jumlah_pinjaman']."</td>
			   		     <td align='center'>".$value['tenor']."</td>
			   		     <td align='center'>".$value['nama_karyawan']."</td>
			   		     <td align='center'>".$value['no_telp']."</td>
			   		     <td align='right' class='row_nominal'>".$value['sisa_saldo']."</td>
			   		     <td align='center'>".($value['tenor'] - $value['jml_cicilan'])."</td>
			   		  </tr>";

			   		  $no++;
			   		  $total += $value['sisa_saldo'];
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