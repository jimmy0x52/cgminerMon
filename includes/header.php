<?

include_once ('./includes/config.php');
include_once ('./includes/all_marketData.php');
include_once ('./includes/dogechainData.php');

?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">

    <title>CGMiner Monitor</title>

    <!-- Core CSS - Include with every page -->
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">

    <!-- Page-Level Plugin CSS - Tables -->
    <link href="css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">

    <!-- SB Admin CSS - Include with every page -->
    <link href="css/sb-admin.css" rel="stylesheet">
</head>

<body>

    <div id="wrapper">

        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/">CGMinerMon</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <li>
                    Balance: <? echo getTotalDoges(); ?>&nbsp;DOGE
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-money fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-messages">
                    	<?
                    	for ($i=0; $i<count($monitoredMarkets); $i++)
						{
							$marketInfo = $monitoredMarkets[$i]['marketdata']['return']['markets'][$monitoredMarkets[$i]['marketname']];
							if($i > 0)
							{
								?>
                        		<li class="divider"></li>
								<?
							}
						?>
                        	<li>
                    			<a href="#">
									<div>
	                                    <strong><?echo $marketInfo['label'];?></strong>
	                                    <span class="pull-right text-muted">
	                                        <em><?echo date("m/d/y h:i:sa",strtotime($marketInfo['recenttrades'][0]['time']));?></em>
	                                    </span>
	                                </div>
	                                <div>1&nbsp;<?echo $marketInfo['primarycode'];?> = <?echo $marketInfo['recenttrades'][0]['price'];?>&nbsp;<?echo $marketInfo['secondarycode'];?></div>
                           	 	</a>
                        	</li>
						<?
						}
						?>
                    </ul>
                    <!-- /.dropdown-messages -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

        </nav>
        <!-- /.navbar-static-top -->

        <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="side-menu">
                	
                    <li>
                        <a href="/"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                    </li>
                   
                </ul>
                <!-- /#side-menu -->
            </div>
            <!-- /.sidebar-collapse -->
        </nav>
        <!-- /.navbar-static-side -->
