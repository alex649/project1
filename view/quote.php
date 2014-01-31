<?php
/*********************
 * quote.php
 *
 * CSCI S-75
 * Project 1
 * Alex Spivakovsky
 *
 * quote view
 *********************/

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

<form method=POST action=portfolio>
    <table>
        <tr>
            <th>Symbol</th>
            <th>Name</th>
            <th>Last Trade</th>
	    <th>Shares</th>
        </tr>
        <tr>
            <td><input type=text name="symbol" value="<?= htmlspecialchars($quote_data['symbol']) ?>"></td>
	    <td><input type=text name="name" value="<?= htmlspecialchars($quote_data['name']) ?>"></td>
            <td><input type=text name="last_trade" value="<?= htmlspecialchars($quote_data['last_trade']) ?>"></td>
	    <td><input type=text name="shares" size=6 maxlength=7></td>
	    <td><input type=submit name="submit" value="Buy Shares"></td>
        </tr>
    </table>
</form>
<br>
<br>
<p>Back to <a href="home">Home Page</a></p>

<?php
}

render('footer');
?>
