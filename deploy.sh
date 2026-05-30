#!/bin/bash
# ─────────────────────────────────────────────────────────────────────────────
# deploy.sh — Script deploy BAKUL ke VPS
# Jalankan: bash deploy.sh
# ─────────────────────────────────────────────────────────────────────────────

set -e

GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

log()  { echo -e "${GREEN}[✓]${NC} $1"; }
warn() { echo -e "${YELLOW}[!]${NC} $1"; }
err()  { echo -e "${RED}[✗]${NC} $1"; exit 1; }

echo ""
echo "╔══════════════════════════════════════════╗"
echo "║      BAKUL Enterprise — Deploy Script    ║"
echo "╚══════════════════════════════════════════╝"
echo ""

# 1. Cek .env
if [ ! -f ".env" ]; then
    warn ".env tidak ditemukan, menyalin dari .env.example..."
    cp .env.example .env
    warn "⚠️  EDIT file .env sebelum lanjut! (password database dll)"
    echo ""
    read -p "Sudah edit .env? Lanjut deploy? (y/N): " confirm
    [[ "$confirm" =~ ^[Yy]$ ]] || err "Deploy dibatalkan."
fi
log ".env ditemukan"

# 2. Set permission folder uploads
log "Mengatur permission folder uploads..."
mkdir -p public/uploads
chmod -R 775 public/uploads

# 3. Pull image terbaru
log "Pull Docker images terbaru..."
docker compose pull --ignore-buildable

# 4. Build image PHP
log "Build image PHP app..."
docker compose build app

# 5. Stop container lama (jika ada)
warn "Mematikan container lama..."
docker compose down --remove-orphans 2>/dev/null || true

# 6. Jalankan container
log "Menjalankan semua container..."
docker compose up -d

# 7. Tunggu database siap
warn "Menunggu database MySQL siap..."
sleep 8

# 8. Cek status
log "Status container:"
docker compose ps

echo ""
echo "═══════════════════════════════════════════"
log "Deploy SELESAI! 🎉"
echo ""
echo "  🌐 Web App  : http://$(curl -s ifconfig.me 2>/dev/null || echo 'IP-VPS-ANDA')"
echo "  🗄️  Database : port 3306 (lokal saja)"
echo ""
echo "  Jalankan phpMyAdmin:"
echo "    docker compose --profile tools up -d phpmyadmin"
echo "    Akses: http://IP-VPS:8080"
echo ""
echo "  Lihat log:"
echo "    docker compose logs -f"
echo "═══════════════════════════════════════════"
