<?php
namespace yii\easyii\behaviors;

use Yii;
use yii\db\ActiveRecord;

class DataBehavior extends \yii\base\Behavior
{
	public $dataClass = null;
	public $dataAttribute = 'data';

    public function events()
    {
        return [
	        ActiveRecord::EVENT_BEFORE_INSERT => 'encodeData',
	        ActiveRecord::EVENT_BEFORE_UPDATE => 'encodeData',

	        ActiveRecord::EVENT_AFTER_INSERT => 'decodeData',
	        ActiveRecord::EVENT_AFTER_UPDATE => 'decodeData',

	        ActiveRecord::EVENT_AFTER_FIND => 'afterFind',
	        ActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
        ];
    }

	public function getData()
	{
		return $this->owner->{$this->dataAttribute};
	}

	public function setData($data)
	{
		$this->owner->{$this->dataAttribute} = $data;
	}

	public function encodeData()
	{
		if(!$this->data || (!is_object($this->data) && !is_array($this->data))){
			$this->data = new \stdClass();
		}

		$this->data = json_encode($this->data);
	}

	public function decodeData(){

		$this->parseData();

		if ($this->dataClass != null)
		{
			$class = $this->dataClass;
			$class::deleteAll(['item_id' => $this->primaryKey]);

			foreach($this->data as $name => $value){
				if(!is_array($value)){
					$this->insertDataValue($name, $value);
				} else {
					foreach($value as $arrayItem){
						$this->insertDataValue($name, $arrayItem);
					}
				}
			}
		}

	}

	private function insertDataValue($name, $value){
		$class = $this->dataClass;
		Yii::$app->db->createCommand()->insert($class::tableName(), [
			'item_id' => $this->primaryKey,
			'name' => $name,
			'value' => $value
		])->execute();
	}

	public function afterFind()
	{
		$this->parseData();
	}

    public function afterDelete()
    {
	    if ($this->dataClass != null)
	    {
		    $class = $this->dataClass;
		    $class::deleteAll(['item_id' => $this->primaryKey]);
	    }
    }

	private function parseData(){
		$this->data = $this->data !== '' ? json_decode($this->data) : [];
	}
}