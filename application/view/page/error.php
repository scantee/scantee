<div class='page_wrapper'>
    <h3>Oh No!</h3>
    <p>
        <?php 
        if(!empty($data->arg))
            echo '<h3>Error Code: '.$data->arg.'</h3>';
            echo Error::init($data->arg);
        ?>    
   
    </p>
    
</div>