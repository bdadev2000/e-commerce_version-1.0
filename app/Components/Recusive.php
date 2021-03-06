<?php

namespace App\Components;
class Recusive{
    
    private $data;
    private $htmlSelect = '';
    public function __construct($data)
    {   
       $this->data = $data;
    }

    public function categoryRecusive( $parentId,$id = 0)
    {
        foreach ($this->data as $value) {
            if ($value['parent_id'] == $id) {
                if(!empty($parentId)  && $parentId == $value['id'])
                {
                    $this->htmlSelect .= "<option selected value= ' ". $value['id'] ."' >" . $value['name'] . "</option>";
                }else{
                    $this->htmlSelect .= "<option  value= ' ". $value['id'] ."' >" . $value['name'] . "</option>";
                }
                // Them <option> ...... </option> vao trong chuoi htmlSelect 
                $this->categoryRecusive($parentId,$value['id']);
            }
        }

        return $this->htmlSelect;
    }
}