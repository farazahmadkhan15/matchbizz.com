{% extends 'email/base.volt' %}
{% block title %}
    <h2>Your claim for "{{ businessProfileName }}" has been rejected.</h3>
{% endblock %}
{% block content %}
    <div>
        <p>It was not posible to verify the authenticity of your business with the information that you specified.</p>
        <p>Our team will contact you as soon as possible to ask for more information in order to finished the verification process.</p>
        <p>Please excuse us for any inconvenience.</p>
    </div>
{% endblock %}
