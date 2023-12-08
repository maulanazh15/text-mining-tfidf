from django.http import JsonResponse
from django.shortcuts import render
from django.views import View
from .models import HoaksPredictor

class HoaksPredictView(View):
    template_name = 'hoax_predictor/predict.html'

    def get(self, request, *args, **kwargs):
        return render(request, self.template_name)

    def post(self, request, *args, **kwargs):
        # Ambil data dari formulir POST
        title = request.POST.get('title', '')
        text = request.POST.get('text', '')

        # Buat objek HoaksPredictor dengan data dari formulir
        hoaks_predictor = HoaksPredictor(title=title, text=text)
        hoaks_predictor.save()  # Objek ini akan melakukan prediksi saat disimpan

        # Ambil nilai label prediksi
        label = hoaks_predictor.label

        if request.headers.get('X-Requested-With') == 'XMLHttpRequest':
            return JsonResponse({'label': label})
        # Kirim hasil prediksi ke template
        context = {'title': title, 'text': text, 'label': label}
        return render(request, self.template_name, context)
