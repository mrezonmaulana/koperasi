<style type="text/css">
.text{
  mso-number-format:"\@";/*force text*/
}
</style>
<table border="1">
<thead>
<tr>
<th>No</th>
<th>ID / Nama</th>
<th class="text">NIK</th>
<th>Alamat</th>
<th>No HP</th>
<th>Tipe</th>
</tr>
</thead>
<tbody>
<?php 
$no=1;
foreach($list->result_array() as $i) {
echo "<tr>";
echo "<td>".$no."</td>";
echo "<td>".$i['teid']." / ".$i['nama_karyawan']."</td>";
echo "<td class='text'>".$i['nik']."</td>";
echo "<td>".$i['alamat']."</td>";
echo "<td>".$i['no_telp']."</td>";
echo "<td>".$i['nama_role']."</td>";
echo "</tr>";
$no++;
} 
?>
</tbody>
<tfoot>
</tfoot>
</table>