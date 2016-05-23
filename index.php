<?php
require_once('vendor/Mustache/Autoloader.php');
require_once('models/Page.php');
require_once('models/Homepage.php');

// Only updated featured elements once a day
if(filemtime('featured.json') >= time() - 60*60) {
	$ffs = scandir(dirname(__FILE__).'/models/data/');
	// store newest item by publish date
	$categories = array( "books" => 0, "movies" => 0, "games" => 0 );
	foreach($ffs as $ff) {
		// don't bother with 1.json or the back/this entries
		if($ff == "." || $ff == ".." || $ff == "1.json") { continue; }
		// Get post data
		$itemDataString = file_get_contents('./models/data/'.$ff);
		$itemData = (array) json_decode($itemDataString);
		// Skip items that don't include a category
		if($itemData['category'] == "") { continue; }
		// Get needed data
		$item['category'] = $itemData['category'];
		$item['title'] = $itemData['title'];
		$item['url'] = str_replace(' ', '-', $itemData['title']);
		$item['publish_date_string'] = $itemData['publish_date'];
		// Store only the most recent in each category
		if(strtotime($item['publish_date_string']) > $categories[$item['category']]) {
			$items[$item['category']] = $item;
		}
	}
	// Write the results to a dedicated file
	file_put_contents("./featured.json", json_encode($items));
}

// Set up mustache with page and partial directories
Mustache_Autoloader::register();
$mustache = new Mustache_Engine(array(
	'loader'			=> new Mustache_Loader_FilesystemLoader(dirname(__FILE__).'/views'),
	'partials_loader'	=> new Mustache_Loader_FilesystemLoader(dirname(__FILE__).'/views/partials')
));

// Determine of page is homepage or not
if(!isset($_GET['id'])) {
	$_GET['id'] = '1';
}

// Fetch page data
if($_GET['id'] == '1') {
	$page = new Homepage();
} else {
	$page = new Page($_GET['id']);
}

// Select mustache template
$template = $mustache->loadTemplate("base");
// Store page data as an array
$data = (array) $page;

// Render and output the mustache combined with the data
echo $template->render($data);
?>