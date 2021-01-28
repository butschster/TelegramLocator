<?php

namespace App;

use App\Exceptions\OutOfDateSignature;
use Illuminate\Support\Carbon;

class RoomSignatureManager
{
    private string $secret;

    public function __construct(string $secret)
    {
        $this->secret = $secret;
    }

    public function createSign(string $roomId, string $userHash): string
    {
        $time = now()->getTimestamp();

        return base64_encode($roomId . ':' . $time . ':' . $this->hashData($time . $userHash . $roomId));
    }

    public function validate(string $signature, string $userHash): bool
    {
        $signature = base64_decode($signature);

        if (!$signature) {
            return false;
        }

        if (strpos($signature, ':') < 0) {
            return false;
        }

        [$roomId, $time, $hash] = explode(':', $signature, 4);

        if (Carbon::createFromTimestamp($time)->diffInMinutes() > 5) {
            throw new OutOfDateSignature();
        }

        return hash_equals($hash, $this->hashData($time . $userHash . $roomId));
    }

    private function hashData(string $roomId): string
    {
        return hash_hmac('sha1', $roomId, $this->secret);
    }
}
