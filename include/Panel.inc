<?php
	class Panel extends Template {
		var $components=array();
		var $classname="Panel";
		var $bool=true;
		function Panel($file_str,$root=".") {
			$this->tfile="file_".rand();
			$this->file_str=$file_str;
			$this->set_file($this->tfile,$this->file_str);
		}
		function add($layout,$component="") {
			
			if(is_object($component))$component=$component->getComponent();
			$this->set_var($layout,$component);
		}
		function getComponent() {
			
			if($this->bool==true)$this->add("SID",SID);
			$h_OUT="out_".rand();
			$this->parse($h_OUT,$this->tfile);
			return $this->finish($this->get_var($h_OUT));
		}
		function setBlock($block) {
			
			$handler="BLOCK_".rand();
			$this->set_block($this->tfile,$block,$handler);
			return $handler;
		}
		function concat($block,$handler) {
			
			$this->parse($handler,$block,true);
		}
		/* activa-desactiva al panel a colocar el SID*/
		function setSID($bool=true) {
			
			$this->bool=$bool;
		}
		function show($bool=false){
			
			echo $this->getComponent();
		}
		function pagina($bool=false){
			
			return $this->getComponent();
		}
	}
?>
