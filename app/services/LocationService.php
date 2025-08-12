<?php

namespace App\Services;
use Illuminate\Support\Facades\Log;

class LocationService
{
    /**
     * Menghitung jarak antara dua koordinat menggunakan Haversine formula
     *
     * @param float $lat1 Latitude point 1
     * @param float $lon1 Longitude point 1
     * @param float $lat2 Latitude point 2
     * @param float $lon2 Longitude point 2
     * @return float Jarak dalam meter
     */
    public static function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        // Radius bumi dalam meter
        $earthRadius = 6371000;

        // Konversi derajat ke radian
        $lat1Rad = deg2rad($lat1);
        $lon1Rad = deg2rad($lon1);
        $lat2Rad = deg2rad($lat2);
        $lon2Rad = deg2rad($lon2);

        // Selisih koordinat
        $deltaLat = $lat2Rad - $lat1Rad;
        $deltaLon = $lon2Rad - $lon1Rad;

        // Haversine formula
        $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
             cos($lat1Rad) * cos($lat2Rad) *
             sin($deltaLon / 2) * sin($deltaLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        // Jarak dalam meter
        $distance = $earthRadius * $c;

        return round($distance, 2);
    }

    /**
     * Mengecek apakah lokasi berada dalam radius yang diizinkan
     *
     * @param float $userLat Latitude user
     * @param float $userLon Longitude user
     * @param float $officeLat Latitude kantor
     * @param float $officeLon Longitude kantor
     * @param int $allowedRadius Radius yang diizinkan dalam meter
     * @return array
     */
    public static function isWithinRadius($userLat, $userLon, $officeLat, $officeLon, $allowedRadius = 1000)
    {
        $distance = self::calculateDistance($userLat, $userLon, $officeLat, $officeLon);

        return [
            'is_within_radius' => $distance <= $allowedRadius,
            'distance' => $distance,
            'allowed_radius' => $allowedRadius,
            'distance_formatted' => self::formatDistance($distance)
        ];
    }

    /**
     * Format jarak untuk display yang user-friendly
     *
     * @param float $distance Jarak dalam meter
     * @return string
     */
    public static function formatDistance($distance)
    {
        if ($distance >= 1000) {
            return round($distance / 1000, 2) . ' km';
        }

        return round($distance, 0) . ' m';
    }

    /**
     * Validasi koordinat
     *
     * @param float $lat Latitude
     * @param float $lon Longitude
     * @return bool
     */
    public static function isValidCoordinate($lat, $lon)
    {
        return is_numeric($lat) && is_numeric($lon) &&
               $lat >= -90 && $lat <= 90 &&
               $lon >= -180 && $lon <= 180;
    }

    /**
     * Mendapatkan alamat dari koordinat menggunakan Reverse Geocoding
     * (Implementasi sederhana, bisa diganti dengan API yang lebih akurat)
     *
     * @param float $lat Latitude
     * @param float $lon Longitude
     * @return string|null
     */
    public static function getAddressFromCoordinates($lat, $lon)
    {
        try {
            // Menggunakan Nominatim OpenStreetMap API (gratis)
            $url = "https://nominatim.openstreetmap.org/reverse?lat={$lat}&lon={$lon}&format=json&addressdetails=1";

            $context = stream_context_create([
                'http' => [
                    'method' => 'GET',
                    'header' => [
                        'User-Agent: AbsensiApp/1.0'
                    ],
                    'timeout' => 10
                ]
            ]);

            $response = file_get_contents($url, false, $context);

            if ($response !== false) {
                $data = json_decode($response, true);

                if (isset($data['display_name'])) {
                    return $data['display_name'];
                }
            }
        } catch (\Exception $e) {
            Log::error('Error getting address from coordinates: ' . $e->getMessage());
        }

        return "Koordinat: {$lat}, {$lon}";
    }

    /**
     * Generate Google Maps URL untuk koordinat
     *
     * @param float $lat Latitude
     * @param float $lon Longitude
     * @return string
     */
    public static function getGoogleMapsUrl($lat, $lon)
    {
        return "https://www.google.com/maps?q={$lat},{$lon}";
    }

    /**
     * Cek apakah browser mendukung geolocation
     * (untuk JavaScript helper)
     *
     * @return string JavaScript code
     */
    public static function getGeolocationScript()
    {
        return "
        function checkGeolocationSupport() {
            return 'geolocation' in navigator;
        }

        function getCurrentPosition() {
            return new Promise((resolve, reject) => {
                if (!checkGeolocationSupport()) {
                    reject(new Error('Geolocation tidak didukung oleh browser ini'));
                    return;
                }

                const options = {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 300000 // 5 menit
                };

                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        resolve({
                            latitude: position.coords.latitude,
                            longitude: position.coords.longitude,
                            accuracy: position.coords.accuracy
                        });
                    },
                    (error) => {
                        let errorMessage = 'Gagal mendapatkan lokasi: ';
                        switch(error.code) {
                            case error.PERMISSION_DENIED:
                                errorMessage += 'Akses lokasi ditolak oleh pengguna';
                                break;
                            case error.POSITION_UNAVAILABLE:
                                errorMessage += 'Informasi lokasi tidak tersedia';
                                break;
                            case error.TIMEOUT:
                                errorMessage += 'Waktu habis dalam mendapatkan lokasi';
                                break;
                            default:
                                errorMessage += 'Kesalahan tidak diketahui';
                                break;
                        }
                        reject(new Error(errorMessage));
                    },
                    options
                );
            });
        }
        ";
    }
}
