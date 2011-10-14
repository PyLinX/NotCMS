<?php
/*  NotCMS (Nice Or Terrible CMS).
    Copyright (C) 2011  Jona "PyLinX" Lelmi

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>. */

	require_once('Core.php');
        require_once('BBcode.php');
        require_once('SimpleXMLElementt.php');
	class NotCMS extends Core{
	
                public function __construct($file_name, $rep=array()){
                	$this->rep=$rep;
			$this->file_name=$file_name;
			if(!isset($this->file_name)) throw new Exception('You must specify a file!');
		}
				
		public function createMenu($section_list){
			if(!preg_match("§(.*?)\.xml§", $this->file_name)) return false;
			if(!file_exists($this->file_name)){
				$fd=fopen($this->file_name, "w");
				fwrite($fd, "<?xml version='1.0' encoding='utf-8' ?><menu></menu>");
				fclose($fd);
			}
			$menu=new SimpleXMLElement($this->file_name, NULL, TRUE);
			foreach($section_list as $key=>$value){
				if($value!="guest"&&$value!="pages") continue;
                                if(!isset($key)||$key=="") continue;
				$id=$this->maxId($menu->section);
				$section=$menu->addChild("section");
				$section->addAttribute("name", $key);
				$section->addAttribute("type", $value);
				$section->addAttribute("id", $id);
			}
			$this->saveXML($this->file_name, $menu->asXML());
			return true;
		}
		
		public function makeMenu(){
			if(!file_exists($this->file_name)) return false;
			$menu=new SimpleXMLElement($this->file_name, NULL, TRUE);
			return $menu->section;
		}
		
		public function deleteMenu($id=NULL){
			if(!file_exists($this->file_name)) return false;
			if(!isset($id)){
				unlink($this->file_name);
				return true;
			}
			
			$menu=new SimpleXMLElement($this->file_name, NULL, TRUE);
			
			if(!$i=$this->searchId($menu->section, $id)) return false;
			unset($menu->section[$i]);
                  
			$this->saveXML($this->file_name, $menu->asXML());
			return true;

		}
		
		public function writePage($title, $text, $section_id){
			if(!file_exists($this->file_name)) return false;
			$title=htmlentities($title, ENT_COMPAT, "UTF-8");
			$text=htmlentities($text, ENT_COMPAT, "UTF-8");
			$text=BB(nl2br($text), $this->rep);
			$menu=new SimpleXMLElementt($this->file_name, NULL, TRUE);
			$i=$this->searchId($menu->section, $section_id);
			if($menu->section[$i]["type"]=="guest") return false;
			$pid=$this->maxId($menu->section[$i]->page);
			$pag=$menu->section[$i]->addChild("page");
			$tit=$pag->addChild("title");
                        $tit->addCChild($title);
			$txt=$pag->addChild("text");
			$txt->addCChild($text);
			$pag->addAttribute("id", $pid);
			$pag->addAttribute("date", "".date("D j M Y  H:i:s")."");
                        $pag->addAttribute("tstp", "".mktime()."");
			$pag->addChild("commenti");
			$this->saveXML($this->file_name, $menu->asXML());
			return $pag;		
		}
		
		public function removePage($id, $pid){
			if(!file_exists($this->file_name)) return false;
			$menu=new SimpleXMLElement($this->file_name, NULL, TRUE);
			$i=$this->searchId($menu->section, $id);
			$p=$this->searchId($menu->section[$i]->page, $pid);
			unset($menu->section[$i]->page[$p]);
			$this->saveXML($this->file_name, $menu->asXML()); 
			return true;		
		}
		
		public function modPage($title, $text, $section_id, $pid){
			if(!file_exists($this->file_name)) return false; 
			
			$title=htmlentities($title,ENT_COMPAT, "UTF-8");
			$text=htmlentities($text,ENT_COMPAT, "UTF-8");
                        $text=BB(nl2br($text), $this->rep);
			$this->removePage($section_id, $pid);

			$menu=new SimpleXMLElementt($this->file_name, NULL, TRUE);
			$i=$this->searchId($menu->section, $section_id);
			
			$pag=$menu->section[$i]->addChild("page");
			$t=$pag->addChild("title");
                        $t->addCChild($title);
			$t=$pag->addChild("text");
                        $t->addCChild($text);
			$pag->addAttribute("id", $pid);
			$pag->addAttribute("date", "".date("D j M  Y  H:i:s")."");
                        $pag->addAttribute("tstp", "".mktime()."");
			$pag->addChild("commenti");
			$this->saveXML($this->file_name, $menu->asXML());
			return $pag;		
		}
		
		public function putComment($name, $web, $comm, $ip, $sec_id, $pid){
			if(!file_exists($this->file_name)) return false;
			
			$name=htmlentities($name,ENT_COMPAT, "UTF-8");
			$web=htmlentities($web,ENT_COMPAT, "UTF-8");
			$comm=htmlentities($comm,ENT_COMPAT, "UTF-8");
			$comm=BB(nl2br($comm), $this->rep);
                        if(preg_match("§http\:\/\/(.*?)\Z§", $web)==0&&preg_match("§ftp\:\/\/(.*?)\Z§", $web)==0&&preg_match("§https\:\/\/(.*?)\Z§", $web)==0) $web='#';
			$menu=new SimpleXMLElementt($this->file_name, NULL, TRUE);
			$i=$this->searchId($menu->section, $sec_id);
			$p=$this->searchId($menu->section[$i]->page, $pid);
			$commento=$menu->section[$i]->page[$p]->commenti->addChild("commento");
                        $id_cmm=$this->maxId($menu->section[$i]->page[$p]->commenti->commento);
                        $commento->addAttribute("id", "$id_cmm");
			
			$n=$commento->addChild("name");
                        $n->addCChild($name);
			$commento->addChild("date", "".date("D j M  Y  H:i:s")."");
			$n=$commento->addChild("ip");
                        $n->addCChild($ip);
			$n=$commento->addChild("web");
                        $n->addCChild($web);
			$n=$commento->addChild("comment");
                        $n->addCChild($comm);
			
			$this->saveXML($this->file_name, $menu->asXML());
                        return $commento->asXML();

		}
		
		public function delComment($sec_id, $id, $pid){
			if(!file_exists($this->file_name)) return false;
			
			$menu=new SimpleXMLElement($this->file_name, NULL, TRUE);
			$s=$this->searchId($menu->section, $sec_id);
			$i=$this->searchId($menu->section[$s]->page, $id);
			$p=$this->searchId($menu->section[$s]->page[$i]->commenti->commento, $pid);
			
			unset($menu->section[$s]->page[$i]->commenti->commento[$p]);
			$this->saveXML($this->file_name, $menu->asXML());
			return true;
			
		}
		
		public function addFeed($name, $web, $comm, $ip, $sec_id){
			if(!file_exists($this->file_name)) return false;
			
			$name=htmlentities($name,ENT_COMPAT, "UTF-8");
			$web=htmlentities($web,ENT_COMPAT, "UTF-8");
			$comm=htmlentities($comm,ENT_COMPAT, "UTF-8");
			$comm=BB(nl2br($comm), $this->rep);
                        if(preg_match("§http\:\/\/(.*?)\Z§", $web)==0&&preg_match("§ftp\:\/\/(.*?)\Z§", $web)==0&&preg_match("§https\:\/\/(.*?)\Z§", $web)==0) $web='#';
			$menu=new SimpleXMLElementt($this->file_name, NULL, TRUE);
			$i=$this->searchId($menu->section, $sec_id);
			if($menu->section[$i]["type"]!="guest") return false;
			$feed=$menu->section[$i]->addChild("feed");
			$id=$this->maxId($menu->section[$i]->feed);
			$feed->addAttribute("id", "$id");
			
			$n=$feed->addChild("name");
                        $n->addCChild($name);
			$feed->addChild("date", "".date("D j M  Y  H:i:s")."");
			$n=$feed->addChild("ip");
                        $n->addCChild($ip);
			$n=$feed->addChild("web");
                        $n->addCChild($web);
			$n=$feed->addChild("fe");
                        $n->addCChild($comm);
			
			$this->saveXML($this->file_name, $menu->asXML());
			return $feed->asXML();
			
		}
		
		public function delFeed($sec_id, $id){
			if(!file_exists($this->file_name)) return false;
			
			$menu=new SimpleXMLElement($this->file_name, NULL, TRUE);
			$s=$this->searchId($menu->section, $sec_id);
			if($menu->section[$s]["type"]!="guest") return false;
			$i=$this->searchId($menu->section[$s]->feed, $id);
			
			unset($menu->section[$s]->feed[$i]);
			$this->saveXML($this->file_name, $menu->asXML());
			return true;
			
		}
		
		public function readSection($id, $limit=null, $from=0){
			if(!file_exists($this->file_name)) return false;
			
			$menu=simplexml_load_file($this->file_name, 'SimpleXMLElement', LIBXML_NOCDATA);
			$i=$this->searchId($menu->section, $id);
			if($menu->section[$i]["type"]=="guest"){
                                $tutte=array();
			              foreach($menu->section[$i]->feed as $feed){
				            $tutte[count($tutte)]=$feed;
			              }
				return array(array_reverse($tutte), "guest");
			}
			$tutte=array();
			foreach($menu->section[$i]->page as $pag){
				$tutte[count($tutte)]=$pag;
			}
			
			$dim=count($tutte);
			while($dim>1){
        			$p=0;
				for($t=1; $t<$dim; $t++){
					if((int) $tutte[$t]["tstp"]>(int) $tutte[$p]["tstp"]) $p=$t;
				}
				if($p<$dim-1){
					$tmp=$tutte[$dim-1];
					$tutte[$dim-1]=$tutte[$p];
					$tutte[$p]=$tmp;
				}
				$dim-=1;
			}
                        $tutte=array_reverse($tutte);
                        if(!isset($limit)) return array($tutte, "pages");
			if($from==0){
                                return array(array_slice($tutte, 0, $limit), "pages");
                        }
			for($t=0; $t<count($tutte); $t++){
				if((int) $tutte[$t]["tstp"]==$from) break;
			} 
			return array(array_slice($tutte, $t+=1, $limit), "pages");	
		
		}
		
		public function makeHome($how_many){
			if(!file_exists($this->file_name)) return false;
			
			$menu=new SimpleXMLElement($this->file_name, NULL, TRUE);
			$tutte=array();
			
			foreach($menu->section as $value){
				if($value["type"]!="pages") continue;
				foreach($value as $pag){
                                        $pag->addAttribute('id_s', $value['id']);
					$tutte[count($tutte)]=$pag;
				}
			}
			
			$dim=count($tutte);
			while($dim>1){
        			$p=0;
				for($i=1; $i<$dim; $i++){
					if((int) $tutte[$i]["tstp"]>(int) $tutte[$p]["tstp"]) $p=$i;
				}
				if($p<$dim-1){
					$tmp=$tutte[$dim-1];
					$tutte[$dim-1]=$tutte[$p];
					$tutte[$p]=$tmp;
				}
				$dim-=1;
			}			
			return array(array_slice(array_reverse($tutte), 0, $how_many), "home");
			
		}

                public function readComments($id_s, $id_p){
                	if(!file_exists($this->file_name)) return false;
			
			$menu=new SimpleXMLElement($this->file_name, NULL, TRUE);
			$s=$this->searchId($menu->section, $id_s);
			$p=$this->searchId($menu->section[$s]->page, $id_p);
                        
                        return $menu->section[$s]->page[$p]->commenti;


                }

                public function getAPage($id_s, $id_p){
                	if(!file_exists($this->file_name)) return false;
			
			$menu=new SimpleXMLElement($this->file_name, NULL, TRUE);
                        $i=$this->searchId($menu->section, $id_s);
                        $p=$this->searchId($menu->section[$i]->page, $id_p);
                        return $menu->section[$i]->page[$p];          
                }
            
	
	
	
	}
?>
