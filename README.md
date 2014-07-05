Pubmed API
=========

This API grew out of my analysis of scientific authorship over the last century, published [here](https://thewinnower.com/papers/the-rising-trend-in-authorship).

### Sample Code

###### Query abstracts of 100 papers published in 1973
```php
<?php

// Initialize
include "lib.pubmed.php";
$query = new Pubmed();

// Query Pubmed for 100 papers published in 1973
// See http://www.ncbi.nlm.nih.gov/books/NBK3827/ for query information
$result = $query->esearch( Array( 'term'     => '1973[pdat]',
                                  'retmax'   => 100,          // Max fields to return is 100
                                  'retstart' => 0)            // Return first results 
                        );

// Output is IDs of papers
$paperIDs = (array) $result->IdList;

// Prepare next query to fetch abstract (first, make list of the 100 paper IDs)
$strPaperIDs = '';
foreach($paperIDs as $articleID)
	$strPaperIDs .= (string) $articleID;

// Query Pubmed for abstracts (and paper title/author list/etc)
// Returns XML file (set last argument to true to return SimpleXML object)
$articleAbstracts = $query->efetch( Array( 'id'      => $strPaperIDs,
                                           'retmode' => 'xml',
                                           'rettype' => 'abstract'
                                          ), false);

// Output
print_r($articleAbstracts);

?>
```


### Files

* *lib.pubmed.php*: This is the library file
* *1-fetchdata.php*: Fetch XML abstract listings of all papers published between 1913 and 2013
* *2-parsedata.php*: Extracts authorship distribution for each year
* *3-analysis.php*: Calculates average/median/max number of author per year
