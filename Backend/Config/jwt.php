<?php
class JWTHandler {
    private $secret = "DU_AN_DOI_SONG_SUC_KHOE_NAM_2026"; 

    // Hàm tạo Token từ dữ liệu User
    public function encode($payload) {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($payload)));
        
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $this->secret, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        
        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    // Hàm giải mã và kiểm tra tính hợp lệ của Token
    public function decode($jwt) {
        $tokenParts = explode('.', $jwt);
        if(count($tokenParts) != 3) return false;
        
        $signature = hash_hmac('sha256', $tokenParts[0] . "." . $tokenParts[1], $this->secret, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        
        if($base64UrlSignature === $tokenParts[2]) {
            $payload = json_decode(base64_decode($tokenParts[1]));
            // Kiểm tra xem token đã hết hạn chưa nếu có đặt trường 'exp'
            if (isset($payload->exp) && $payload->exp < time()) {
                return false; 
            }
            return $payload;
        }
        return false;
    }
}
?>