# Generated by Django 2.2.3 on 2019-07-31 06:42

from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('booking', '0003_booking_email'),
    ]

    operations = [
        migrations.AddField(
            model_name='booking',
            name='price',
            field=models.CharField(default=None, max_length=40),
        ),
    ]