<?php //require(LIB_PATH.'Unit_test.php'); Unit Test Library?>
<div class='index_wrapper' style="background-image:url('/public/images/white.gif'); background-repeat:no-repeat; background-position:center top;">

<article> 
    <?php
    
    if(@$data->qr_img):
        $img = @$data->qr_img.'.png';
    else:
        $img = 'qr-code.png';
    endif;
    
    ?>
    
    <div style="margin-top:80px;"><img src="/public/images/tmp/<?php echo $img; ?>" style="width:250px; height:250px;" /></div>

    <div style="margin-top:200px;">
        
        <!-- add ajax selector // put image in session -->
        
        <form action="order/" method="POST" class="search">
            <input type="hidden" name="action" value="build_qr" />
            <input type="hidden" name="type" value="text" />
            <div class="spacer"></div>
            <input type="submit" name="submit_qr" value="Order ScanTee" style="width:400px; height:40px;" />
        </form>

    </div>
</article>

</div>

