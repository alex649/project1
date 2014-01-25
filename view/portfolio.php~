<?php
require_once('../includes/helper.php');
render('header', array('title' => 'Portfolio'));
?>

<h1>Portfolio</h1>
<form action=sell method=POST>
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
    print "<td name=" . $holding["symbol"] . ">" . htmlspecialchars($holding["shares"]) . "</td>";
    print "<td>" . "<input type=submit name=" . $holding['symbol'] . " ";
    print "value='Sell'>";
    print "</td>";
    print "</tr>";
}
?>
</table>
</form>

<?php
render('footer');
?>
