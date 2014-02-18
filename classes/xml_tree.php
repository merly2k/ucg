<?php
/**
 * Description of xml_tree
 *
 * @author user3
 */
class xml_tree {
    //put your code here
    public function __construct() {
        public $xml='';
        public $keypath='';
        public $ret= array();
        $ret=get_xml_node($xml, $keypath);
        return $ret;
    }
    
    public function get_xml_node(){
        foreach($xml->xpath($keypas) as $item) {
            $this->row = simplexml_load_string($item->asXML());
            $this->outs[]=$this->row;
            }
            return $this->outs;
    }
 
 

}

?>
