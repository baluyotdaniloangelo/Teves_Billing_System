<?php

namespace App\Http\Controllers;

use App\Services\SmsTemplateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class SMSTemplateController extends Controller
{
    private function values(Request $request, ?string $key = null): array
    {
        $request->merge([
            'name' => trim((string) $request->input('name')),
            'situation' => trim((string) $request->input('situation')),
            'message' => trim((string) $request->input('message')),
        ]);
        $input = $request->validate([
            'name' => 'required|string|max:100',
            'scope' => ['required', Rule::in(['broadcast', 'birthday', 'order'])],
            'situation' => 'nullable|string|max:255',
            'message' => 'required|string|max:1000',
            'version' => $key ? 'required|integer|min:1' : 'nullable',
        ]);
        if ($key === 'birthday' && $input['scope'] !== 'birthday') {
            throw ValidationException::withMessages(['scope' => 'The daily birthday template must keep the Birthday category.']);
        }
        // Draft brackets are allowed in manual templates, but not in the daily greeting.
        try {
            app(SmsTemplateService::class)->validateMessage($input['message'], $input['scope'], $key !== 'birthday');
        } catch (ValidationException $exception) {
            throw ValidationException::withMessages(['message' => $exception->errors()['sms_content']]);
        }
        return $input;
    }

    public function store(Request $request)
    {
        $input = $this->values($request);
        $key = 'custom_'.str_replace('-', '', (string) Str::uuid());
        DB::table('teves_sms_templates')->insert([
            'template_key' => $key, 'name' => $input['name'], 'scope' => $input['scope'],
            'situation' => $input['situation'] ?? '', 'message' => $input['message'], 'is_active' => true, 'version' => 1,
            'created_by' => Session::get('loginID'), 'updated_by' => Session::get('loginID'), 'created_at' => now(), 'updated_at' => now(),
        ]);
        return redirect()->route('sms_blast', ['tab' => 'templates'])->with('sms_template_status', 'Template added. You can now select it when composing a message.');
    }

    public function update(Request $request, string $key)
    {
        abort_unless(DB::table('teves_sms_templates')->where('template_key', $key)->exists(), 404);
        $input = $this->values($request, $key);
        $changed = DB::table('teves_sms_templates')->where('template_key', $key)->where('version', $input['version'])->update([
            'name' => $input['name'], 'scope' => $input['scope'], 'situation' => $input['situation'] ?? '', 'message' => $input['message'],
            'version' => DB::raw('version + 1'), 'updated_by' => Session::get('loginID'), 'updated_at' => now(),
        ]);
        $this->checkVersion($changed);
        return redirect()->route('sms_blast', ['tab' => 'templates'])->with('sms_template_status', 'Template updated. Existing message history keeps its original messages.');
    }

    public function status(Request $request, string $key)
    {
        abort_unless(DB::table('teves_sms_templates')->where('template_key', $key)->exists(), 404);
        $input = $request->validate(['version' => 'required|integer|min:1', 'is_active' => 'required|boolean']);
        if ($key === 'birthday' && !$input['is_active']) {
            throw ValidationException::withMessages(['template' => 'The daily birthday template can be edited, but cannot be archived.']);
        }
        $changed = DB::table('teves_sms_templates')->where('template_key', $key)->where('version', $input['version'])->update([
            'is_active' => $input['is_active'], 'version' => DB::raw('version + 1'), 'updated_by' => Session::get('loginID'), 'updated_at' => now(),
        ]);
        $this->checkVersion($changed);
        return redirect()->route('sms_blast', ['tab' => 'templates'])->with('sms_template_status', $input['is_active'] ? 'Template restored.' : 'Template archived. It is no longer available in the compose list.');
    }

    private function checkVersion(int $changed): void
    {
        if (!$changed) throw ValidationException::withMessages(['template' => 'Someone has updated this template. Reload the latest version before saving again.']);
    }
}
