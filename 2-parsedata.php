<?php

// -----------------------------------------------------------------------------
// -- Configuration ------------------------------------------------------------
// -----------------------------------------------------------------------------
set_time_limit(0);
ini_set('memory_limit', '-1');
include "lib.pubmed.php";
error_reporting(0);

// -----------------------------------------------------------------------------
// -- Parse all XML files for each year & keep track of authorship distribution
// -----------------------------------------------------------------------------
for($year = 1913; $year <= 2013; $year++)
{
	$nbAuthors = array();

	//
	foreach(glob('data/' . $year . '/' . $year . '-*') as $fileDir)
	{
		$articleAbstracts = simplexml_load_string(file_get_contents($fileDir));
		$i += count($articleAbstracts);
		$retStart += count($articleAbstracts);

		foreach($articleAbstracts as $currentAbstract)
		{
			$authorList = $currentAbstract->MedlineCitation->Article->AuthorList->Author;
			@$nbAuthors[ count($authorList) ]++;
		}
		ksort($nbAuthors);
	}

	//
	echo '# -- ' . $year . " --------------------------------------------------\n";
	echo '$data[' . $year . '] = ' . var_export($nbAuthors, $return = true) . ';';
	echo "\n\n";
}


