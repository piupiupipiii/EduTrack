#!/bin/bash

# Konfigurasi
DB_USER="root"
DB_PASS="Uas_pipi_aliya1"
DB_NAME="uas-db"

# Nama file backup
DATE=$(date +"%Y%m%d_%H%M%S")
BACKUP_FILE="backup_${DB_NAME}_${DATE}.sql"
COMPRESSED_FILE="${BACKUP_FILE}.gz"

# Lokasi file sementara
TMP_PATH="/tmp/${COMPRESSED_FILE}"

# Buat dump dan kompres
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME | gzip > $TMP_PATH

# Upload ke Google Cloud Storage
BUCKET_NAME="materi"
DESTINATION="backup/${COMPRESSED_FILE}"

# Pastikan sudah install gcloud dan login
gsutil cp "$TMP_PATH" "gs://$BUCKET_NAME/$DESTINATION"

# Bersihkan file lokal
rm "$TMP_PATH"

echo "Backup selesai dan diupload ke: gs://$BUCKET_NAME/$DESTINATION"
