import os
from django.db import models
import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.svm import SVC
from sklearn.feature_extraction.text import TfidfVectorizer
from nltk.tokenize import word_tokenize
from nltk.corpus import stopwords
from nltk.stem import PorterStemmer
import joblib

from hoax_detection.settings import BASE_DIR

# Inisialisasi stop words dan stemmer
stop_words = set(stopwords.words('english'))
porter = PorterStemmer()

class HoaksPredictor(models.Model):
    title = models.TextField()
    text = models.TextField()
    label = models.BooleanField(null=True, blank=True)
    akurasi = 0

    def preprocess_text(self, text):
        # Fungsi untuk pra-pemrosesan teks
        # Hapus tanda baca, ubah ke huruf kecil, tokenisasi, hapus stop words, dan stemming
        # text_without_punctuation = text.translate(str.maketrans('', '', string.punctuation))
        words = word_tokenize(text)
        filtered_words = [porter.stem(word.lower()) for word in words if word.isalnum() and word.lower() not in stop_words]
        return ' '.join(filtered_words)
    
    def train_models(self) :
        preprocess_file_text = os.path.join(BASE_DIR, 'hoax_predictor', 'preprocessed_corpus_with_labels.csv')

        preprocessed_corpus = pd.read_csv(preprocess_file_text, dtype=str)
        tfidf_vectorizer = TfidfVectorizer(max_df=0.7, max_features=2000)

        # Fit and transform the preprocessed corpus
        tfidf_matrix = tfidf_vectorizer.fit_transform(list(preprocessed_corpus['text']))
        predict_matrix = tfidf_vectorizer.transform([self.preprocess_text(self.text)])
        # Convert the TF-IDF matrix to a DataFrame (for better visualization)
        tfidf_df = pd.DataFrame(tfidf_matrix.toarray(), columns=tfidf_vectorizer.get_feature_names(), 
                                index=['Doc'+str(i+1) for i in range(preprocessed_corpus.shape[0] + 1)])

        # Reset indices before concatenating
        preprocessed_corpus.reset_index(drop=True, inplace=True)
        tfidf_df.reset_index(drop=True, inplace=True)

        # Concatenate TF-IDF DataFrame with the label column
        result_df = pd.concat([tfidf_df, preprocessed_corpus['label']], axis=1)

        X = result_df.drop('label', axis=1)  # Features (TF-IDF)
        y = result_df['label'].replace({"REAL": 0, "FAKE": 1}).T.groupby(level=0).last().T # Labels

        # Split the data into training and testing sets (80% train, 20% test)
        X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

        # Create SVM model
        svm_model = SVC(kernel='linear')  # You can adjust the kernel type (linear, polynomial, etc.)

        # Train the model
        svm_model.fit(X_train, y_train)

        # Make predictions on the test set
        svm_predictions = svm_model.predict(predict_matrix.toarray())

        self.label = bool(svm_predictions)


    def predict_hoaks(self):
        # Pra-pemrosesan teks
        preprocessed_text = self.preprocess_text(self.text)
        models_folder = os.path.join(BASE_DIR, 'hoax_predictor', 'models')
        tfidf_idf_model = os.path.join(models_folder, 'tfidf_vectorizer.joblib')

        try:
            tfidf_vectorizer = joblib.load(tfidf_idf_model)
        except FileNotFoundError:
            raise FileNotFoundError(f"TFIDF model file not found at {tfidf_idf_model}")
        # Ubah teks menjadi fitur TF-IDF
        tfidf_matrix = tfidf_vectorizer.transform([preprocessed_text])
        # Load the SVM model using the full path
        models_folder = os.path.join(BASE_DIR, 'hoax_predictor', 'models')
        svm_model_path = os.path.join(models_folder, 'svm_model.joblib')
        try:
            svm_model = joblib.load(svm_model_path)
        except FileNotFoundError:
            raise FileNotFoundError(f"SVM model file not found at {svm_model_path}")
        # Lakukan prediksi dengan model SVM yang telah disiapkan sebelumnya
        # Convert sparse matrix to dense array
        dense_tfidf_matrix = tfidf_matrix.toarray()

        # Lakukan prediksi dengan model SVM yang telah disiapkan sebelumnya
        prediction = svm_model.predict(dense_tfidf_matrix)

        # Set nilai label pada objek model Django
        self.label = bool(prediction)

    def save(self, *args, **kwargs):
        # Saat objek disimpan, lakukan prediksi berita hoaks
        self.predict_hoaks()
        # self.train_models()
        super().save(*args, **kwargs)

    def __str__(self):
        return f"{self.title} - {self.label}"
