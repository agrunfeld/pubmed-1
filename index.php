<?php

// -----------------------------------------------------------------------------
// -- Configuration ------------------------------------------------------------
// -----------------------------------------------------------------------------
set_time_limit(0);
ini_set('memory_limit', '-1');
include "lib.pubmed.php";
error_reporting(0);

// //
// $year 		= 1913;
// $retMax 	= 10;
// $retStart 	= 0;

// //
// $query     	= new Pubmed();
// $result 	= $query->esearch(Array(
// 	'term'		=> $year . '[pdat]',	// $journal . '[journal] AND ' .
// 	'retmax'	=> $retMax,				// Max fields to return is 100k
// 	'retstart'	=> $retStart)
// );

// print_r($result);
// //$query->query('', $method = 'GET', $body = '', $returnJSON = false);
// exit;



// -----------------------------------------------------------------------------
// -- Fetch data for papers published between 1813 and 2013
// -----------------------------------------------------------------------------
for($year = 2010; $year <= 2013; $year++)
{
	echo "\n" . $year;
	//
	$retStart	= 0;
	$retMax     = 3600;	// Fetch 10k elements at a time (~40 MB)
	$retEnd     = 10e6;	// Total # of items to retrieve. When do first query, will update this value
	//
	$totNbArticles = 0;
	$pageNb     = 1;
	$currYear   = array();
	//
	$nbAuthors  = array();

	@mkdir('data/' . $year . '/');

	//
	for($i = 0; $i < $retEnd; $i++)
	{
		$fileDir = "data/$year/$year-" . ($pageNb++);

		if(file_exists($fileDir))
		{
			$articleAbstracts = file_get_contents($fileDir);
			$articleAbstracts = simplexml_load_string($articleAbstracts);
			$i += count($articleAbstracts);
			$retStart += count($articleAbstracts);
			echo ".";
		}
		else
		{

			// -- Query Pubmed: search for all publications during $year
			$query     	= new Pubmed();
			$result 	= $query->esearch(Array(
				'term'		=> $year . '[pdat]',	// $journal . '[journal] AND ' .
				'retmax'	=> $retMax,				// Max fields to return is 100k
				'retstart'	=> $retStart)
			);

			//
			$i         += count($result->IdList->Id);
			$retStart  += count($result->IdList->Id);
			$retEnd     = $result->Count;

			//
			//$currYear = array_merge($currYear, (array) $result->IdList->Id);
			$currPage = (array) $result->IdList;
			$currPage = $currPage['Id'];

			// -- Setup next query to get authors for each paper
			$allIDs = '';
			$articleNb = 0;
			foreach($currPage as $articleID)
			{
				$allIDs .= (string) $articleID;
				$articleNb++;
				$totNbArticles++;

				if( $articleNb != count($currPage) )
					$allIDs .= ',';
			}
			if(empty($allIDs))
				break;

			sleep(0.5);

			// -- Query Pubmed: fetch paper information using IDs obtained earlier
			$articleAbstracts = $query->efetch( Array(
										'id' => $allIDs,
										'retmode' => 'xml',
										'rettype' => 'abstract',
									), false);

			file_put_contents( $fileDir, $articleAbstracts );
			echo ".";
			//$articleAbstracts = simplexml_load_string($articleAbstracts);
			sleep(0.5);
		}

		// 
		// foreach($articleAbstracts as $currentAbstract)
		// {
		// 	@$nbAuthors[ count($currentAbstract->MedlineCitation->Article->AuthorList->Author) ]++;
		// }
		// ksort($nbAuthors);
	}
	echo $i . "\n";

	// print_r($nbAuthors);
}








