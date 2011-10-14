<?php
class Core{

	protected function saveXML($file_name, $xml){
		if(!file_exists($file_name)||!isset($xml)) return false;
		$fd=fopen($file_name, "w");
		fwrite($fd, $xml);
		fclose($fd);
	}

	protected function searchId($xmlist, $id){
		if(!isset($xmlist)||!isset($id)) return false;
		$i=0;
		foreach($xmlist as $key=>$value){
			if($value["id"]=="".$id."") return $i;
			$i++;
		}
		return false;
	}
	
	protected function maxId($xmlist){
		if(!isset($xmlist)) return false;
		$id=0;
		foreach($xmlist as $val){
			if($val["id"]>"".$id."") $id=$val["id"];
		}
		return $id+=1;
	}

}
?>