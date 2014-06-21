<?php

// -----------------------------------------------------------------------------
// -- Very Simple PHP Library for EUtils/Pubmed
// -- https://www.github.com/robertaboukhalil/pubmed
// -----------------------------------------------------------------------------

// EUtils
class EUtils
{
	// -- 
	public $url = 'http://eutils.ncbi.nlm.nih.gov/entrez/eutils/{action}.fcgi';
	public $db  = '';

	// --
	public function query($action, $query, $method = 'GET', $returnObject = true)
	{
		$body = '';

		// Setup URL
		$URL  = str_replace('{action}', $action, $this->url);
		if($method == 'GET')
			$URL .= '?' . http_build_query($query);
		else if($method == 'POST')
			$query['db'] = $this->db;

		// cURL
		$cURL = curl_init($URL);
		curl_setopt($cURL, CURLOPT_POST, $method == 'POST' ? 1 : 0);
		curl_setopt($cURL, CURLOPT_POSTFIELDS, $method == 'POST' ? $query : false);
		curl_setopt($cURL, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($cURL);
		curl_close($cURL);

		if($returnObject)
			return simplexml_load_string($result);

		return $result;
	}
}

// Pubmed
class Pubmed extends EUtils
{
	// -- 
	public function Pubmed()
	{
		$this->db = 'pubmed';
	}

	// -- 
	public function esearch($query, $returnObject = true)
	{
		return $this->query('esearch', $query, 'GET', $returnObject);
	}

	// -- 
	public function efetch($query, $returnObject = true)
	{
		return $this->query('efetch', $query, 'POST', $returnObject);
	}
}

