<?php
# Feedback System wenn der Benutzer ein Admin ist!
if ($_SESSION['usr_grp'] == 'adm'){
?>
<aside>
    <script>
        // Include the UserVoice JavaScript SDK (only needed once on a page)
        UserVoice=window.UserVoice||[];(function(){var uv=document.createElement('script');uv.type='text/javascript';uv.async=true;uv.src='//widget.uservoice.com/GuCVG5NTZFt5fY70K1CDPw.js';var s=document.getElementsByTagName('script')[0];s.parentNode.insertBefore(uv,s)})();

        //
        // UserVoice Javascript SDK developer documentation:
        // https://www.uservoice.com/o/javascript-sdk
        //

        // Set colors
        UserVoice.push(['set', {
            accent_color: '#448dd6',
            trigger_color: 'white',
            trigger_background_color: '#e2753a'
        }]);

        // Identify the user and pass traits
        // To enable, replace sample data with actual user traits and uncomment the line
        UserVoice.push(['identify', {
            //email:      'john.doe@example.com', // User’s email address
            //name:       'John Doe', // User’s real name
            //created_at: 1364406966, // Unix timestamp for the date the user signed up
            //id:         123, // Optional: Unique id of the user (if set, this should not change)
            //type:       'Owner', // Optional: segment your users by type
            //account: {
            //  id:           123, // Optional: associate multiple users with a single account
            //  name:         'Acme, Co.', // Account name
            //  created_at:   1364406966, // Unix timestamp for the date the account was created
            //  monthly_rate: 9.99, // Decimal; monthly rate of the account
            //  ltv:          1495.00, // Decimal; lifetime value of the account
            //  plan:         'Enhanced' // Plan name for the account
            //}
        }]);

        // Add default trigger to the bottom-right corner of the window:
        UserVoice.push(['addTrigger', { mode: 'contact', trigger_position: 'top-left' }]);

        // Or, use your own custom trigger:
        //UserVoice.push(['addTrigger', '#id', { mode: 'contact' }]);

        // Autoprompt for Satisfaction and SmartVote (only displayed under certain conditions)
        UserVoice.push(['autoprompt', {}]);
    </script>
</aside>
<?php

}





echo
'

<div id="footer">
	<div class="container">

		  &copy;  <a href="http://board.streamerspanel.com/">board.streamerspanel.com</a>
         <small>   &raquo; <a href="http://changelog.streamerspanel.com/" target="_blank">Changelog</a></small>
         &raquo; '.$specialFooter.'

 <br />

 <span xmlns:dct="http://purl.org/dc/terms/" property="dct:title">StreamersPanel</span> von
 <a xmlns:cc="http://creativecommons.org/ns#" href="http://board.streamerspanel.de" property="cc:attributionName" rel="cc:attributionURL">
 David Schomburg</a> ist lizenziert unter einer

 <a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/4.0/">
 Creative Commons Namensnennung - Nicht kommerziell - Keine Bearbeitungen 4.0 International Lizenz</a>.

	</div> <!-- /.container -->

</div> <!-- /#footer -->
</body>
</html>';

