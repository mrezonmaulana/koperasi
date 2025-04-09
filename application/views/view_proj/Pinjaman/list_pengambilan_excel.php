<style type="text/css">
.text{
  mso-number-format:"\@";/*force text*/
}
</style>
<table border="1">
 <thead>
<tr>
<th>No</th>
<th>Kode</th>
<th width="12%">Tanggal Pinjam</th>
<th>Nasabah</th>
<th>Pengurus</th>
<th class='text' >No HP</th>
<th>Pinjaman</th>

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
echo "<td align='right'>".$i['nominal_pinjam']."</td>";
echo "</tr>";
$no++;
} 
?>
</tbody>
<tfoot>
</tfoot>
</table>