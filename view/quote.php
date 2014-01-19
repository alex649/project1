<?php
require_once('../includes/helper.php');

if (!isset($quote_data["symbol"]))
{
    // No quote data
    render('header', array('title' => 'Quote'));
    print "No symbol was provided, or no quote data was found.";
}
else
{
    // Render quote for provided quote data
    render('header', array('title' => 'Quote for '.htmlspecialchars($quote_data["symbol"])));
?>

<table>
    <tr>
        <th>Symbol</th>
        <th>Name</th>
        <th>Last Trade</th>
    </tr>
    <tr>
        <td><?= htmlspecialchars($quote_data["symbol"]) ?></td>
        <td><?= htmlspecialchars($quote_data["name"]) ?></td>
        <td><?= htmlspecialchars($quote_data["last_trade"]) ?></td>
    </tr>
</table>

<?php
}

render('footer');
?>
