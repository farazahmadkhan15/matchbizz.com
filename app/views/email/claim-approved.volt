{% extends 'email/base.volt' %}
{% block title %}
    <h2>Your claim for "{{ businessProfileName }}" has been approved!</h3>
{% endblock %}
{% block content %}
    <div>
        <h3>Next steps:</h3>
        <ul>
            <li>Select a business plan that suits your needs</li>
            <li>Use all the features enabled your plan</li>
            <li>Get new customers!</li>
        </ul>
    </div>
{% endblock %}
