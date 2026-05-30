#!/bin/bash
# ─────────────────────────────────────────────────────────────────────────────
# install.sh — Script Installer Satu Perintah untuk VPS (BAKUL Enterprise)
# Jalankan: curl -fsSL https://raw.githubusercontent.com/suppfaiz/bakulweb/main/install.sh | sudo bash
# ─────────────────────────────────────────────────────────────────────────────

set -e

# Warna ANSI
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
CYAN='\033[0;36m'
MAGENTA='\033[0;35m'
NC='\033[0m' # No Color
BOLD='\033[1m'

# Helper Logger
log()   { echo -e "${GREEN}[✓]${NC} $1"; }
warn()  { echo -e "${YELLOW}[!]${NC} $1"; }
error() { echo -e "${RED}[✗]${NC} $1"; exit 1; }
info()  { echo -e "${CYAN}[i]${NC} $1"; }

# 1. Pastikan dijalankan sebagai root
if [ "$EUID" -ne 0 ]; then
    error "Skrip ini harus dijalankan sebagai root atau dengan sudo. Silakan jalankan kembali menggunakan: sudo bash"
fi

# Tampilkan Banner Premium
clear
echo -e "${CYAN}╔══════════════════════════════════════════════════════════════╗${NC}"
echo -e "${CYAN}║                                                              ║${NC}"
echo -e "${CYAN}║     ${BOLD}${MAGENTA}BAKUL Enterprise — Automated VPS Installer v1.0${NC}${CYAN}      ║${NC}"
echo -e "${CYAN}║                                                              ║${NC}"
echo -e "${CYAN}╚══════════════════════════════════════════════════════════════╝${NC}"
echo ""

# 2. Deteksi OS
info "Mendeteksi sistem operasi..."
OS_ID=""
if [ -f /etc/os-release ]; then
    . /etc/os-release
    OS_ID=$ID
fi

info "Sistem operasi terdeteksi: $OS_ID ($NAME)"

# 3. Update Package Manager & Install Git & Curl
info "Memeriksa dan memasang dependensi dasar (curl, git, openssl)..."
if [[ "$OS_ID" == "ubuntu" || "$OS_ID" == "debian" || "$OS_ID" == "raspbian" ]]; then
    apt-get update -y
    apt-get install -y curl git openssl ca-certificates gnupg
elif [[ "$OS_ID" == "centos" || "$OS_ID" == "rhel" || "$OS_ID" == "rocky" || "$OS_ID" == "almalinux" || "$OS_ID" == "fedora" ]]; then
    yum install -y curl git openssl ca-certificates
else
    warn "OS tidak teridentifikasi secara penuh. Mencoba menggunakan apt-get..."
    apt-get update -y && apt-get install -y curl git openssl || true
fi

# 4. Memasang Docker & Docker Compose
if ! command -v docker &>/dev/null; then
    info "Docker tidak ditemukan. Menginstal Docker menggunakan script resmi..."
    curl -fsSL https://get.docker.com | sh
    systemctl enable --now docker
    log "Docker berhasil diinstal dan dijalankan."
else
    log "Docker sudah terpasang."
fi

# Memastikan Docker Compose terpasang (CLI V2)
if ! docker compose version &>/dev/null; then
    info "Docker Compose v2 tidak terdeteksi. Memasang docker-compose-plugin..."
    if [[ "$OS_ID" == "ubuntu" || "$OS_ID" == "debian" ]]; then
        apt-get update -y
        apt-get install -y docker-compose-plugin
    elif [[ "$OS_ID" == "centos" || "$OS_ID" == "rhel" || "$OS_ID" == "rocky" || "$OS_ID" == "almalinux" ]]; then
        yum install -y docker-compose-plugin
    else
        error "Gagal memasang Docker Compose. Silakan pasang docker-compose-plugin secara manual."
    fi
fi
log "Docker Compose terverifikasi: $(docker compose version)"

# 5. Clone Repository (atau jalankan in-place)
INSTALL_DIR="bakulweb"
if [ -f "docker-compose.yml" ] && [ -d "app" ]; then
    info "Skrip dijalankan dari dalam folder project. Melewati langkah kloning..."
    INSTALL_DIR="."
