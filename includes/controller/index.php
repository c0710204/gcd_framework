<?php

class index extends controller
{
	function main()
	{

		$storys=$this->loadmodel('story');
		$this->loadview("head.html");
		$this->loadview("menu.html");
		$this->loadloopview("row/homerow.html",$storys->loaddata("limit50sortbytimedesc")->getall());
		$this->loadview('aboutinfo.html');
	}
	function test()
	{

		$storys=$this->loadmodel('story');
		$talk=$this->loadmodel('talk');
		$this->echo=false;
		$storyshtml=$this->loadloopview(
				"row/rowhome.html",
				array('storys'=>$storys->loaddata("limit50sortbytimedesc")->getall()));
		$this->echo=true;
		$this->loadview('head.html',array('storys'=>$storyshtml));
		
		
	//	$this->loadview("head.html");
				
	}

}