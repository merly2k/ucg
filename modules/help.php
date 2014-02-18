<?php
/*
 * этот файл распространяется на условиях коммерческой лицензии
 * по вопросам приобретения кода обращайтесь merly2k at gmail.com
 */

/**
 * Description of help
 * @author Лосев-Пахотин Руслан Витальевич <merly2k at gmail.com>
 */
class help extends app{
    function index(){}

    static function id($id){
        //echo $id;
        $help=new db();
        $zapros="select help_article from ghelp where id='$id';";
        //echo $zapros;
        $help->get_cols($zapros);
        $out=$help->result['help_article'][0];
        return $out;
    }
    
}

if(!empty($this->param[0])){
echo help::id($this->param[0]);

}
?>
