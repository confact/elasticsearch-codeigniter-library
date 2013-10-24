<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
 
class ElasticSearch {
    public $index;
 
    function __construct()
    {
        $CI =& get_instance();
        $CI->config->load("elasticsearch");
        $this->server = $CI->config->item('es_server');
        $this->index = $CI->config->item('index');
    }
 
    function call($path, $method = 'GET', $data = NULL)
    {
        if (!$this->index) throw new Exception('$this->index needs a value');
 
        $url = $this->server . '/' . $this->index . '/' . $path;
 
        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json',
        );
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
 
        switch($method)
        {
            case 'GET':
                break;
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                break;
            case 'PUT':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                break;
            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
        }
 
        $response = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);        
        
        return json_decode($response, true);
    }
 
    //curl -X PUT http://localhost:9200/{INDEX}/
    //this function is to create an index
    function create($map = FALSE)
    {
    	if(!$map) {
        $this->call(NULL, 'PUT');
		} 
		else {
			$this->call(NULL, 'PUT', $map);
		} 
    }
 
    //curl -X GET http://localhost:9200/{INDEX}/_status
    function status()
    {
        return $this->call('_status');
    }
 
    //curl -X GET http://localhost:9200/{INDEX}/{TYPE}/_count -d {matchAll:{}}
    function count($type)
    {
        return $this->call($type . '/_count?' . http_build_query(array(NULL => '{matchAll:{}}')));
    }
 
    //curl -X PUT http://localhost:9200/{INDEX}/{TYPE}/_mapping -d ...
    function map($type, $data)
    {
        return $this->call($type . '/_mapping', 'PUT', $data);
    }
 
    //curl -X PUT http://localhost:9200/{INDEX}/{TYPE}/{ID} -d ...
    function add($type, $id, $data)
    {
        return $this->call($type . '/' . $id, 'PUT', $data);
    }
 
    //curl -X DELETE http://localhost:9200/{INDEX}/
    //delete an indexed item by ID
    function delete($type, $id)
    {
        return $this->call($type . '/' . $id, 'DELETE');
    }
 
    //curl -X GET http://localhost:9200/{INDEX}/{TYPE}/_search?q= ...
    function query($type, $q)
    {
        return $this->call($type . '/_search?' . http_build_query(array('q' => $q)));
    }
	
	function advancedquery($type, $query)
    {
        return $this->call($type . '/_search', 'POST', $query);
    }
 
    function query_wresultSize($type, $query, $size = 999)
    {
        return $this->call($type . '/_search?' . http_build_query(array('q' => $q, 'size' => $size)));
    }
 
    function query_all($query)
    {
        return $this->call('_search?' . http_build_query(array('q' => $q)));
    }
 
    function query_all_wresultSize($query, $size = 999)
    {
        return $this->call('_search?' . http_build_query(array('q' => $q, 'size' => $size)));     
    }
}