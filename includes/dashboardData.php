<?php
include_once ('config.php');
include_once ('gatherMinerData.php');
include_once ('functions.inc.php');

$nr_rigs = count($miners);

?>

<div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">CGMINER Monitoring Status</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                            <div class="table-responsive" id="dashboardData">
    

<table class="table table-striped table-bordered table-hover dataTables">
	<tr>
		<th style="width:150px;">Miner</th>
		<th style="width:120px;">Status</th>
		<th style="width:120px;">Uptime</th>
		<th style="width:100px;">MH/s</th>
		<th style="width:80px;">A</th>
		<th style="width:80px;">R</th>
		<th style="width:50px;">HW</th>
		<th style="width:100px;">Invalid</th>
		<th style="width:120px;">WU</th>
		<th style="width:120px;">WU ratio</th>
	</tr>
	<?php
	$hash_sum          = 0;
	$a_sum             = 0;
	$miners_sum             = 0;
	$hw_sum            = 0;
	$wu_sum            = 0;
	$invalid_sum_ratio = 0;

	for ($i=0; $i<$nr_rigs; $i++)
	{
		$miners[$i]['summary']['STATUS']['STATUS']           = isset($miners[$i]['summary']['STATUS']['STATUS'])           ? $miners[$i]['summary']['STATUS']['STATUS']           : 'OFFLINE';
		$miners[$i]['summary']['SUMMARY']['MHS av']          = isset($miners[$i]['summary']['SUMMARY']['MHS av'])          ? $miners[$i]['summary']['SUMMARY']['MHS av']          : 0;
		$miners[$i]['summary']['SUMMARY']['Accepted']        = isset($miners[$i]['summary']['SUMMARY']['Accepted'])        ? $miners[$i]['summary']['SUMMARY']['Accepted']        : 0;
		$miners[$i]['summary']['SUMMARY']['Rejected']        = isset($miners[$i]['summary']['SUMMARY']['Rejected'])        ? $miners[$i]['summary']['SUMMARY']['Rejected']        : 0;
		$miners[$i]['summary']['SUMMARY']['Hardware Errors'] = isset($miners[$i]['summary']['SUMMARY']['Hardware Errors']) ? $miners[$i]['summary']['SUMMARY']['Hardware Errors'] : 0;
		$miners[$i]['summary']['SUMMARY']['Work Utility']    = isset($miners[$i]['summary']['SUMMARY']['Work Utility'])    ? $miners[$i]['summary']['SUMMARY']['Work Utility']    : 0;
		$miners[$i]['stats']['STATS0']['Elapsed']            = isset($miners[$i]['stats']['STATS0']['Elapsed'])            ? $miners[$i]['stats']['STATS0']['Elapsed']            : 'N/A';
		$miners[$i]['coin']['COIN']['Hash Method']           = isset($miners[$i]['coin']['COIN']['Hash Method'])           ? $miners[$i]['coin']['COIN']['Hash Method']           : 'sha256';

		$invalid_ratio = 0;
		$wu_ratio      = 0;

		if (($miners[$i]['summary']['SUMMARY']['Accepted'] + $miners[$i]['summary']['SUMMARY']['Rejected']) > 0)
		{
			$invalid_ratio = round(($miners[$i]['summary']['SUMMARY']['Rejected'] / ($miners[$i]['summary']['SUMMARY']['Accepted'] + $miners[$i]['summary']['SUMMARY']['Rejected'])) * 100,2);
		}

		if ($miners[$i]['stats']['STATS0']['Elapsed'] == 'N/A')
		{
			$minersunning = 'N/A';
		}
		else
		{
			$t = seconds_to_time($miners[$i]['stats']['STATS0']['Elapsed']);
			$minersunning = $t['d'] . 'd ' . $t['h'] . ':' . $t['m'] . ':' . $t['s'];
		}

		if ($miners[$i]['summary']['SUMMARY']['MHS av'] > 0)
		{
			$wu_ratio = round($miners[$i]['summary']['SUMMARY']['Work Utility'] / ($miners[$i]['summary']['SUMMARY']['MHS av']*1000),3);
			if ($wu_ratio < 0.9 && $t['d']>=1)
			{
				$wu_ratio = '<span class="error">' . $wu_ratio . '</span>';
			}
		}
		
		$hash_sum = $hash_sum + $miners[$i]['summary']['SUMMARY']['MHS av'];
		$a_sum    = $a_sum    + $miners[$i]['summary']['SUMMARY']['Accepted'];
		$miners_sum    = $miners_sum    + $miners[$i]['summary']['SUMMARY']['Rejected'];
		$hw_sum   = $hw_sum   + $miners[$i]['summary']['SUMMARY']['Hardware Errors'];
		$wu_sum   = $wu_sum   + $miners[$i]['summary']['SUMMARY']['Work Utility'];

		?>
		<tr>
			<td><?php echo $miners[$i]['name']?></td>
			<td style="text-align:center"><?php echo $miners[$i]['summary']['STATUS']['STATUS'] == 'S' ? '<span class="ok">ONLINE</span>' : '<span class="error">OFFLINE</span>' ?></td>
			<td style="text-align:center"><?php echo $minersunning?></td>
			<td style="text-align:center"><?php echo $miners[$i]['summary']['SUMMARY']['MHS av']?></td>
			<td style="text-align:center"><?php echo $miners[$i]['summary']['SUMMARY']['Accepted']?></td>
			<td style="text-align:center"><?php echo $miners[$i]['summary']['SUMMARY']['Rejected']?></td>
			<td style="text-align:center"><?php echo $miners[$i]['summary']['SUMMARY']['Hardware Errors'] == 0 ? '<span class="ok">0</span>' : '<span class="error">' . $miners[$i]['summary']['SUMMARY']['Hardware Errors'] . '</span>' ?></td>
			<td style="text-align:center"><?php echo $invalid_ratio <= ALERT_STALES  ? $invalid_ratio . '%' : '<span class="error">' . $invalid_ratio . '%</span>' ?></td>
			<td style="text-align:center"><?php echo $miners[$i]['summary']['SUMMARY']['Work Utility']?></td>
			<td style="text-align:center"><?php echo $wu_ratio?></td>
		</tr>
		<?php
	}

	if ($a_sum > 0)
	{
		$invalid_sum_ratio = round(($miners_sum / $a_sum) * 100, 2);
	}

	?>
	<tr style="font-weight:bold;">
		<td colspan="3"></td>
		<td style="text-align:center;"><?php echo $hash_sum?></td>
		<td style="text-align:center;"><?php echo $a_sum?></td>
		<td style="text-align:center;"><?php echo $miners_sum?></td>
		<td style="text-align:center;"><?php echo $hw_sum == 0 ? '<span class="ok">0</span>' : '<span class="error">' . $hw_sum . '</span>' ?></td>
		<td style="text-align:center"><?php echo $invalid_sum_ratio <= 5  ? $invalid_sum_ratio . '%' : '<span class="error">' . $invalid_sum_ratio . '%</span>' ?></td>
		<td style="text-align:center"><?php echo $wu_sum?></td>
		<td colspan="3"></td>
	</tr>
