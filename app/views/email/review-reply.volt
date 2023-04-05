{% extends 'email/base.volt' %}
{% block content %}
    <div>Your review was replied by the business owner</div>
    <br>
    <div><b>This is the reply message:</b></div>
    <div>{{ reply }}</div>
{% endblock %}
