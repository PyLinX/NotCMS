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
require_once("Core.php");      
require_once("BBcode.php"); 
require_once("checkURL.php"); 
require_once("SimpleXMLElementt.php");                   
class NotConfig extends Core{
	
	public function createLU($array_link){
		if(!file_exists("Db_XML/link_utili.xml")){
			$fd=fopen("Db_XML/link_utili.xml", "w");
			fwrite($fd, "<?xml version='1.0' encoding='UTF-8' ?><links></links>");
			fclose($fd);
		}
		$links=new SimpleXMLElement("Db_XML/link_utili.xml", NULL, TRUE);
		foreach($array_link as $key=>$value){
                        if(!isset($key)||$key==""||checkURL($value)===false) continue;
			$link=$links->addChild("link");
			$link->addAttribute("nome", htmlentities($key));
			$link->addAttribute("link", htmlentities($value));
			$link->addAttribute("id", $this->maxId($links->link));
		}
		$this->saveXML("Db_XML/link_utili.xml", $links->asXML());
		return true;	
		}
	
	public function delLU($id){
		if(!file_exists("Db_XML/link_utili.xml")) return false;
		$links=new SimpleXMLElement("Db_XML/link_utili.xml", NULL, TRUE);
		$i=$this->searchId($links->link, $id);
		unset($links->link[$i]);
                $this->saveXML("Db_XML/link_utili.xml", $links->asXML());
		return true;
	}
	
	public function addNews($new){                    
		if(!file_exists("Db_XML/news.xml")){
			$fd=fopen("Db_XML/news.xml", "w");
			fwrite($fd, "<?xml version='1.0' encoding='UTF-8' ?><newss></newss>");
			fclose($fd);
		}
		$newss=new SimpleXMLElementt("Db_XML/news.xml", NULL, TRUE);
		$ne=$newss->addChild("news");
		$t=$ne->addChild("testo");
                $t->addCChild(preg_replace("§\"§", "'", BB(htmlentities(substr(str_replace(array("\n","\r","\t"),"",$new),0,300)), array("url", "url="))));
		if(count($newss->news)>5) unset($newss->news[0]);
		$this->saveXML("Db_XML/news.xml", $newss->asXML());
		return true;
		
	}
	public function readLink(){
		if(!file_exists("Db_XML/link_utili.xml")) return false;
                $links=new SimpleXMLElement("Db_XML/link_utili.xml", NULL, TRUE);
		return $links->link;
		
	}
        public function readNews(){
		if(!file_exists("Db_XML/news.xml")) return false;
                $newss=new SimpleXMLElement("Db_XML/news.xml", NULL, TRUE);
		return $newss->news;
		
	}
        public function setUser($user, $passwd){
		$fd=fopen("config/admin_config.php", "w");
		fwrite($fd, "<?php define(USER, '".htmlentities($user)."'); define(PASSWD, md5('".$passwd."')); ?>");
		fclose($fd);
	}
        public function useDb($db){
                $fd=fopen("config/db_config.php", "w");
                fwrite($fd, "<?php define(DB, 'Db_XML/".$db."'); ?>");
                fclose($fd);
        }
        public function setVisualConfig($site="NotCMS Site", $footer="Powered by NotCMS created by PyLinX.", $geshi_file='g_style/geshi.txt', $limit=3){
                if(is_int($limit)===false||$limit==0) $limit=3;
                if(file_exists("config/visual_config.php")===true){
                      require_once("config/visual_config.php");
                      if($site!=SITE_NAME) $site=htmlentities($site);
                      if($footer!=FOOTER) $footer=htmlentities($footer);
                }else{
                      $site=htmlentities($site);
                      $footer=htmlentities($footer);
                }
                $replaces=array("url", "url=", "b", "em", "u");
                $fd=fopen("config/visual_config.php", "w");
                fwrite($fd, "<?php define(SITE_NAME, '".$site."'); define(FOOTER, '".BB($footer, $replaces)."'); define(GESHI_STYLE, '".$geshi_file."'); define(LIMIT, $limit); ?>");
                fclose($fd);
        }
        public function load_template($tp){
                $fd=fopen("config/style.php", "w");
                fwrite($fd, "<?php define(TEMPLATE, 'themes/".$tp."'); ?>");
		fclose($fd);
        }
}

?>
