{% extends 'email/base.volt' %}
{% block content %}
    <div><b>First name: </b>{{ firstName }}</div>
    <div><b>Last name: </b>{{ lastName }}</div>
    <div><b>Business name: </b>{{ businessName }}</div>
    <div><b>Email: </b>{{ email }}</div>
    <div><b>Phone: </b>{{ phone }}</div>
    <br>
    <div><b>Cuerpo del mensaje:</b></div>
    <div>{{ message }}</div>
    <br>--<br>
{% endblock %}
