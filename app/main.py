import os, time
from datetime import timedelta
from io import BytesIO
from flask import Flask, request, jsonify, send_file, render_template_string, redirect, url_for
from werkzeug.utils import secure_filename
from google.cloud import storage
import subprocess


app = Flask(__name__)

# --- Google Cloud Storage setup ---
GCS_BUCKET_NAME = "materi"
SERVICE_ACCOUNT_JSON = os.path.join(os.getcwd(), "gcs-service-account.json")
client  = storage.Client.from_service_account_json(SERVICE_ACCOUNT_JSON)
bucket  = client.bucket(GCS_BUCKET_NAME)

# --- Helpers ---
ALLOWED_EXT = {".sql"}
def allowed(filename: str) -> bool:
    return os.path.splitext(filename)[1].lower() in ALLOWED_EXT

# --- HTML UI ---
main_html = """
<!DOCTYPE html>
<html>
<head>
  <title>Backup Manager</title>
  <style>
    body { font-family: Arial; max-width: 700px; margin: auto; padding: 2rem; background: #f5f5f5; }
    h2 { color: #2c3e50; }
    .card { background: white; padding: 1.5rem; border-radius: 0.75rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-bottom: 2rem; }
    button, input[type="submit"] {
        padding: 0.6rem 1.2rem;
        background-color: #2c7be5;
        color: white;
        border: none;
        border-radius: 0.4rem;
        cursor: pointer;
        font-size: 1rem;
    }
    button:hover, input[type="submit"]:hover { background-color: #1a68d1; }
    input[type="file"] {
        padding: 0.4rem;
        margin-right: 1rem;
    }
    ul { list-style: none; padding: 0; }
    li { margin: 0.5rem 0; }
    a { color: #2c7be5; text-decoration: none; }
    a:hover { text-decoration: underline; }
  </style>
</head>
<body>

  <h2>Backup Manager</h2>

  <div class="card">
    <form method="post" action="/backup-now">
      <button type="submit">🔁 Backup Database</button>
    </form>
  </div>

  <div class="card">
    <h3>Upload File .SQL</h3>
    <form action="/upload-backup" method="post" enctype="multipart/form-data">
      <input type="file" name="backup" accept=".sql" required>
      <input type="submit" value="⬆️ Upload">
    </form>
  </div>

  <div class="card">
    <h3>📁 Daftar Backup Tersimpan</h3>
    <ul>
      {% for file in files %}
        <li>
          {{ file }} — <a href="/download-backup?filename={{ file | urlencode }}">⬇️ Download</a>
        </li>
      {% else %}
        <li><em>Belum ada backup</em></li>
      {% endfor %}
    </ul>
  </div>

</body>
</html>
"""

# --- ROUTES ---

@app.route("/", methods=["GET"])
def index():
    blobs = bucket.list_blobs(prefix="backup/")
    files = [blob.name.replace("backup/", "") for blob in blobs if not blob.name.endswith("/")]
    return render_template_string(main_html, files=files)

@app.route("/upload-backup", methods=["POST"])
def upload_backup():
    if "backup" not in request.files:
        return jsonify(error="No backup file provided"), 400

    f = request.files["backup"]
    if f.filename == "":
        return jsonify(error="Empty filename"), 400
    if not allowed(f.filename):
        return jsonify(error="Invalid extension. Only .sql allowed"), 400

    original   = secure_filename(f.filename)
    timestamp  = int(time.time())
    object_key = f"backup/{timestamp}_{original}"

    blob = bucket.blob(object_key)
    blob.upload_from_file(f, content_type="application/sql")

    return redirect(url_for("index"))

@app.route("/backup-now", methods=["POST"])
def backup_now():
    timestamp = int(time.time())
    filename  = f"{timestamp}_{os.environ['DB_NAME']}_backup.sql"

    temp_dir  = "/tmp"                     # Di Cloud Run gunakan /tmp
    local_path = os.path.join(temp_dir, filename)

    dump_cmd = [
        "mysqldump",
        "-h", os.environ.get("DB_HOST", "127.0.0.1"),
        "-P", os.environ.get("DB_PORT", "3306"),
        "-u", os.environ["DB_USER"],
        f"-p{os.environ['DB_PASSWORD']}",
        os.environ["DB_NAME"],
    ]

    try:
        with open(local_path, "w", encoding="utf-8") as f:
            subprocess.run(dump_cmd, stdout=f, check=True)

        # Upload ke GCS
        bucket.blob(f"backup/{filename}").upload_from_filename(
            local_path, content_type="application/sql"
        )
        os.remove(local_path)
        return redirect(url_for("index"))

    except subprocess.CalledProcessError as e:
        return jsonify(error="Gagal membackup database",
                       detail=e.stderr.decode() if e.stderr else str(e)), 500

    
@app.route("/download-backup", methods=["GET"])
def download_backup():
    filename = request.args.get("filename")
    if not filename:
        return jsonify(error="Missing filename"), 400

    blob = bucket.blob(f"backup/{filename}")
    if not blob.exists():
        return jsonify(error="File not found"), 404

    buf = BytesIO()
    blob.download_to_file(buf)
    buf.seek(0)
    return send_file(buf, download_name=filename, as_attachment=True)

if __name__ == "__main__":
    import os
    port = int(os.environ.get("PORT", 8080)) 
    app.run(host="0.0.0.0", port=port) 