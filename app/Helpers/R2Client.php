<?php

namespace App\Helpers;

class R2Client
{
    protected $accountId;
    protected $accessKey;
    protected $secretKey;
    protected $bucket;
    protected $region = 'auto';
    protected $service = 's3';
    protected $endpoint;
    protected $publicUrl;

    public function __construct()
    {
        $this->accountId = env('R2_ACCOUNT_ID');
        $this->accessKey = env('R2_ACCESS_KEY_ID');
        $this->secretKey = env('R2_SECRET_ACCESS_KEY');
        $this->bucket    = env('R2_BUCKET');
        $this->publicUrl = env('R2_PUBLIC_URL');
        $this->endpoint  = "https://{$this->accountId}.r2.cloudflarestorage.com";
    }

    /**
     * Upload file ke R2 menggunakan cURL native
     */
    public function upload($filePath, $path, $contentType)
    {
        $now = gmdate('Ymd\THis\Z');
        $dateStamp = gmdate('Ymd');

        $payload = file_get_contents($filePath);
        $payloadHash = hash('sha256', $payload);

        $url = "{$this->endpoint}/{$this->bucket}/{$path}";
        $method = 'PUT';

        // Canonical Request
        $canonicalHeaders = "content-type:{$contentType}\nhost:{$this->accountId}.r2.cloudflarestorage.com\nx-amz-content-sha256:{$payloadHash}\nx-amz-date:{$now}\n";
        $signedHeaders = 'content-type;host;x-amz-content-sha256;x-amz-date';

        $canonicalRequest = implode("\n", [
            $method,
            "/{$this->bucket}/{$path}",
            '',
            $canonicalHeaders,
            $signedHeaders,
            $payloadHash,
        ]);

        // String to Sign
        $credentialScope = "{$dateStamp}/{$this->region}/{$this->service}/aws4_request";
        $stringToSign = implode("\n", [
            'AWS4-HMAC-SHA256',
            $now,
            $credentialScope,
            hash('sha256', $canonicalRequest),
        ]);

        // Signing Key
        $kDate = hash_hmac('sha256', $dateStamp, 'AWS4' . $this->secretKey, true);
        $kRegion = hash_hmac('sha256', $this->region, $kDate, true);
        $kService = hash_hmac('sha256', $this->service, $kRegion, true);
        $kSigning = hash_hmac('sha256', 'aws4_request', $kService, true);

        // Signature
        $signature = hash_hmac('sha256', $stringToSign, $kSigning);

        // Authorization Header
        $authorization = "AWS4-HMAC-SHA256 Credential={$this->accessKey}/{$credentialScope}, SignedHeaders={$signedHeaders}, Signature={$signature}";

        // cURL Request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: {$authorization}",
            "x-amz-date: {$now}",
            "x-amz-content-sha256: {$payloadHash}",
            "Content-Type: {$contentType}",
        ]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Untuk Laragon lokal

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new \Exception('cURL Error: ' . $error);
        }

        if ($httpCode >= 200 && $httpCode < 300) {
            return true;
        }

        throw new \Exception("R2 Upload Failed (HTTP {$httpCode}): " . $response);
    }

    /**
     * Hapus file dari R2
     */
    public function delete($path)
    {
        $now = gmdate('Ymd\THis\Z');
        $dateStamp = gmdate('Ymd');
        $payloadHash = hash('sha256', '');

        $url = "{$this->endpoint}/{$this->bucket}/{$path}";
        $method = 'DELETE';

        $canonicalHeaders = "host:{$this->accountId}.r2.cloudflarestorage.com\nx-amz-content-sha256:{$payloadHash}\nx-amz-date:{$now}\n";
        $signedHeaders = 'host;x-amz-content-sha256;x-amz-date';

        $canonicalRequest = implode("\n", [
            $method,
            "/{$this->bucket}/{$path}",
            '',
            $canonicalHeaders,
            $signedHeaders,
            $payloadHash,
        ]);

        $credentialScope = "{$dateStamp}/{$this->region}/{$this->service}/aws4_request";
        $stringToSign = implode("\n", [
            'AWS4-HMAC-SHA256',
            $now,
            $credentialScope,
            hash('sha256', $canonicalRequest),
        ]);

        $kDate = hash_hmac('sha256', $dateStamp, 'AWS4' . $this->secretKey, true);
        $kRegion = hash_hmac('sha256', $this->region, $kDate, true);
        $kService = hash_hmac('sha256', $this->service, $kRegion, true);
        $kSigning = hash_hmac('sha256', 'aws4_request', $kService, true);
        $signature = hash_hmac('sha256', $stringToSign, $kSigning);

        $authorization = "AWS4-HMAC-SHA256 Credential={$this->accessKey}/{$credentialScope}, SignedHeaders={$signedHeaders}, Signature={$signature}";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: {$authorization}",
            "x-amz-date: {$now}",
            "x-amz-content-sha256: {$payloadHash}",
        ]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $httpCode >= 200 && $httpCode < 300;
    }

    /**
     * Untuk Show Public URL
     */
    public function getUrl($path)
    {
        return rtrim($this->publicUrl, '/') . '/' . ltrim($path, '/');
    }
}
