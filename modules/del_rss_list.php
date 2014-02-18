        <?php
        // put your code here
        $template="default";
        $context="добавление задачи ";
        $id=(int)$this->param[0];
        $dbz=new db;
        $zapros="DELETE FROM `rss_sites` WHERE  `id`=$id LIMIT 1;";
        $dbz->query($zapros);
        $addr=WWW_BASE_PATH."task";
        header("Location:$addr");
        ?>
