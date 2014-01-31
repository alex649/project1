<?php
/*********************
 * portfolio.php
 *
 * CSCI S-75
 * Project 1
 * Alex Spivakovsky
 *
 * portfolio view
 *********************/

require_once('../includes/helper.php');
render('header', array('title' => 'Portfolio'));
?>

<h1>Portfolio</h1>
<form action=portfolio method=POST>
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
<br>
<br>
<p>Back to <a href="home">Home Page</a></p>
<?php
render('footer');
?>
