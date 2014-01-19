<?

include_once ('./includes/config.php');

include_once ('./includes/header.php');
?>

        <div id="page-wrapper" class="dashboardData">
           


  		</div>
        <!-- /#page-wrapper -->
<?
$footerScript = "
    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>

	function updateDashboard()
	{
		$('.dashboardData').load('includes/dashboardData.php');
	}

    $(document).ready(function() {
        $('.dataTables').dataTable();
		$('.dashboardData').load('includes/dashboardData.php');
		setInterval(function(){updateDashboard();}, 300000)
    });


    </script>
    ";


include_once ('./includes/footer.php');

?>