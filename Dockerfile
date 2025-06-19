# Gunakan Python slim
FROM python:3.10-slim

# Install mysql-client (untuk `mysqldump`)
RUN apt-get update && apt-get install -y default-mysql-client && rm -rf /var/lib/apt/lists/*

# Set workdir
WORKDIR /app

# Copy semua file Python app
COPY app/ ./app/
COPY gcs-service-account.json ./
COPY requirements.txt .

# Install dependency
RUN pip install --upgrade pip && pip install -r requirements.txt

# Set env vars
ENV GOOGLE_APPLICATION_CREDENTIALS="/app/gcs-service-account.json"
ENV PORT=8080

# Expose port 8080 (wajib untuk Cloud Run)
EXPOSE 8080

# Jalankan flask
CMD ["python", "app/main.py"]
