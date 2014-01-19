<?php
require_once('../includes/helper.php');
render('header', array('title' => 'Portfolio'));
?>

<table>
    <tr>
        <th>Symbol</th>
        <th>Shares</th>
    </tr>
<?php
foreach ($holdings as $holding)
{
    print "<tr>";
    print "<td>" . htmlspecialchars($holding["symbol"]) . "</td>";
    print "<td>" . htmlspecialchars($holding["shares"]) . "</td>";
    print "</tr>";
}
?>
</table>

<?php
render('footer');
?>
