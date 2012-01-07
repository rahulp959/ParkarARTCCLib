<?php
session_start();
if (!$_SESSION['password'])
{
	?>
    <form action="password.php">
    Enter Password: <input type="text" name="password" />
    </form>
    <?
} elseif ($_SESSION['password'] = 1) {
	
	if ($_REQUEST['cid'])
	{
		function simpleXMLToArray(SimpleXMLElement $xml,$attributesKey=null,$childrenKey=null,$valueKey=null){ 
		
			if($childrenKey && !is_string($childrenKey)){$childrenKey = '@children';} 
			if($attributesKey && !is_string($attributesKey)){$attributesKey = '@attributes';} 
			if($valueKey && !is_string($valueKey)){$valueKey = '@values';} 
		
			$return = array(); 
			$name = $xml->getName(); 
			$_value = trim((string)$xml); 
			if(!strlen($_value)){$_value = null;}; 
		
			if($_value!==null){ 
				if($valueKey){$return[$valueKey] = $_value;} 
				else{$return = $_value;} 
			} 
		
			$children = array(); 
			$first = true; 
			foreach($xml->children() as $elementName => $child){ 
				$value = simpleXMLToArray($child,$attributesKey, $childrenKey,$valueKey); 
				if(isset($children[$elementName])){ 
					if(is_array($children[$elementName])){ 
						if($first){ 
							$temp = $children[$elementName]; 
							unset($children[$elementName]); 
							$children[$elementName][] = $temp; 
							$first=false; 
						} 
						$children[$elementName][] = $value; 
					}else{ 
						$children[$elementName] = array($children[$elementName],$value); 
					} 
				} 
				else{ 
					$children[$elementName] = $value; 
				} 
			} 
			if($children){ 
				if($childrenKey){$return[$childrenKey] = $children;} 
				else{$return = array_merge($return,$children);} 
			} 
		
			$attributes = array(); 
			foreach($xml->attributes() as $name=>$value){ 
				$attributes[$name] = trim($value); 
			} 
			if($attributes){ 
				if($attributesKey){$return[$attributesKey] = $attributes;} 
				else{$return = array_merge($return, $attributes);} 
			} 
		
			return $return; 
		}
		
		$url = "http://www.vatusa.net/feeds/exams.php?a=6&&key=709ceab47022aac392ecf5ff27d5e6e2&cid=${_REQUEST['cid']}";
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		curl_setopt($ch, CURLOPT_USERAGENT, 'ZOB (Cleveland ARTCC) Test History Obtainer');
		
		$content = curl_exec($ch);
		
		curl_close($ch);
		
		$root = new SimpleXMLElement($content, null, false);
		?>
		<table>
		<tr>
		<th>CID</th>
		<th>Exam Name</th>
		<th>Exam Date</th>
		<th>Exam Score</th>
		</tr>
		<?php
		$root2 = simpleXMLToArray($root);
		
		foreach ($root2 as $test)
		{
			foreach($test as $test_record)
			{
				?>
				<tr>
				<td><?=$test_record['cid']?></td>
				<td><?=$test_record['exam']?></td>
				<td><?=$test_record['exam_date']?></td>
				<td align="center"><?=$test_record['exam_score']?></td>
				</tr>
			<?
			}
		}
	} else {
		?>
        <form action="tsthistory.php" method="post">
        Enter CID: <input type="text" name="cid" />
        </form>
        <?php
	}
}
?>