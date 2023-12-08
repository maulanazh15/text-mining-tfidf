from django.urls import path
from .views import HoaksPredictView

urlpatterns = [
    path('predict/', HoaksPredictView.as_view(), name='predict'),
]
