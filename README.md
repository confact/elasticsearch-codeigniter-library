elasticsearch-codeigniter-library
=================================

A small library to make search queries and create and add indexes.


## How to index data with the library
You create data in an array to pass it to elasticsearch. You probably want to specify the Key for the document and show the result. Like this:

	$id = 1337;
	$data = array("name"=>"nisse", "age"=>"14", "sex"=>"male");
	var_dump($this->elasticsearch->add("people", $id, $data));
	
This will save the array to the elasticsearch. "people" is the collection, the index where you want to save it.

## CRUD Operations

# CREATE
	$id = 1337;
	$data = array("name"=>"nisse", "age"=>"14", "sex"=>"male");
	$return = $this->elasticsearch->add("people", $id, $data);

# READ
	$id = 1337;
	$this->elasticsearch->get("people", $id);

# UPDATE
	$id = 1337;
	$data = array(
		"id" => $id,
		"name"=>"nisse", 
		"age"=>"14", 
		"sex"=>"male"
	);
	$return = $this->elasticsearch->add("people", $id, $data);

# DELETE
	$id = 1337;
	$this->elasticsearch->delete("people", $id);
