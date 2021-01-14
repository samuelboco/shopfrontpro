<?php

class main
{
	public function buildBranches($aRawBranches)
	{	    
	    if (count($aRawBranches) === 2) {
	    	$sBranch = $aRawBranches[0];
	    	array_shift($aRawBranches);
	    	$sLeaf = $aRawBranches[0];
	    	array_shift($aRawBranches);
	    	return array($sBranch => $sLeaf);
	    } 
	    $key = array_shift($aRawBranches);	  
	    return array($key => $this->buildBranches($aRawBranches));	    
	}

	public function createTree($sInput)
	{
		$aDirs = explode("\n", str_replace("\r", "", $sInput));
		$aTree = array();
		foreach ($aDirs as $sDir) {
			$aRawBranches = explode('/', $sDir);
			$aTree = array_merge_recursive($this->buildBranches($aRawBranches), $aTree);
				
		}
		return $aTree[''];
	}

	public function printTree($aBranches, &$sOutput, $iDepth)
	{		
		foreach ($aBranches as $sBranchKey => $mBranchValue) {												
			if (is_array($mBranchValue) === true) {				
				$sOutput .= str_repeat('&nbsp;', $iDepth*4) . $sBranchKey . '<br>';
				$this->printTree(array_reverse($mBranchValue), $sOutput, $iDepth+=1);
			} else {				
				$sOutput .= str_repeat('&nbsp;', $iDepth*4) . $mBranchValue . '<br>';
			}	
		}
		return $sOutput;
	}

	public function createRandomDirs($sBasePath, $iPathCount, $iDepth, $iFiles)
	{		
		$aDirectories = array();
		$sTempDirectory = '';
		$aDepths = array();
		for ($iCtr = 0; $iCtr <= $iPathCount; $iCtr++) {					
			$iRandDepth = rand(1, $iDepth);
			if (array_key_exists($iRandDepth, $aDepths) && array_count_values($aDepths)[$iRandDepth] > $iFiles) {
				$iCtr-=1;
				continue;
			}
			for ($iCtr2 = 1; $iCtr2 <= $iRandDepth; $iCtr2++) {		
				$sTempDirectory .= '/folder' . $iCtr2;
			}
			$iRandCharNum = rand(1, 10);
			$sFile = '';
			for ($iCtr3 = 0; $iCtr3 <= $iRandCharNum; $iCtr3++) {
				$iRandChar = rand(0, 25);			
				$aLetters = str_split('abcdefghijklmnopqrstuvwxyz');
				$sFile .= rand(0, 1) === 1 ? strtoupper($aLetters[$iRandChar]) : $aLetters[$iRandChar];
			}
			array_push($aDirectories, $sBasePath . $sTempDirectory . '/' .$sFile . '.txt');
			$sTempDirectory = '';
		}
		return $aDirectories;
	}

}

$aTree = array();
$oMain = new main();
$aResult = $oMain->createTree($_POST['paths']);
$sOutput = '';
print_r($oMain->printTree(array_reverse($aResult), $sOutput, 0));
print('<br><br>');
print_r($oMain->createRandomDirs($_POST['base_path'],(int)$_POST['paths_count'],(int)$_POST['depth'],(int)$_POST['files']));

