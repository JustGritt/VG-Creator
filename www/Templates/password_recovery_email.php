<h1>Hi {{name}},</h1>
<p>You recently requested to reset your password for your {{ product_name }} account. Use the button below to reset it. <strong>This password reset is only valid for the next 30 minutes.</strong></p>
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
                  <a href="{{action_url}}" class="button button--green" target="_blank">Reset your password</a>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<p>If you did not request a password reset, please ignore this email or <a href="{{support_url}}">contact support</a> if you have questions.</p>
<p>Thanks,
  <br>The {{ product_name }} Team</p>
<!-- Sub copy -->
<table class="body-sub">
  <tr>
    <td>
      <p class="sub">If you’re having trouble with the button above, copy and paste the URL below into your web browser.</p>
      <p class="sub">{{action_url}}</p>
    </td>
  </tr>
</table>
