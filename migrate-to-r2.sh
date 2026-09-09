#!/bin/bash
set -euo pipefail

# ---- KONFIGURASI (GANTI KEY YANG BARU!) ----
R2_ACCOUNT_ID="a84b7e07dcbfd407a3198510ab1ce209"
R2_ACCESS_KEY_ID="732c6b55cfe7c2ffc9195b89b0d081f8"
R2_SECRET_ACCESS_KEY="a4de9cce473a97e1ea149aeb6adb5aeba05617b09174c1a6b6e0175f7e0d6540"
R2_BUCKET="sinta"

SOURCE_DIR="/var/www/html/asset-inventaris/storage/app/public"
REMOTE_NAME="r2"
LOG_DIR="/root/r2-migration-logs"

TRANSFERS=10
CHECKERS=20

FOLDERS=(
  "gambar"
)

mkdir -p "$LOG_DIR"

# ---- SETUP RCLONE ----
setup_rclone_remote() {
  if ! command -v rclone &> /dev/null; then
    echo ">> rclone belum terinstall, menginstall..."
    curl https://rclone.org/install.sh | sudo bash
  fi

  if ! command -v mogrify &> /dev/null; then
    echo ">> ImageMagick belum terinstall, menginstall..."
    apt-get update && apt-get install -y imagemagick
  fi

  mkdir -p ~/.config/rclone
  cat > /tmp/rclone-r2-block.conf <<EOF
[${REMOTE_NAME}]
type = s3
provider = Cloudflare
access_key_id = ${R2_ACCESS_KEY_ID}
secret_access_key = ${R2_SECRET_ACCESS_KEY}
endpoint = https://${R2_ACCOUNT_ID}.r2.cloudflarestorage.com
EOF

  RCLONE_CONF="$HOME/.config/rclone/rclone.conf"
  touch "$RCLONE_CONF"

  if grep -q "^\[${REMOTE_NAME}\]" "$RCLONE_CONF" 2>/dev/null; then
    awk -v RS='' -v ORS='\n\n' '!/^\['"${REMOTE_NAME}"'\]/' "$RCLONE_CONF" > /tmp/rclone-filtered.conf || true
    mv /tmp/rclone-filtered.conf "$RCLONE_CONF"
  fi
  echo "" >> "$RCLONE_CONF"
  cat /tmp/rclone-r2-block.conf >> "$RCLONE_CONF"
  echo ">> Remote '${REMOTE_NAME}' sudah dikonfigurasi."
}

# ---- KOMPRESI AMAN UNTUK DISK PENUH (1 FILE = 1 PROSES) ----
compress_folder_safely() {
  local folder="$1"
  local src="${SOURCE_DIR}/${folder}"

  if [ ! -d "$src" ]; then
    echo ">> [SKIP] Folder tidak ditemukan: $src"
    return
  fi

  echo ">> [KOMPRESI AMAN] Memproses gambar di: $folder"
  echo ">> Ukuran awal: $(du -sh "$src" | cut -f1)"
  echo ">> Metode: 1 file dikompres → file asli dihapus → lanjut file berikutnya"
  echo ">> (Aman untuk disk yang hampir penuh)"
  echo ""

  local count=0
  local total=$(find "$src" -type f \( -iname "*.jpg" -o -iname "*.jpeg" -o -iname "*.png" -o -iname "*.webp" \) | wc -l)
  echo ">> Total gambar ditemukan: $total file"

  # Proses SATU PER SATU menggunakan loop
  find "$src" -type f \( -iname "*.jpg" -o -iname "*.jpeg" -o -iname "*.png" -o -iname "*.webp" \) -print0 | \
  while IFS= read -r -d $'\0' file; do
    count=$((count + 1))

    # Tampilkan progress setiap 100 file
    if (( count % 100 == 0 )); then
      echo "   Progress: $count / $total file | Ukuran saat ini: $(du -sh "$src" | cut -f1)"
    fi

    # Kompres file ini saja (in-place untuk JPG, atau konversi untuk PNG/WEBP)
    mogrify -auto-orient -resize "1280>" -quality 80 -format jpg "$file" 2>/dev/null || true

    # Jika file asli bukan .jpg (misal .png atau .webp), hapus file aslinya
    # karena mogrify -format jpg membuat file baru dengan ekstensi .jpg
    local ext="${file##*.}"
    ext=$(echo "$ext" | tr '[:upper:]' '[:lower:]')
    if [[ "$ext" != "jpg" && "$ext" != "jpeg" ]]; then
      rm -f "$file"
    fi
  done

  echo ""
  echo ">> [KOMPRESI] Selesai. Ukuran akhir: $(du -sh "$src" | cut -f1)"
}

# ---- SYNC ----
sync_folder() {
  local folder="$1"
  local src="${SOURCE_DIR}/${folder}"
  local dst="${REMOTE_NAME}:${R2_BUCKET}/${folder}"
  local log="${LOG_DIR}/${folder}.log"

  if [ ! -d "$src" ]; then
    return
  fi

  echo ">> [SYNC] Menyalin ke R2: $folder"
  rclone copy "$src" "$dst" \
    --transfers="$TRANSFERS" \
    --checkers="$CHECKERS" \
    --progress \
    --log-file="$log" \
    --log-level INFO \
    --s3-no-check-bucket

  echo ">> [SYNC] Selesai: $folder"
}

# ---- VERIFIKASI ----
verify_folder() {
  local folder="$1"
  local src="${SOURCE_DIR}/${folder}"
  local dst="${REMOTE_NAME}:${R2_BUCKET}/${folder}"

  if [ ! -d "$src" ]; then
    return
  fi

  echo ">> [VERIFIKASI] Mengecek: $folder"
  rclone check "$src" "$dst" --one-way --s3-no-check-bucket || echo "!! ADA PERBEDAAN di folder: $folder"
}

# ============================================================
# EKSEKUSI
# ============================================================

echo "===== 1. SETUP ====="
setup_rclone_remote

echo ""
echo "===== 2. KOMPRESI AMAN ====="
for folder in "${FOLDERS[@]}"; do
  compress_folder_safely "$folder"
done

echo ""
echo "===== 3. SYNC KE R2 ====="
for folder in "${FOLDERS[@]}"; do
  sync_folder "$folder"
done

echo ""
echo "===== 4. VERIFIKASI ====="
for folder in "${FOLDERS[@]}"; do
  verify_folder "$folder"
done

echo ""
echo "===== SELESAI ====="
