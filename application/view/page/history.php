<div class='page_wrapper'>
    <h3>Search History</h3>
    <p>
        <div class="">
            <form action="" method="post" class="search">
                <div>
                    <input type="hidden" name="action" value="history" />  
                    <input type="text" name="keywords" value="<?php echo ((!empty($_POST['keywords']))?$_POST['keywords']:''); ?>" style="width:200px;" />
                    <input type="text" name="limit" value="<?php echo ((!empty($_POST['limit']))?$_POST['limit']:'10'); ?>" style="width:60px;" />
                    <input type="submit" value="Search History">
                </div>	

            </form>	
        </div>        
        
        View the price history for any part search.  
    </p>
    
    
<article>
<div id="results">

<?php 
    
    empty($_POST['limit'])?$limit=10:$limit=$_POST['limit'];
    
    $count = 0;
    
    if(is_array(@$data->pHx)):
        
        echo '<h3>Relectric Search History</h3>'; 
 
        foreach($data->pHx['relectric'] as $v):
            if($count >= $limit) continue;
            echo '<div class="cl_ad_stats"><p style="font-size:12px;">'.
                    'New: <span class="new"><em>'.$v['searchHistory_new'].'</em></span>&nbsp;&nbsp;&nbsp;'.
                    'Used: <span class="green"><em>'.$v['searchHistory_used'].'</em></span>&nbsp;&nbsp;&nbsp;'.
                    'Date: <em>'.$v['searchHistory_date'].'</em>'.
                    '</p></div>'; 
            $count++;

        endforeach;

        $count = 0;
        echo '<h3>SuperBreakers Search History</h3>';

        foreach($data->pHx['superBreaker'] as $v):
            if($count >= $limit) continue;                            
            echo '<div class="cl_ad_stats"><p style="font-size:12px;">'.
                    'New: <span class="new"><em>'.$v['searchHistory_new'].'</em></span>&nbsp;&nbsp;&nbsp;'.
                    'Used: <span class="green"><em>'.$v['searchHistory_used'].'</em></span>&nbsp;&nbsp;&nbsp;'.
                    'Date: <em>'.$v['searchHistory_date'].'</em>'.
                    '</p></div>'; 
            $count++;                        

        endforeach;

        $count = 0;
        echo '<h3>BreakerStore Search History</h3>';        

        foreach($data->pHx['breakerStore'] as $v):
            if($count >= $limit) continue;                            
            echo '<div class="cl_ad_stats"><p style="font-size:12px;">'.
                'New: <span class="new"><em>'.$v['searchHistory_new'].'</em></span>&nbsp;&nbsp;&nbsp;'.
                'List: <span class="green"><em>'.$v['searchHistory_list'].'</em></span>&nbsp;&nbsp;&nbsp;'.
                'Date: <em>'.$v['searchHistory_date'].'</em>'.
                '</p></div>';  
            $count++;                        

        endforeach;                        

    else:
        echo '<div class="cl_ad_stats"><p style="font-size:12px;">Records: NONE</p></div>';                         
    endif;
	

?>

</div>
</article>
    
</div>


