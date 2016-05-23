<?php
class Page {

	public $is_page = true;
	public $publish_date;
	public $publish_date_string;
	public $keywords;
	public $category;
	public $title;
	public $content;
	public $raw_json;

	function Page($id) {
		// Handle 404 redirect if file doesn't exist, should never get here naturally
		if(!file_exists('./models/data/'.$id.'.json')) {
			header('location: /');
		}
		if($id == "1") { $this->is_page = false; }
		// Get the json data into an array
		$dataString = file_get_contents('./models/data/'.$id.'.json');
		$data = (array) json_decode($dataString);
		// Store data in object
		$this->publish_date_string = $data['publish_date'];
		$this->publish_date = strtotime($data['publish_date']);
		$this->keywords = $data['keywords'];
		$this->category = $data['category'];
		$this->title = $data['title'];
		$this->content = $data['content'];
	}
}
?>