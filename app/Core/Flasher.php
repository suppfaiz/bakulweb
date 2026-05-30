<?php

class Flasher {
    public static function setFlash($pesan, $aksi, $tipe) {
        $_SESSION['flash'] = [
            'pesan' => $pesan,
            'aksi'  => $aksi,
            'tipe'  => $tipe
        ];
    }

    public static function flash() {
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            $message = htmlspecialchars($flash['pesan'] . ' ' . $flash['aksi'], ENT_QUOTES);
            $type = strtolower($flash['tipe']); // success, error, warning, info
            
            // Map common color/bootstrap-style types to standard toast types
            if ($type === 'red' || $type === 'danger') {
                $type = 'error';
            } elseif ($type === 'green') {
                $type = 'success';
            } elseif ($type === 'yellow') {
                $type = 'warning';
            } elseif ($type === 'blue') {
                $type = 'info';
            }
            
            echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    if(typeof showToast === "function") {
                        showToast("' . $message . '", "' . $type . '");
                    }
                });
            </script>';
            
            unset($_SESSION['flash']);
        }
    }
}
