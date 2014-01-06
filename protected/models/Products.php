<?php

/**
 * This is the model class for table "tbl_products".
 *
 * The followings are the available columns in table 'tbl_products':
 * @property integer $id
 * @property string $code
 * @property string $product_type
 * @property string $location
 * @property integer $cost_price
 * @property integer $marked_price
 * @property integer $selling_price
 * @property integer $net_profit
 * @property string $description
 * @property string $date
 */
class Products extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Products the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_products';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cost_price, marked_price, selling_price, net_profit', 'numerical', 'integerOnly'=>true),
			array('code', 'length', 'max'=>10),
			array('product_type, location', 'length', 'max'=>50),
			array('description, date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, code, product_type, location, cost_price, marked_price, selling_price, net_profit, description, date', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'code' => 'Code',
			'product_type' => 'Product Type',
			'location' => 'Location',
			'cost_price' => 'Cost Price',
			'marked_price' => 'Marked Price',
			'selling_price' => 'Selling Price',
			'net_profit' => 'Net Profit',
			'description' => 'Description',
			'date' => 'Date',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('product_type',$this->product_type,true);
		$criteria->compare('location',$this->location,true);
		$criteria->compare('cost_price',$this->cost_price);
		$criteria->compare('marked_price',$this->marked_price);
		$criteria->compare('selling_price',$this->selling_price);
		$criteria->compare('net_profit',$this->net_profit);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('date',$this->date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}