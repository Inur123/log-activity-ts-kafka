<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use App\Jobs\ProcessUnifiedLog;

class LogController extends Controller
{
    /**
     * Kirim log event ke sistem.
     *
     * Contoh payload per log_type:
     *
     * DATA_CREATE
     * ```json
     * {"log_type":"DATA_CREATE","payload":{"user_id":2,"data":{"resource":"product","id":10,"name":"Laptop"}}}
     * ```
     *
     * DATA_UPDATE
     * ```json
     * {"log_type":"DATA_UPDATE","payload":{"user_id":2,"before":{"id":10,"price":1000},"after":{"id":10,"price":1200}}}
     * ```
     *
     * DATA_DELETE
     * ```json
     * {"log_type":"DATA_DELETE","payload":{"user_id":2,"id":10,"reason":"Deleted by admin"}}
     * ```
     *
     * STATUS_CHANGE
     * ```json
     * {"log_type":"STATUS_CHANGE","payload":{"user_id":2,"id":99,"from":"draft","to":"published"}}
     * ```
     *
     * ACCESS_ENDPOINT
     * ```json
     * {"log_type":"ACCESS_ENDPOINT","payload":{"user_id":2,"endpoint":"/products","method":"GET","status":200}}
     * ```
     *
     * DOWNLOAD_DOCUMENT
     * ```json
     * {"log_type":"DOWNLOAD_DOCUMENT","payload":{"user_id":2,"document_id":"DOC-99","document_name":"report.pdf"}}
     * ```
     *
     * SEND_EXTERNAL
     * ```json
     * {"log_type":"SEND_EXTERNAL","payload":{"user_id":2,"channel":"EMAIL","to":"customer@gmail.com","message":"Invoice sent"}}
     * ```
     *
     * AUTH_LOGIN
     * ```json
     * {"log_type":"AUTH_LOGIN","payload":{"user_id":2,"email":"admin@gmail.com","device":"Chrome Windows"}}
     * ```
     *
     * AUTH_LOGOUT
     * ```json
     * {"log_type":"AUTH_LOGOUT","payload":{"user_id":2,"email":"admin@gmail.com"}}
     * ```
     *
     * AUTH_LOGIN_FAILED
     * ```json
     * {"log_type":"AUTH_LOGIN_FAILED","payload":{"user_id":null,"email":"admin@gmail.com","device":"Firefox Linux"}}
     * ```
     *
     * BULK_IMPORT
     * ```json
     * {"log_type":"BULK_IMPORT","payload":{"user_id":2,"total_rows":100,"success":95,"failed":5,"file_name":"import.xlsx"}}
     * ```
     *
     * BULK_EXPORT
     * ```json
     * {"log_type":"BULK_EXPORT","payload":{"user_id":2,"total_rows":200,"success":200,"failed":0,"file_name":"export.xlsx"}}
     * ```
     *
     * SYSTEM_ERROR
     * ```json
     * {"log_type":"SYSTEM_ERROR","payload":{"message":"Route not defined","code":"RouteNotFoundException","context":{"url":"/products","method":"GET"}}}
     * ```
     *
     * SECURITY_VIOLATION
     * ```json
     * {"log_type":"SECURITY_VIOLATION","payload":{"user_id":null,"reason":"Brute force attempt","meta":{"email":"admin@gmail.com","attempt":5}}}
     * ```
     *
     * PERMISSION_CHANGE
     * ```json
     * {"log_type":"PERMISSION_CHANGE","payload":{"user_id":1,"target_user_id":2,"before":{"role":"user"},"after":{"role":"admin"}}}
     * ```
     *
     * @group Logs
     * @header X-API-Key string required API Key aplikasi.
     * @bodyParam log_type string required Jenis log. Allowed: AUTH_LOGIN, AUTH_LOGOUT, AUTH_LOGIN_FAILED, ACCESS_ENDPOINT, DOWNLOAD_DOCUMENT, SEND_EXTERNAL, DATA_CREATE, DATA_UPDATE, DATA_DELETE, STATUS_CHANGE, BULK_IMPORT, BULK_EXPORT, SYSTEM_ERROR, SECURITY_VIOLATION, PERMISSION_CHANGE.
     * @bodyParam payload object required Data log sesuai log_type.
     * @bodyParam payload.user_id integer ID user. Required untuk: ACCESS_ENDPOINT, DOWNLOAD_DOCUMENT, SEND_EXTERNAL, DATA_CREATE, DATA_UPDATE, DATA_DELETE, STATUS_CHANGE, BULK_IMPORT, BULK_EXPORT, PERMISSION_CHANGE. Nullable untuk AUTH_* dan SECURITY_VIOLATION.
     * @bodyParam payload.email string Email user. Required untuk: AUTH_LOGIN, AUTH_LOGOUT, AUTH_LOGIN_FAILED.
     * @bodyParam payload.ip string IP address. Optional untuk AUTH_* dan SECURITY_VIOLATION.
     * @bodyParam payload.device string Informasi device. Optional untuk AUTH_*.
     * @bodyParam payload.endpoint string Endpoint yang diakses. Required untuk: ACCESS_ENDPOINT.
     * @bodyParam payload.method string HTTP method. Required untuk: ACCESS_ENDPOINT. Allowed: GET, POST, PUT, PATCH, DELETE.
     * @bodyParam payload.status integer HTTP status code. Required untuk: ACCESS_ENDPOINT.
     * @bodyParam payload.document_id string ID dokumen. Required untuk: DOWNLOAD_DOCUMENT.
     * @bodyParam payload.document_name string Nama dokumen. Optional untuk: DOWNLOAD_DOCUMENT.
     * @bodyParam payload.channel string Channel. Required untuk: SEND_EXTERNAL. Allowed: WA, EMAIL, API.
     * @bodyParam payload.to string Tujuan pengiriman. Required untuk: SEND_EXTERNAL. Juga digunakan sebagai status akhir untuk STATUS_CHANGE.
     * @bodyParam payload.message string Pesan. Required untuk: SYSTEM_ERROR. Optional untuk: SEND_EXTERNAL.
     * @bodyParam payload.meta object Metadata tambahan. Optional untuk: SEND_EXTERNAL, SECURITY_VIOLATION.
     * @bodyParam payload.data object Data yang dibuat. Required untuk: DATA_CREATE.
     * @bodyParam payload.before object Data sebelum perubahan. Required untuk: DATA_UPDATE, PERMISSION_CHANGE.
     * @bodyParam payload.after object Data sesudah perubahan. Required untuk: DATA_UPDATE, PERMISSION_CHANGE.
     * @bodyParam payload.id string ID record. Required untuk: DATA_DELETE, STATUS_CHANGE.
     * @bodyParam payload.reason string Alasan. Required untuk: SECURITY_VIOLATION. Optional untuk: DATA_DELETE.
     * @bodyParam payload.from string Status awal. Required untuk: STATUS_CHANGE.
     * @bodyParam payload.total_rows integer Total baris. Required untuk: BULK_IMPORT, BULK_EXPORT.
     * @bodyParam payload.success integer Total sukses. Required untuk: BULK_IMPORT, BULK_EXPORT.
     * @bodyParam payload.failed integer Total gagal. Required untuk: BULK_IMPORT, BULK_EXPORT.
     * @bodyParam payload.file_name string Nama file. Optional untuk: BULK_IMPORT, BULK_EXPORT.
     * @bodyParam payload.code string Kode error. Optional untuk: SYSTEM_ERROR.
     * @bodyParam payload.trace_id string Trace ID. Optional untuk: SYSTEM_ERROR.
     * @bodyParam payload.context object Konteks tambahan. Optional untuk: SYSTEM_ERROR.
     * @bodyParam payload.target_user_id integer Target user ID. Required untuk: PERMISSION_CHANGE.
     * @response 202 {"success":true,"message":"Log received and queued for processing","queued_at":"2024-01-15 10:30:45"}
     * @response 401 {"success":false,"message":"Invalid application context"}
     * @response 422 {"success":false,"message":"Validation failed","errors":{"log_type":["The log_type field is required."]}}
     */
    public function store(Request $request)
    {
        $application = $request->input('application');

        if (! $application) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid application context',
            ], 401);
        }

        /**
         *  Rate limit per application_id (maks 1000/minute)
         */
        $key = 'api:' . $application->id;

        if (RateLimiter::tooManyAttempts($key, 1000)) {
            return response()->json([
                'success'     => false,
                'message'     => 'Too Many Requests',
                'retry_after' => RateLimiter::availableIn($key),
            ], 429);
        }

        RateLimiter::hit($key, 60);

        /**
         *  Decode raw body kalau request->all() kosong
         */
        $rawBody = [];
        if ($request->getContent()) {
            $decoded = json_decode($request->getContent(), true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $rawBody = $decoded;

                if (empty($request->all())) {
                    $request->merge($rawBody);
                }
            }
        }

        /**
         *  Ambil log_type (fallback root -> raw -> payload.log_type)
         */
        $rawLogType = $request->input('log_type')
            ?? ($rawBody['log_type'] ?? null)
            ?? data_get($request->input('payload', []), 'log_type')
            ?? ($rawBody['payload']['log_type'] ?? null);

        $logType = strtoupper(trim((string) $rawLogType));
        if ($logType === '') $logType = 'UNKNOWN';

        $request->merge(['log_type' => $logType]);

        /**
         *  Decode payload kalau string JSON
         */
        $rawPayload = $request->input('payload');

        if (is_string($rawPayload)) {
            $decoded = json_decode($rawPayload, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $rawPayload = $decoded;
            } else {
                $rawPayload = ['_raw' => $rawPayload];
            }

            $request->merge(['payload' => $rawPayload]);
        }

        if ($rawPayload === null) {
            $request->merge(['payload' => []]);
        }

        /**
         *  BASIC validation
         */
        $validator = Validator::make($request->all(), [
            'log_type' => 'required|string|in:' . implode(',', $this->allowedLogTypes()),
            'payload'  => 'required|array',
        ]);

        if ($validator->fails()) {
            $this->dispatchValidationFailed(
                application: $application,
                originalLogType: $logType,
                stage: 'BASIC',
                errors: $validator->errors()->toArray(),
                originalPayload: (array) $request->input('payload', [])
            );

            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        /**
         *  PAYLOAD validation by log_type
         */
        $payloadRules = $this->payloadRulesFor($logType);

        if (! empty($payloadRules)) {
            $payloadValidator = Validator::make($request->input('payload', []), $payloadRules);

            if ($payloadValidator->fails()) {
                $this->dispatchValidationFailed(
                    application: $application,
                    originalLogType: $logType,
                    stage: 'PAYLOAD',
                    errors: $payloadValidator->errors()->toArray(),
                    originalPayload: (array) $request->input('payload', [])
                );

                return response()->json([
                    'success' => false,
                    'message' => 'Payload validation failed',
                    'errors'  => $payloadValidator->errors(),
                ], 422);
            }
        }

        try {
            /**
             *  VALID request -> PASSED
             */
            $payload = $request->input('payload', []);
            $payload['validation'] = [
                'status' => 'PASSED',
                'stage'  => null,
                'errors' => null,
            ];

            $logData = [
                'application_id' => $application->id,
                'log_type'       => $logType,
                'payload'        => $payload,
                'ip_address'     => $request->ip(),
                'user_agent'     => $request->userAgent(),
            ];

            ProcessUnifiedLog::dispatch($logData)->onQueue('logs');

            return response()->json([
                'success'   => true,
                'message'   => 'Log received and queued for processing',
                'queued_at' => now()->toDateTimeString(),
            ], 202);

        } catch (\Throwable $e) {

            Log::error('Failed to queue log', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to process log request',
            ], 500);
        }
    }

    /**
     *  Log invalid request/payload
     */
    private function dispatchValidationFailed($application, string $originalLogType, string $stage, array $errors, array $originalPayload): void
    {
        $logType = strtoupper(trim($originalLogType));
        if ($logType === '') $logType = 'UNKNOWN';

        $rateKey = 'validation_failed:' . $application->id . ':' . md5($logType . $stage . json_encode($errors));

        if (RateLimiter::tooManyAttempts($rateKey, 30)) {
            return;
        }

        RateLimiter::hit($rateKey, 60);

        $payload = array_merge($originalPayload, [
            'validation' => [
                'status' => 'FAILED',
                'stage'  => $stage,
                'errors' => $errors,
            ],
        ]);

        $validationLogData = [
            'application_id' => $application->id,
            'log_type'       => $logType,
            'payload'        => $payload,
            'ip_address'     => request()?->ip(),
            'user_agent'     => request()?->userAgent(),
        ];

        ProcessUnifiedLog::dispatch($validationLogData)->onQueue('logs');
    }

    private function allowedLogTypes(): array
    {
        return [
            'AUTH_LOGIN',
            'AUTH_LOGOUT',
            'AUTH_LOGIN_FAILED',

            'ACCESS_ENDPOINT',
            'DOWNLOAD_DOCUMENT',
            'SEND_EXTERNAL',

            'DATA_CREATE',
            'DATA_UPDATE',
            'DATA_DELETE',
            'STATUS_CHANGE',
            'BULK_IMPORT',
            'BULK_EXPORT',

            'SYSTEM_ERROR',
            'SECURITY_VIOLATION',
            'PERMISSION_CHANGE',
        ];
    }

    private function payloadRulesFor(string $logType): array
    {
        return match ($logType) {

            'AUTH_LOGIN',
            'AUTH_LOGOUT',
            'AUTH_LOGIN_FAILED' => [
                'user_id' => 'nullable|integer',
                'email'   => 'required|email',
                'ip'      => 'nullable|string',
                'device'  => 'nullable|string',
            ],

            'ACCESS_ENDPOINT' => [
                'user_id'   => 'required|integer',
                'endpoint'  => 'required|string',
                'method'    => 'required|string|in:GET,POST,PUT,PATCH,DELETE',
                'ip'        => 'nullable|string',
                'status'    => 'required|integer',
            ],

            'DOWNLOAD_DOCUMENT' => [
                'user_id'        => 'required|integer',
                'document_id'    => 'required',
                'document_name'  => 'nullable|string',
                'ip'             => 'nullable|string',
            ],

            'SEND_EXTERNAL' => [
                'user_id'  => 'required|integer',
                'channel'  => 'required|string|in:WA,EMAIL,API',
                'to'       => 'required|string',
                'message'  => 'nullable|string',
                'meta'     => 'nullable|array',
            ],

            'DATA_CREATE' => [
                'user_id' => 'required|integer',
                'data'    => 'required|array',
            ],

            'DATA_UPDATE' => [
                'user_id' => 'required|integer',
                'before'  => 'required|array',
                'after'   => 'required|array',
            ],

            'DATA_DELETE' => [
                'user_id' => 'required|integer',
                'id'      => 'required',
                'reason'  => 'nullable|string',
            ],

            'STATUS_CHANGE' => [
                'user_id' => 'required|integer',
                'id'      => 'required',
                'from'    => 'required|string',
                'to'      => 'required|string',
            ],

            'BULK_IMPORT',
            'BULK_EXPORT' => [
                'user_id'     => 'required|integer',
                'total_rows'  => 'required|integer|min:1',
                'success'     => 'required|integer|min:0',
                'failed'      => 'required|integer|min:0',
                'file_name'   => 'nullable|string',
            ],

            'SYSTEM_ERROR' => [
                'message'   => 'required|string',
                'code'      => 'nullable|string',
                'trace_id'  => 'nullable|string',
                'context'   => 'nullable|array',
            ],

            'SECURITY_VIOLATION' => [
                'user_id' => 'nullable|integer',
                'ip'      => 'nullable|string',
                'reason'  => 'required|string',
                'meta'    => 'nullable|array',
            ],

            'PERMISSION_CHANGE' => [
                'user_id'        => 'required|integer',
                'target_user_id' => 'required|integer',
                'before'         => 'required|array',
                'after'          => 'required|array',
            ],

            default => [],
        };
    }
}
