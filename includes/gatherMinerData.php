<?

include_once ('config.php');
include_once ('aws/aws-autoloader.php');


$aws = Aws\Common\Aws::factory(array(
    'key'    => AWS_ACCESSKEYID,
    'secret' => AWS_SECRET,
));

$s3Client = $aws->get('s3');


// Use the high-level iterators (returns ALL of your objects).
$objects = $s3Client->getIterator('ListObjects', array('Bucket' => AWS_BUCKET_NAME));

foreach ($objects as $object) {
	// Get an object.
	$result = $s3Client->getObject(array(
	    'Bucket' => AWS_BUCKET_NAME,
	    'Key'    => $object['Key']
	));

	$minerIdx = count($miners);
    $miners[$minerIdx] = json_decode($result['Body'] . "\n",true);
}
?>