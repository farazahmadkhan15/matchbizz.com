<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>MatchBizz</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>

<body style="margin: 0; padding: 0; background: #f3f3f3;">
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="600">
        <tr>
            <td align="center" bgcolor="#ffffff" style="padding: 40px 0 30px 0;border-bottom: 3px solid #f3f3f3;">
                <a href="https://www.matchbizz.com/" target="_blank">
                    <img src="https://www.matchbizz.com/webapp/assets/logo-matchbizz.png" alt="MatchBizz" width="500"
                        height="173.72" style="display: block;" />
                </a>
            </td>
        </tr>
        <tr style="font-family: Arial, sans-serif;">
            <td bgcolor="#FFF" style="padding: 40px 30px 40px 30px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td>
                            {% block title %}{% endblock %}
                        </td>
                    </tr>
                    <tr>
                        <td style="color: #333333; font-size: 16px; padding: 20px 0 30px 0;">
                            {% block content %}{% endblock %}
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 16px;">
                            {% block foot_note %}
                                <p>Best regards,</p>
                                <p>Matchbizz</p>
                            {% endblock %}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="center" bgcolor="#33333" style="padding: 40px 0 30px 0;color: #ffffff; font-family: Arial, sans-serif; font-size: 12px;">
                <a href="https://www.matchbizz.com/" target="_blank">
                    <img src="https://www.matchbizz.com/webapp/assets/name-matchbizz.png" alt="MatchBizz" width="361"
                        height="57" style="display: block;" />
                </a>
                <p>Copyright All rights reserved © MatchBizz</p>
                <div>This message was automatically sent from <a href="https://www.matchbizz.com">www.matchbizz.com</a></div>
            </td>
        </tr>
    </table>
</body>
</html>