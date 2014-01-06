<?php

/**
 * Filterform to use filters in combination with CArrayDataProvider and CGridView
 */
class ArrayFilterClass extends CFormModel {

    public $filters = array();

    /**
     * Override magic getter for filters
     */
    public function __get($name) {
        if (!array_key_exists($name, $this->filters))
            $this->filters[$name] = null;
        return $this->filters[$name];
    }

    /**
     * Filter input array by key value pairs
     * @param array $data rawData
     * @return array filtered data array
     */
    public function filter(array $data) {
        foreach ($data AS $rowIndex => $row) {
            foreach ($this->filters AS $key => $searchValue) {
                if (!is_null($searchValue) AND $searchValue !== '') {
                    $compareValue = null;

                    if ($row instanceof CModel) {
                        if (isset($row->$key) == false) {
                            throw new CException("Property " . get_class($row) . "::{$key} does not exist!");
                        }
                        $compareValue = $row->$key;
                    } elseif (is_array($row)) {
                        if (!array_key_exists($key, $row)) {
                            throw new CException("Key {$key} does not exist in array!");
                        }
                        $compareValue = $row[$key];
                    } else {
                        throw new CException("Data in CArrayDataProvider must be an array of arrays or an array of CModels!");
                    }

                    if (stripos($compareValue, $searchValue) === false) {
                        unset($data[$rowIndex]);
                    }
                }
            }
        }
        return $data;
    }

}