<?php

// -----------------------------------------------------------------------------
// -- Configuration ------------------------------------------------------------
// -----------------------------------------------------------------------------
set_time_limit(0);
ini_set('memory_limit', '-1');
include "lib.pubmed.php";
error_reporting(0);

// Only return results from a specific journal
$filterByJournal = ''; // e.g. 'The New England journal of medicine'

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
			// For a complete list of Pubmed publication types,
			// refer to http://www.nlm.nih.gov/mesh/pubtypes2006.html
			$publicationType    = (string) $currentAbstract->MedlineCitation->Article->PublicationTypeList->PublicationType;
			$publicationJournal = (string) $currentAbstract->MedlineCitation->Article->Journal->Title;

			// Filter out articles that aren't published in journal of interest
			if($filterByJournal != '' && strcasecmp($publicationJournal, $filterByJournal) != 0)
				continue;			

			//// OPTIONAL FILTERING:
			//// Only keep track of journal articles (not other types of articles)
			// if(strcasecmp($publicationType, 'Journal Article') != 0)
			// 	continue;
			//// Only keep track of retractions
			// if(strcasecmp($publicationType, 'Retracted Publication') != 0)
			// 	continue;
			//// Only keep track of reviews
			// if(strcasecmp($publicationType, 'Review') != 0)
			//	continue;
			//// Only keep entries in a certain language
			// if(strcasecmp($currentAbstract->MedlineCitation->Article->Language, 'eng') != 0)
			// 	continue;

			$authorList = $currentAbstract->MedlineCitation->Article->AuthorList->Author;
			@$nbAuthors[ count($authorList) ]++;
		}
		ksort($nbAuthors);
	}

	// Output in PHP format so that can be included as a file in 3-*.php
	echo '# -- ' . $year . " --------------------------------------------------\n";
	echo '$data[' . $year . '] = ' . var_export($nbAuthors, $return = true) . ';';
	echo "\n\n";
}


