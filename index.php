<?php
// include config and functions
require_once( 'modules/config.php');
require_once( 'modules/functions.php');
?>

<!DOCTYPE html>

<head>
<link rel="stylesheet" type="text/css" href="modules/style.css">
<title><?php echo $siteTitle; ?></title>
<meta http-equiv="refresh" content="<?php echo $autoRefreshInSeconds; ?>">
</head>

<body>
<?php

// get curl handle
$ch = curl_init();

if (!$ch)
{
  myError('Could not initialize curl!');
}

// we have a valid curl handle here
// set some curl options
curl_setopt($ch, CURLOPT_URL, 'http://'.$raiNodeRPCIP.':'.$raiNodeRPCPort);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// -- Get Version String from rai_node ---------------
$rpcVersion = getVersion($ch);
$version = $rpcVersion->{'node_vendor'};

// -- Get get current block from rai_node 
$rpcBlockCount = getBlockCount($ch);
$currentBlock = $rpcBlockCount->{'count'};
$uncheckedBlocks = $rpcBlockCount->{'unchecked'};

// -- Get number of peers from rai_node 
$rpcPeers = getPeers($ch);
$peers = (array) $rpcPeers->{'peers'};
$numPeers = count($peers);

// -- Get node account balance from rai_node 
//$rpcNodeAccountBalance = getAccountBalance($ch, $raiNodeAccount);
//$accBalanceMrai = rawToMrai($rpcNodeAccountBalance->{'balance'},4);
//$accPendingMrai = rawToMrai($rpcNodeAccountBalance->{'pending'},4);

// -- Get Number of Delegators --
$rpcDelegators =  getAccountDelegators($ch,$raiNodeAccount);
$numDelegators =  $rpcDelegators->{'count'};



// -- Get representative info for current node from rai_node 
$rpcNodeRepInfo = getRepresentativeInfo($ch, $raiNodeAccount);
$votingWeight = rawToMrai($rpcNodeRepInfo->{'weight'},0);
$repAccount = $rpcNodeRepInfo->{'representative'};


// close curl handle
curl_close($ch);
?>

<!-- rai Market Data Section-->

<a href="https://raicoin.org/" target="_blank">
	<img src="modules/rai-logo.png" class="logo" alt="Logo Rai"/>
</a>
<h1><?php echo $siteTitle; ?></h1>
<br style="clear:all">

<?php

// get rai data from coinmarketcap
$raiCMCData = getRaiInfoFromCMCTicker($cmcTickerUrl);

if (!empty($raiCMCData))
{ // begin rai market data section

  // beautify market info to be displayed
  $raiMarketCapUSD = "$" . number_format( (float) $raiCMCData->{'market_cap_usd'} / pow(10,9), 2 ) . "B";
  $raiMarketCapEUR =       number_format( (float) $raiCMCData->{'market_cap_eur'} / pow(10,9), 2 ) . "Mâ‚¬";

  $raiPriceUSD = "$" . number_format( (float) $raiCMCData->{'price_usd'} , 2 );
  $raiPriceEUR =       number_format( (float) $raiCMCData->{'price_eur'} , 2 ) . "€";

  $raiChange24hPercent = number_format( (float) $raiCMCData->{'percent_change_24h'}, 2 );
  $raiChange7dPercent  = number_format( (float) $raiCMCData->{'percent_change_7d'}, 2 );

  // color values for positive and negative change
  $colorPos = "darkgreen";
  $colorNeg = "firebrick";

  $raiChange24hPercentHTMLCol = $colorNeg;
  $raiChange7dPercentHTMLCol  = $colorNeg;

  // prepend '+' sign and make it green (hopefully ...)
  if ( $raiChange24hPercent > 0)
  {
    $raiChange24hPercent  = "+" . $raiChange24hPercent;
    $raiChange24hPercentHTMLCol = $colorPos;
  }

  if ( $raiChange7dPercent > 0)
  {
    $raiChange7dPercent  = "+" . $raiChange7dPercent;
    $raiChange7dPercentHTMLCol = $colorPos;
  }

  // append '%''
  $raiChange24hPercent = $raiChange24hPercent . "%";
  $raiChange7dPercent  = $raiChange7dPercent . "%";

?>

<!-- Rai Market Data Table -->

<div class="ticker">
Value: <?php print ($raiPriceUSD . " | " . $raiPriceEUR . " | " . $raiPriceBTC); ?>  <?php print ("<span style='color:" . $raiChange24hPercentHTMLCol . "'>" . $raiChange24hPercent . " (24h)</span> | ". "<span style='color:" . $raiChange7dPercentHTMLCol  . "'>" . $raiChange7dPercent .  " (7d)</span>"); ?>

<?php
}
?>

	</div>
<!-- Node Info -->

	<div class="info">	
<p class="medium">

<!--
Enter your description/text/picture of your cat/whatever here
-->
</p>

<h3>Node Information:</h3>

<p class="medium">Version: 10.0.1<br/>
Current Block: <?php print($currentBlock) ?><br/>
Number of Unchecked Blocks: <?php print($uncheckedBlocks) ?><br/>
Number of Peers: <?php print($numPeers) ?><br/>
Address: <a  href="https://raicoin.org/#explorer/<?php print($raiNodeAccount); ?>" target="_blank"><?php print($raiNodeAccount); ?></a><br/>
Voting Weight: <?php echo $votingWeight; ?> Rai<br/<br/>
Delegators:<?php echo $numDelegators ?></br>
System: <?php echo $serverInfo; ?><br/>
System Load Average: <?php print(getSystemLoadAvg()); ?><br/>
<?php
  $data = shell_exec('uptime');
  $uptime = explode(' up ', $data);
  $uptime = explode(',', $uptime[1]);
  $uptime = $uptime[0].', '.$uptime[1];

  echo ('Current server uptime: '.$uptime.'
');

?>
</p>

</div>

<!-- QR-Code-->
<div class=qrcode>
<h3>Scan or click* to copy<h3>
<h4>Node account</h4>
<a href="rai:<?php echo $raiNodeAccount;?>" class="small"><?php echo $raiNodeAccount;?></a>
<img  src="https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=rai<?php echo $raiNodeAccount; ?>">
<hr  style="width:200px">
<h4>Donations</h4>
<a href="xrb:<?php echo $raiDonationAccount;?>" class="small"><?php echo $raiDonationAccount;?></a>
<img src="https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=rai<?php echo $raiDonationAccount; ?>">
<br/>
<p class=small>*)not implemented yet</p>
</div>

<!-- Footer -->
<hr>
<div class="footer">
<p class="small"><a href="https://github.com/scorerlite/Rainode21-master/" target="_blank">Rainode21</a> is forked from <a href="https://github.com/stefonarch/Nanode21" target="_blank">phpNodeXrai</a></p>
<p class="small">Server Cost: <?php echo $monthlyCosts; ?>/mo. Donations:  
<a  href="https://www.nanode.co/account/<?php print($raiDonationAccount); ?>" target="_blank"><?php print($raiDonationAccount); ?></a>
</p>
</div>											   
</body>
</html>
