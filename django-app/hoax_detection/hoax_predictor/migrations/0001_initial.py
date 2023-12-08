# Generated by Django 4.2.7 on 2023-11-19 13:47

from django.db import migrations, models


class Migration(migrations.Migration):

    initial = True

    dependencies = []

    operations = [
        migrations.CreateModel(
            name="HoaksPredictor",
            fields=[
                (
                    "id",
                    models.BigAutoField(
                        auto_created=True,
                        primary_key=True,
                        serialize=False,
                        verbose_name="ID",
                    ),
                ),
                ("title", models.TextField()),
                ("text", models.TextField()),
                ("label", models.BooleanField(blank=True, null=True)),
            ],
        ),
    ]