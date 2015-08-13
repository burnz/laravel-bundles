<!--详情展示模板-->
<?php
    foreach( $panels as $panel ){
        echo $panel->draw();
    }