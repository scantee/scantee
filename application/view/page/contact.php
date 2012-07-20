<div class='page_wrapper'>

    <!-- [ERROR MESSAGE] -->
    <?php echo!empty($data->error) ? $data->error : ''; ?>

    <h3>Contact Us</h3>
    <div class='contact_wrapper'>
        <div class='contact_left'>    
            <form class='contact_form' method='POST' enctype='application/x-www-form-urlencoded'>
                <input type='hidden' name='action' value='process_contact' />

                <label>Name:</label>
                <input type='text' name='name' value='' />

                <label>Phone:</label>
                <input type='text' name='phone' value='' />

                <label>Email:</label>
                <input type='text' name='email' value='' />

                <label>Message:</label>
                <textarea name='message'></textarea> 

                <input type='submit' name='submit_contact' value='Send Message' />

            </form>
        </div>
        <div class='contact_right'>
            <p>
                This message will be sent to the email address set in the config file. 
                Please make sure the email config settings are accurate as it will save 
                you allot of time and frustration.
            </p>
            <p>
                <input type='button' value='Click here to test email settings' onclick="location.href='/page/contact/test'" />
            </p>
        </div> 
    </div>    
</div>