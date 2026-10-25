<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UserPresenceService
{
    private $available;

    public function available(): bool
    {
        if ($this->available === null) $this->available = Schema::hasTable('teves_user_presence');

        return $this->available;
    }

    public function windowSeconds(): int
    {
        return min(300, max(1, (int) config('session.lifetime', 120)) * 60);
    }

    public function touch(Request $request): bool
    {
        if (!$request->hasSession() || !$request->session()->has('loginID') || !$this->available()) return false;
        $userId = $request->session()->get('loginID');
        $key = $this->sessionKey($request);
        $now = now('UTC');
        $previous = $request->session()->get('_presence_last_write', []);
        if (($previous['key'] ?? null) === $key && ($previous['user'] ?? null) === $userId &&
            $now->timestamp - ($previous['at'] ?? 0) >= 0 && $now->timestamp - ($previous['at'] ?? 0) < 60) return true;

        $active = DB::table('user_tb')->where('user_id', $userId)->where('user_status', 'Active')->whereNull('deleted_at')->exists();
        if (!$active) return false;
        DB::table('teves_user_presence')->upsert([
            ['session_key' => $key, 'user_idx' => $userId, 'last_seen_at' => $now->toDateTimeString(), 'logged_out_at' => null],
        // A late in-flight request cannot reopen a session explicitly marked as logged out.
        // Signing in again regenerates the browser session and creates a different key.
        ], ['session_key'], ['user_idx', 'last_seen_at']);
        $request->session()->put('_presence_last_write', ['key' => $key, 'user' => $userId, 'at' => $now->timestamp]);

        return true;
    }

    public function logout(Request $request): void
    {
        if (!$request->session()->has('loginID') || !$this->available()) return;
        DB::table('teves_user_presence')->where('session_key', $this->sessionKey($request))
            ->where('user_idx', $request->session()->get('loginID'))
            ->update(['logged_out_at' => now('UTC')->toDateTimeString(), 'last_seen_at' => now('UTC')->toDateTimeString()]);
        $request->session()->forget('_presence_last_write');
    }

    private function sessionKey(Request $request): string
    {
        return hash_hmac('sha256', $request->session()->getId(), (string) config('app.key'));
    }

    private function cutoff(): string
    {
        return now('UTC')->subSeconds($this->windowSeconds())->toDateTimeString();
    }

    private function onlineExpression(): string
    {
        return "CASE WHEN user_tb.user_status = 'Active' AND COALESCE(presence.online_sessions, 0) > 0 THEN 1 ELSE 0 END";
    }

    public function attach($query)
    {
        if (!$this->available()) return $query->selectRaw('NULL AS last_seen_at, NULL AS is_online');
        $aggregate = DB::table('teves_user_presence')->select('user_idx')
            ->selectRaw('MAX(last_seen_at) AS last_seen_at')
            ->selectRaw('SUM(CASE WHEN logged_out_at IS NULL AND last_seen_at >= ? THEN 1 ELSE 0 END) AS online_sessions', [$this->cutoff()])
            ->groupBy('user_idx');

        return $query->leftJoinSub($aggregate, 'presence', function ($join) {
            $join->on('user_tb.user_id', '=', 'presence.user_idx');
        })->addSelect('presence.last_seen_at')->selectRaw($this->onlineExpression() . ' AS is_online');
    }

    public function filter($query, ?string $status): void
    {
        if (!$status) return;
        if (!$this->available()) { $query->whereRaw('1 = 0'); return; }
        $query->whereRaw('(' . $this->onlineExpression() . ') = ?', [$status === 'online' ? 1 : 0]);
    }

    public function onlineCount($visibleUsers): ?int
    {
        if (!$this->available()) return null;
        $online = DB::table('teves_user_presence')->select('user_idx')->whereNull('logged_out_at')->where('last_seen_at', '>=', $this->cutoff());

        return (clone $visibleUsers)->where('user_status', 'Active')->whereIn('user_id', $online)->count();
    }

    public function isoTimestamp(?string $value): ?string
    {
        return $value ? Carbon::parse($value, 'UTC')->toIso8601String() : null;
    }
}