</table>



                </div>
                <!-- /.col-lg-12 -->
            </div>

<div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Individual Miners</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
    


<?php
for ($i=0; $i<$nr_rigs; $i++)
{
	$pool_active = '';
	if (SHOW_POOLS)
	{
		$pool_priority = 999;
		foreach ($miners[$i]['pools'] as $key=>$pool)
		{
			if ($key != 'STATUS')
			{
				if (($pool['Status'] == 'Alive') && ($pool['Priority'] < $pool_priority))
				{
					$pool_priority = $pool['Priority'];
					$pool_active = '<br><span style="font-weight:normal">Pool ' . $pool['POOL'] . ' - ' . $pool['URL'] . ', user - ' . $pool['User'] . '</span>';
				}
			}
		}
	}
	?>

            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <?php echo $miners[$i]['name']?><?php echo $pool_active?>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive" id="dashboardData">
	<table class="table table-striped table-bordered table-hover dataTables">

		<tr>
			<th style="width:50px;">Device</th>
			<th style="width:120px;">Status</th>
			<th style="width:80px;">Temp</th>
			<th style="width:70px;">Fan</th>
			<th style="width:150px;"><?php echo $miners[$i]['coin']['COIN']['Hash Method'] == 'scrypt' ? 'KH/s' : 'MH/s'?> (5s | avg)</th>
			<th style="width:70px;">A</th>
			<th style="width:70px;">R</th>
			<th style="width:50px;">HW</th>
			<th style="width:100px;">Invalid</th>
			<th style="width:200px;">Last Work</th>
		</tr>
		<?php
		if (isset ($miners[$i]['devs']))
		{
			foreach ($miners[$i]['devs'] as $key=>$dev)
			{
				if ($key != 'STATUS')
				{
					$invalid_ratio = 0;
					$total_shares =  $dev['Accepted'] + $dev['Rejected'];
					if ($total_shares > 0)
					{
						$invalid_ratio = round(($dev['Rejected'] / $total_shares) * 100,2);
					}
					?>
					<tr>
						<td style="text-align:center">
							<?php
							if (isset ($dev['GPU']))
							{
								echo 'GPU ' . $dev['GPU'];
							}
							else if (isset ($dev['ASC']))
							{
								echo 'ASC ' . $dev['ASC'];
							}
							else if (isset ($dev['PGA']))
							{
								echo 'PGA ' . $dev['PGA'];
							}
							?>
						</td>
						<td style="text-align:center"><?php echo $dev['Status'] == 'Alive' ? '<span class="ok">' . $dev['Status'] . '</span>' : '<span class="error">' . $dev['Status'] . '</span>' ?></td>
						<td style="text-align:center"><?php echo $dev['Temperature'] > ALERT_TEMP ? '<span class="error">' . round($dev['Temperature']) . '°C</span>' : round($dev['Temperature']) . '°C' ?></td>
						<td style="text-align:center"><?php echo $dev['Fan Percent']?>%</td>
						<td style="text-align:center">
							<?php
							$stats_second = isset ($dev['MHS 5s']) ? $dev['MHS 5s'] : (isset ($dev['MHS 2s']) ? $dev['MHS 2s'] : 0);
							$stats_second_string = $miners[$i]['coin']['COIN']['Hash Method'] == 'scrypt' ? $stats_second * 1000 . ' | ' . $dev['MHS av'] * 1000 : $stats_second . ' | ' . $dev['MHS av'];
							$stats_ratio = 0;
							if ($dev['MHS av'] > 0)
							{
								$stats_ratio = $stats_second / $dev['MHS av'];
							}

							if (100 - ($stats_ratio * 100) >= ALERT_MHS)
							{
								echo '<span class="error">' . $stats_second_string . '</span>';
							}
							else
							{
								echo $stats_second_string;
							}
							?>
						</td>
						<td style="text-align:center"><?php echo $dev['Accepted']?></td>
						<td style="text-align:center"><?php echo $dev['Rejected']?></td>
						<td style="text-align:center"><?php echo $dev['Hardware Errors'] == 0  ? '<span class="ok">0</span>' : '<span class="error">' . $dev['Hardware Errors'] . '</span>' ?></td>
						<td style="text-align:center"><?php echo $invalid_ratio <= ALERT_STALES  ? $invalid_ratio . '%' : '<span class="error">' . $invalid_ratio . '%</span>' ?></td>
						<td style="text-align:center"><?php echo date('Y-m-d H:i:s', $dev['Last Valid Work']) ?></td>
					</tr>
					<?php
				}
			}
		}
		else
		{
			?>
			<tr>
				<td colspan="10" style="text-align:center" class="error">OFFLINE</td>
			</tr>
			<?php
		}
		?>
	</table>
	


                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>


	<?php
}
?>

