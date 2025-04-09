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
  <h1>List Anggota Koperasi</h1>
  <table class="bdr1 pad" width="100%">
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
  echo "<td align='center'>".$no."</td>";
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
</div>