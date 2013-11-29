<?php
/**
 * Steam API wrapper for Yii
 * @author DarkwaveMD
 */
Yii::setPathOfAlias('steam',dirname(__FILE__));
Yii::import('steam.*');
class MYiiSteam extends CApplicationComponent{
	public $apiKey = '';

	protected $apiUrl = 'http://api.steampowered.com/';
	protected $communityUrl = 'http://steamcommunity.com/profiles/';

	public function init()
	{

	}
	public function getPlayerInfo($steamID)
	{
		$answer = CJSON::decode( Yii::app()->curl->get($this->apiUrl.'ISteamUser/GetPlayerSummaries/v0002/',array(
			'key'=>$this->apiKey,
			'steamids'=>$steamID
		)));
		//return $result;
		return (object) $answer['response']['players']['0'];
	}
	public function getPlayerInventory($steamID,$gameID)
	{
		if($result = Yii::app()->cache->get('inv-'.$steamID.$gameID))
			return $result;
		$result = array();
		$answer = CJSON::decode( Yii::app()->curl->get($this->communityUrl.'/'.$steamID.'/inventory/json/'.$gameID.'/2/'));

		foreach($answer['rgDescriptions'] as $key=>$value)
		{

		if(isset($value['market_name']))
			$result[$value['market_name']] = $value;

		}
		Yii::app()->cache->set('inv-'.$steamID.$gameID,$result,0);
		return  $result;
	}

} 