<?php //require(LIB_PATH.'Unit_test.php'); Unit Test Library?>
<div class='index_wrapper' style="background-image:url('public/images/tmp/<?php echo @$data->qr_img; ?>.png'); background-repeat:no-repeat; background-position:center top;">

<article> 

    <div class="results">  
        
        <?php
        
        if(@$data->text):  
            echo 'Message: '.$data->text;
            echo '<form action="build/" method="POST" class="search" style="margin-bottom:20px;">';
                echo '<input type="hidden" name="action" value="build_t" />';
                echo '<input type="hidden" name="qr_img" value="'.$data->qr_img.'" />';
                echo '<input type="submit" value="OK? next step >>"  />';
            echo '</form>';
        endif;

        ?>        
        
        <form method="POST" class="search">
            <input type="hidden" name="action" value="build_qr" />
            <input type="hidden" name="type" value="text" />
            <textarea name="text"><?php echo @$_POST['text']; ?></textarea>
            <div class="spacer"></div>
            <input type="submit" name="submit_qr" value="Build QR" style="width:400px; height:40px;" />
        </form>

    </div>
</article>

</div>

