#!/bin/bash
set -euo pipefail

# ---- 1. KONFIGURASI  FOLDER SAMA CLOUDFLARE ----
R2_ACCOUNT_ID="a84b7e07dcbfd407a3198510ab1ce209"
R2_ACCESS_KEY_ID="732c6b55cfe7c2ffc9195b89b0d081f8"
R2_SECRET_ACCESS_KEY="0460dc8c1ef9f8e945375e440ce676b7c30b23efe8f02d91df50cd64c49c1a39"
R2_BUCKET="sinta"        # nama bucket R2 kamu

SOURCE_DIR="/var/www/html/asset-inventaris/storage/app/public"
REMOTE_NAME="r2"
LOG_DIR="/root/r2-migration-logs"

# OPTIMASI: Transfers 10 dan Checkers 20
TRANSFERS=10
CHECKERS=20

# ---- 2. FOLDER YANG MAU DI-SYNC ----
FOLDERS=(
  "gambar"
  "dokumen"
  "dokumentasi"
  "manualbook"
  "cssd_item"
  "flipbooks"
  "dokumenbc"
  "cssd_item_group"
  "kso"
  "files"
)

# ============================================================
# JANGAN EDIT DI BAWAH INI KECUALI PERLU
# ============================================================

mkdir -p "$LOG_DIR"

# ---- 3. SETUP RCLONE REMOTE ----
setup_rclone_remote() {
  if ! command -v rclone &> /dev/null; then
    echo ">> rclone belum terinstall, menginstall..."
    curl https://rclone.org/install.sh | sudo bash
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

# ---- 4. SYNC SATU FOLDER ----
sync_folder() {
  local folder="$1"
  local src="${SOURCE_DIR}/${folder}"
  local dst="${REMOTE_NAME}:${R2_BUCKET}/${folder}"
  local log="${LOG_DIR}/${folder}.log"

  if [ ! -d "$src" ]; then
    echo ">> [SKIP] Folder tidak ditemukan: $src"
    return
  fi

  echo ">> Menyalin: $folder ($(du -sh "$src" | cut -f1))"

  # TAMBAHAN PENTING: --s3-no-check-bucket mencegah error 403/404 khas R2
  rclone copy "$src" "$dst" \
    --transfers="$TRANSFERS" \
    --checkers="$CHECKERS" \
    --progress \
    --log-file="$log" \
    --log-level INFO \
    --s3-no-check-bucket

  echo ">> Selesai: $folder (log: $log)"
}

# ---- 5. VERIFIKASI SATU FOLDER ----
verify_folder() {
  local folder="$1"
  local src="${SOURCE_DIR}/${folder}"
  local dst="${REMOTE_NAME}:${R2_BUCKET}/${folder}"

  if [ ! -d "$src" ]; then
    return
  fi

  echo ">> Verifikasi: $folder"
  # TAMBAHAN PENTING: --s3-no-check-bucket juga di sini
  rclone check "$src" "$dst" --one-way --s3-no-check-bucket || echo "!! ADA PERBEDAAN di folder: $folder — cek log di atas"
}

# ============================================================
# EKSEKUSI
# ============================================================

echo "===== SETUP RCLONE REMOTE ====="
setup_rclone_remote

echo ""
echo "===== MULAI SYNC SEMUA FOLDER ====="
for folder in "${FOLDERS[@]}"; do
  sync_folder "$folder"
done

echo ""
echo "===== VERIFIKASI SEMUA FOLDER ====="
for folder in "${FOLDERS[@]}"; do
  verify_folder "$folder"
done

echo ""
echo "===== SELESAI ====="
echo "Cek log lengkap di: $LOG_DIR"
echo "Kalau semua verifikasi OK, baru pertimbangkan hapus file lokal."
