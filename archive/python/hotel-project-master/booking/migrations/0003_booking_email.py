# Generated by Django 2.2.3 on 2019-07-29 07:20

from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('booking', '0002_booking_phone_num'),
    ]

    operations = [
        migrations.AddField(
            model_name='booking',
            name='email',
            field=models.EmailField(blank=True, default=None, max_length=254),
        ),
    ]