<?php
require_once('Page.php');

class Homepage extends Page {

	public $is_home = true;
	public $welcome_message;
	public $featured;

	function Homepage() {
		parent::Page('1');
		// Fetch data for welcome message
		$dataString = file_get_contents('./models/data/1.json');
		$data = (array) json_decode($dataString);
		$this->welcome_message = $data['welcome_message'];
		// Fetch data for featured content
		$dataString = file_get_contents('./featured.json');
		$data = (array) json_decode($dataString);
		$this->featured[] = new Featured((array) $data['books']);
		$this->featured[] = new Featured((array) $data['games']);
		$this->featured[] = new Featured((array) $data['movies']);
	}
}

class Featured {

	public $title;
	public $url;
	public $category;
	public $publish_date_string;

	function Featured($data) {
		// store data from array in object
		$this->title = $data['title'];
		$this->url = $data['url'];
		$this->category = $data['category'];
		$this->publish_date_string = $data['publish_date_string'];
	}
}
?>