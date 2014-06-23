<?php

include "data.php";

// -- For each year, largest number of authors seen on a paper -----------------
#foreach($data as $year => $nbAuthors)
#	echo $year . "\t" . max( array_keys($nbAuthors) ) . "\n";

// -- For each year, find papers published by >1k authors! ---------------------
#foreach($data as $year => $nbAuthors)
#{
#	echo "\n" . $year . "\n";
#	$sum = 0;
#	for($i = 1000; $i < max(array_keys($nbAuthors)); $i++)
#	{
#		if(array_key_exists($i, $nbAuthors))
#		{
#			echo "\t" . $nbAuthors[$i] . " x " . $i . " authors\n";
#			$sum += $nbAuthors[$i];
#		}
#	}
#	echo $sum;
#}

// -- Make authorship distribution ---------------------------------------------
#for($i = 1; $i < 50; $i++)
#{
#	echo $i;
#	foreach($data as $year => $nbAuthors)
#	{
#		echo "\t";
#		if(array_key_exists($i, $nbAuthors))
#			echo $nbAuthors[$i];
#		else
#			echo 0;
#	}
#	echo "\n";
#}

// -- For each year, get mean/median number of authors per paper ---------------
echo "Year\tMean\tMedian\tMax\t#IndivPapers\tTotPapers\n";
foreach($data as $year => $nbAuthors)
{
	//
	$sum = $tot = 0;
	$all = array();

	//
	foreach($nbAuthors as $authors => $frequency)
	{
		if($authors == 0)
			continue;

		$tot += $frequency;

		for($i = 0; $i < $frequency; $i++)
			$all[] = $authors;
	}

	//
	rsort($all);
	$mean   = array_sum($all) / count($all);
	$median = $all[ round(count($all) / 2) - 1 ];
	$max    = max(array_keys($nbAuthors));
	$indiv  = $nbAuthors[1];

	//
	echo $year . "\t" . $mean . "\t" . $median . "\t" . $max . "\t" . $indiv . "\t" . $tot . "\n";
}


