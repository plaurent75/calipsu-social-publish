<?php
/**
 * Created by PhpStorm.
 * User: patrice
 * Date: 21/04/2017
 * Time: 16:51
 */
$rows = ['thead', 'tfoot'];
foreach ($rows as $row) {
    echo '<'.$row.'><tr>';
foreach ($cols as $col){
	?>
    <th class="manage-column" scope="col"><?php echo $col ?></th>
	<?php
}
	echo '</tr></'.$row.'>';

}
?>