<?php

class HomepageDao {

	public static function syncTopicConfig()
	{
		$sql = "SELECT Title,URL,ImageURL,Source FROM TopicConfig WHERE IsShow='YES' ".
			   " ORDER BY Sequence ASC,UpdateTime DESC LIMIT 20";
		$rs  = DBQuery::instance()->executeQuery($sql);
		if($rs && count($rs) >=1)
		{
		    require_once(__INCLUDE_ROOT."lib/util/class.CSV.php");
            $csv = new CSV();
		    $csvFile = __SETTING_FULLPATH . "config/homepage_topicconfig.csv";
		    $csv->storeFromArray($csvFile, $rs, true);
		    //FileDistribute::syncFile(__SETTING_PATH . 'config/homepage_topicconfig.csv');
			return true;
		}
		return false;
	}

	public static function getTopicConfig($source)
	{
		$csvFile = __SETTING_FULLPATH . "config/homepage_topicconfig.csv";
		if(file_exists($csvFile))
		{
			require_once(__INCLUDE_ROOT."lib/util/class.CSV.php");
            $csv = new CSV();
		    $rs = $csv->loadToArray($csvFile,true);
			if($rs)
			{
				$i=1;
				foreach($rs as $item)
				{
					if($item["Source"] != $source) continue;
					if($i == 1)
					{
					    $config["pics"]  = $item["ImageURL"];
						$config["links"] = urlencode($item["URL"]);
						$config["texts"] = $item["Title"];
						$i = 2;
					}
					else
					{
                        $config["pics"]  .= "|".$item["ImageURL"];
						$config["links"] .= "|".urlencode($item["URL"]);
						$config["texts"] .= "|".$item["Title"];
					}
				}
				return $config;
			}
		}
		return false;
	}

}
?>