else
    if [ -d "$INSTALL_DIR" ]; then
        BACKUP_DIR="${INSTALL_DIR}_backup_$(date +%Y%m%d_%H%M%S)"
        warn "Direktori '$INSTALL_DIR' sudah ada. Memindahkan ke folder backup: $BACKUP_DIR"
        mv "$INSTALL_DIR" "$BACKUP_DIR"
    fi

    info "Mengkloning repository BAKUL E-Commerce dari GitHub..."
    git clone https://github.com/suppfaiz/bakulweb.git "$INSTALL_DIR"
    cd "$INSTALL_DIR"
fi

# 6. Konfigurasi Environment & Generate Password Acak
info "Menyiapkan konfigurasi environment (.env)..."
cp .env.example .env

# Generate secure random passwords
DB_PASS=$(openssl rand -hex 12)
DB_ROOT_PASS=$(openssl rand -hex 16)

# Edit .env menggunakan sed
sed -i "s/DB_PASS=bakul_secret/DB_PASS=${DB_PASS}/g" .env
sed -i "s/DB_ROOT_PASS=bakul_root_secret/DB_ROOT_PASS=${DB_ROOT_PASS}/g" .env

log "Konfigurasi .env siap dengan database password yang aman."

# 7. Atur Permission Uploads Folder
info "Mengatur hak akses folder uploads..."
mkdir -p public/uploads
chmod -R 777 public/uploads
log "Permission folder uploads berhasil diatur ke writable."

# 8. Build dan Jalankan Container
info "Membangun dan menjalankan container Docker (PHP, Nginx, MySQL, phpMyAdmin)..."
docker compose up -d --build

# 9. Verifikasi Database
info "Menunggu database MySQL siap..."
DB_READY=false
for i in {1..30}; do
    # Jalankan ping mysqladmin di dalam container db
    if docker compose exec db mysqladmin ping -h localhost -u root -p"${DB_ROOT_PASS}" &>/dev/null; then
        DB_READY=true
        break
    fi
    echo -n "."
    sleep 1
done
echo ""

if [ "$DB_READY" = true ]; then
    log "Database MySQL siap digunakan dan skema database.sql berhasil dimuat."
else
    warn "MySQL membutuhkan waktu lebih lama untuk inisialisasi. Silakan cek status dengan: docker compose ps"
fi

# Dapatkan IP Publik VPS
info "Mendeteksi alamat IP publik VPS..."
PUBLIC_IP=$(curl -s https://ifconfig.me || curl -s https://api.ipify.org || echo "IP_VPS_ANDA")

# 10. Dashboard Ringkasan Instalasi
echo ""
echo -e "${GREEN}=================================================================${NC}"
echo -e "       ${BOLD}${GREEN}INSTALASI SELESAI & DEPLOYMENT BERHASIL! 🎉${NC}"
echo -e "${GREEN}=================================================================${NC}"
echo ""
echo -e "  ${BOLD}🌐 Web App Utama :${NC} http://${PUBLIC_IP}"
echo -e "  ${BOLD}🗄️  phpMyAdmin   :${NC} http://${PUBLIC_IP}:8080"
echo ""
echo -e "  ${BOLD}🔑 Kredensial Database (Disimpan di .env):${NC}"
echo -e "     - DB Host      : db (koneksi antar container)"
echo -e "     - DB Name      : bakul_ecommerce"
echo -e "     - DB User      : bakul_user"
echo -e "     - Password User: ${CYAN}${DB_PASS}${NC}"
echo -e "     - Root Password: ${CYAN}${DB_ROOT_PASS}${NC}"
echo ""
echo -e "  ${BOLD}🛠️  Perintah Berguna di folder ${INSTALL_DIR}:${NC}"
echo -e "     - Melihat status : docker compose ps"
echo -e "     - Melihat log    : docker compose logs -f"
echo -e "     - Mematikan app  : docker compose down"
echo -e "     - Menyalakan app : docker compose up -d"
echo ""
echo -e "${GREEN}=================================================================${NC}"
echo ""
