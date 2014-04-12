<?php

/**
 * This is the model class for table "tbl_inventory".
 *
 * The followings are the available columns in table 'tbl_inventory':
 * @property integer $id
 * @property string $code
 * @property string $product_type
 * @property string $location
 * @property integer $cost_price
 * @property integer $marked_price
 * @property string $description
 * @property string $date
 * @property integer $quantity
 */
class Inventory extends CActiveRecord {

  /**
   * Returns the static model of the specified AR class.
   * @param string $className active record class name.
   * @return Inventory the static model class
   */
  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

  /**
   * @return string the associated database table name
   */
  public function tableName() {
    return 'tbl_inventory';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules() {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
        array('cost_price, marked_price,quantity', 'numerical', 'integerOnly' => true),
        array('code', 'length', 'max' => 10),
        array('product_type, location', 'length', 'max' => 50),
        array('description, date', 'safe'),
        // The following rule is used by search().
        // Please remove those attributes that should not be searched.
        array('id, code, product_type, location, cost_price, marked_price, description, date,quantity', 'safe', 'on' => 'search'),
    );
  }

  /**
   * @return array relational rules.
   */
  public function relations() {
    // NOTE: you may need to adjust the relation name and the related
    // class name for the relations automatically generated below.
    return array(
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels() {
    return array(
        'id'           => 'ID',
        'code'         => 'Code',
        'product_type' => 'Product Type',
        'location'     => 'Location',
        'cost_price'   => 'Cost Price',
        'marked_price' => 'Marked Price',
        'description'  => 'Description',
        'date'         => 'Date',
        'quantity'     => 'Quantity'
    );
  }

  /**
   * Retrieves a list of models based on the current search/filter conditions.
   * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
   */
  public function search() {
    // Warning: Please modify the following code to remove attributes that
    // should not be searched.

    $criteria = new CDbCriteria;

    $criteria->compare('id', $this->id);
    $criteria->compare('code', $this->code, true);
    $criteria->compare('product_type', $this->product_type, true);
    $criteria->compare('location', $this->location, true);
    $criteria->compare('cost_price', $this->cost_price);
    $criteria->compare('marked_price', $this->marked_price);
    $criteria->compare('description', $this->description, true);
    $criteria->compare('date', $this->date, true);
    $criteria->compare('quantity', $this->quantity,true);

    return new CActiveDataProvider($this, array(
        'criteria' => $criteria,
    ));
  }

}