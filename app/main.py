import os
from uuid import uuid4
from google.cloud import storage
from flask import Flask, request, jsonify
from werkzeug.utils import secure_filename

app = Flask(__name__)

GCS_BUCKET_NAME = "materi"
SERVICE_ACCOUNT_JSON = os.path.join(os.getcwd(), "service-account.json")
client = storage.Client.from_service_account_json(SERVICE_ACCOUNT_JSON)
bucket = client.get_bucket(GCS_BUCKET_NAME)  # fail‑fast jika bucket tidak ada

@app.route("/upload", methods=["POST"])
def upload_file():
    if "file" not in request.files:
        return jsonify(error="No file part"), 400
    f = request.files["file"]
    if f.filename == "":
        return jsonify(error="No file selected"), 400

    filename = f"{uuid4()}_{secure_filename(f.filename)}"
    blob = bucket.blob(f"uploads/{filename}")
    f.seek(0)
    blob.upload_from_file(f, content_type=f.content_type)
    blob.make_public()

    return jsonify(message="OK", url=blob.public_url)

if __name__ == "__main__":
    app.run(debug=True)
