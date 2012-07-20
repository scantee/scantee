<div class='page_wrapper'>
    <h3>Login Form</h3>
<form action='/page/login' method='POST' enctype='application/x-www-form-urlencoded' autocomplete='off' >
    
    <input type='hidden' name='action' value='check_login' />
    <label for='username'>Username: </label>
    <input type='text' name='username' autocomplete='off' />
    &nbsp;&nbsp;&nbsp;&nbsp;
    <label for='password'>Password: </label>
    <input type='password' name='password' autocomplete='off' />
    
    <input type='submit' name='login_submit' value='login' />
           
    
</form>
    <p><?php echo ((!empty($data->error))?$data->error:'Try incorrect info to test errors.'); ?></p>
    
    <div style='margin-top: 100px; text-align: left;'>
        <p><b>User:</b> &nbsp; johnjohn &nbsp;&nbsp;&nbsp;&nbsp;  <b>Pass:</b> &nbsp; johnjohn</p>
        
    </div>
    
    
</div>

