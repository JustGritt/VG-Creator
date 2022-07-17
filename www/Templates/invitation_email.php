<h1>Hi, {{name}}!</h1>
<p>{{sender_name}} has invited you to use {{sender_site}}  to collaborate with them. Use the button below to set up your account and get started:</p>
<!-- Action -->
<table class="body-action" align="center" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center">
            <!-- Border based button https://litmus.com/blog/a-guide-to-bulletproof-buttons-in-email-design -->
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td align="center">
                        <table border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td>
                                    <a href="{{action_url}}" class="button button--" target="_blank">Validate your account</a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<p>If you have any questions for {{sender_name}}, you can reply to {{sender_email}} and it will go right to them.
<p>Welcome aboard,
    <br>The {{sender_site}} Team</p>
<p><strong>P.S.</strong> Your account password is set by default to {{password}}.</p>
<!-- Sub copy -->
<table class="body-sub">
    <tr>
        <td>
            <p class="sub">If you’re having trouble with the button above, copy and paste the URL below into your web browser.</p>
            <p class="sub">{{action_url}}</p>
        </td>
    </tr>
</table>
