<style type="text/css">
.text{
  mso-number-format:"\@";/*force text*/
}
</style>
<div id="functions">
    <ul>
        <li><a href="#" onclick="window.print();">Print</a></li>
        <li><a href="#" onclick="JavaScript:window.close();">Close</a></li>
    </ul>
</div>
<div id='previews'>
  <h1>List Simpanan Sukarela</h1>
  <table class="bdr1 pad" width="100%">
 <thead>
<tr>
<th width="1%">No</th>
<th width="5%">Kode</th>
<th width="12%">Tanggal</th>
<th>Nasabah</th>
<th>Pengurus</th>
<th class='text' >No HP</th>
<th>Nominal</th>

</tr>
</thead>
<tbody>
<?php 
$no=1;
foreach($list->result_array() as $i) {
echo "<tr>";
echo "<td align='center'>".$no."</td>";
echo "<td align='center'>".$i['no_reff']."</td>";
echo "<td align='center'>".date('Y-m-d',strtotime($i['tanggal']))."</td>";
echo "<td>".$i['nama_karyawan']."</td>";
echo "<td>".$i['pengurus']."</td>";
echo "<td  class='text' align='center'>".$i['no_telp']."</td>";
echo "<td align='right'>".number_format($i['nominal_pinjam'],0,',','.')."</td>";
echo "</tr>";
$no++;
} 
?>
</tbody>
<tfoot>
</tfoot>
</table>
</div>