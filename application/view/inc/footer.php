    
    </div>
    <div class="footer_wrap">
        
        <div style="float:right;">
        <?php
        $time = microtime();
        $time = explode(' ', $time);
        $time = $time[1] + $time[0];
        $finish = $time;
        $total_time = round(($finish - START), 4);
        echo 'Page generated in '.$total_time.' seconds. &nbsp;';
        ?> 
        </div>        
       
        <div id="test1"></div>
        <div id="test2"></div>
    </div>
</div>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<!--[if lt IE 9]>
   <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<!-- [JS TEMPLATE - EDIT AS NEEDED] -->
<script type="text/javascript" src="<?php echo JS_PATH . 'main.js'; ?>"></script> 

</body>
</html>