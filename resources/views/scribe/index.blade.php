<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Log Activity API Documentation</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.style.css") }}" media="screen">
    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.print.css") }}" media="print">

    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>

    <link rel="stylesheet"
          href="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/styles/obsidian.min.css">
    <script src="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/highlight.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jets/0.14.1/jets.min.js"></script>

    <style id="language-style">
        /* starts out as display none and is replaced with js later  */
                    body .content .bash-example code { display: none; }
                    body .content .javascript-example code { display: none; }
            </style>

    <script>
        var tryItOutBaseUrl = "http://localhost";
        var useCsrf = Boolean();
        var csrfUrl = "/sanctum/csrf-cookie";
    </script>
    <script src="{{ asset("/vendor/scribe/js/tryitout-5.6.0.js") }}"></script>

    <script src="{{ asset("/vendor/scribe/js/theme-default-5.6.0.js") }}"></script>

</head>

<body data-languages="[&quot;bash&quot;,&quot;javascript&quot;]">

<a href="#" id="nav-button">
    <span>
        MENU
        <img src="{{ asset("/vendor/scribe/images/navbar.png") }}" alt="navbar-image"/>
    </span>
</a>
<div class="tocify-wrapper">
    
            <div class="lang-selector">
                                            <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                            <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                    </div>
    
    <div class="search">
        <input type="text" class="search" id="input-search" placeholder="Search">
    </div>

    <div id="toc">
                    <ul id="tocify-header-introduction" class="tocify-header">
                <li class="tocify-item level-1" data-unique="introduction">
                    <a href="#introduction">Introduction</a>
                </li>
                            </ul>
                    <ul id="tocify-header-authenticating-requests" class="tocify-header">
                <li class="tocify-item level-1" data-unique="authenticating-requests">
                    <a href="#authenticating-requests">Authenticating requests</a>
                </li>
                            </ul>
                    <ul id="tocify-header-endpoints" class="tocify-header">
                <li class="tocify-item level-1" data-unique="endpoints">
                    <a href="#endpoints">Endpoints</a>
                </li>
                                    <ul id="tocify-subheader-endpoints" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-user">
                                <a href="#endpoints-GETapi-user">GET api/user</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-logs">
                                <a href="#endpoints-GETapi-v1-logs">GET api/v1/logs</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-logs" class="tocify-header">
                <li class="tocify-item level-1" data-unique="logs">
                    <a href="#logs">Logs</a>
                </li>
                                    <ul id="tocify-subheader-logs" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="logs-POSTapi-v1-logs">
                                <a href="#logs-POSTapi-v1-logs">Kirim log event ke sistem.</a>
                            </li>
                                                                        </ul>
                            </ul>
            </div>

    <ul class="toc-footer" id="toc-footer">
                    <li style="padding-bottom: 5px;"><a href="{{ route("scribe.postman") }}">View Postman collection</a></li>
                            <li style="padding-bottom: 5px;"><a href="{{ route("scribe.openapi") }}">View OpenAPI spec</a></li>
                <li><a href="http://github.com/knuckleswtf/scribe">Documentation powered by Scribe ✍</a></li>
    </ul>

    <ul class="toc-footer" id="last-updated">
        <li>Last updated: February 3, 2026</li>
    </ul>
</div>

<div class="page-wrapper">
    <div class="dark-box"></div>
    <div class="content">
        <h1 id="introduction">Introduction</h1>
<p>Dokumentasi resmi untuk Unified Logging API.</p>
<aside>
    <strong>Base URL</strong>: <code>http://localhost</code>
</aside>
<pre><code>Dokumentasi ini menjelaskan cara mengirim log ke sistem Unified Logging API.

&lt;aside&gt;Contoh request tersedia di panel kanan. Anda bisa mengganti bahasa contoh di bagian atas dokumentasi.&lt;/aside&gt;</code></pre>

        <h1 id="authenticating-requests">Authenticating requests</h1>
<p>To authenticate requests, include a <strong><code>X-API-Key</code></strong> header with the value <strong><code>"{YOUR_API_KEY}"</code></strong>.</p>
<p>All authenticated endpoints are marked with a <code>requires authentication</code> badge in the documentation below.</p>
<p>Gunakan API key yang aktif untuk aplikasi Anda.</p>

        <h1 id="endpoints">Endpoints</h1>

    

                                <h2 id="endpoints-GETapi-user">GET api/user</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-user">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/user" \
    --header "X-API-Key: {YOUR_API_KEY}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/user"
);

const headers = {
    "X-API-Key": "{YOUR_API_KEY}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-user">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-user" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-user"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-user"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-user" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-user">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-user" data-method="GET"
      data-path="api/user"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-user', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-user"
                    onclick="tryItOut('GETapi-user');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-user"
                    onclick="cancelTryOut('GETapi-user');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-user"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/user</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>X-API-Key</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="X-API-Key" class="auth-value"               data-endpoint="GETapi-user"
               value="{YOUR_API_KEY}"
               data-component="header">
    <br>
<p>Example: <code>{YOUR_API_KEY}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-user"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-user"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-v1-logs">GET api/v1/logs</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-logs">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/v1/logs" \
    --header "X-API-Key: {YOUR_API_KEY}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/logs"
);

const headers = {
    "X-API-Key": "{YOUR_API_KEY}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-logs">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
set-cookie: XSRF-TOKEN=eyJpdiI6Im1GbVNROUdndCtmYWxqWExEemhEeVE9PSIsInZhbHVlIjoiWDlIc2M4SHNCZ0ZOR1lUR3RJazhnb3ZXY2EyZ3kyWGdsbHduT1RwR0UyYVFrdmluSHJsUFhva3J5aXNOLzQ2aTcxb3ZkWTVXVEk2a0R2d3VCVEEzREh6NnF2NUF5TWdpL0U0Si9DMEZrd2NLNUpyeldwci81eVFtR0dxRGh0Nm4iLCJtYWMiOiJhNDIxMzk4NTA5NWM5OTBlZmEzZWViYTVjNjA5MTdkNmY2MjA2MGZkZmE0YmNjNmYyYmE2ZDhiZjIwZmZkN2I5IiwidGFnIjoiIn0%3D; expires=Tue, 03 Feb 2026 04:25:44 GMT; Max-Age=7200; path=/; samesite=lax; log-activity-session=eyJpdiI6ImNoc2ZvaWhYVkVtcGxZNG4xZFByc0E9PSIsInZhbHVlIjoicDkxRC9ybnJjN2Z3dms3aFp5R0IycldGM1NFNkllMmJqVXlla2t6T3NSaHUxY0d0cGdORm9pN1lGQnZvNm1hd2wydFRJRTFqeHVrYWtUWk1ZVEwvQjdXa3htMTlyVXRuWXdyN2RMUzM5S2ZtUUJvSVVqQy9Xa2VZVk40WkZqdzgiLCJtYWMiOiIyMjcyZmE3NTA5NTYwYmJkNTViNTJmOGQ0ODc3YTgyNDg3M2I1NTNkNmQyYWJmZDdhODZmZjg1NzEyMThhYjZlIiwidGFnIjoiIn0%3D; expires=Tue, 03 Feb 2026 04:25:44 GMT; Max-Age=7200; path=/; httponly; samesite=lax
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">&lt;!doctype html&gt;
&lt;html lang=&quot;id&quot;&gt;
&lt;head&gt;
    &lt;meta charset=&quot;utf-8&quot;&gt;
    &lt;meta name=&quot;viewport&quot; content=&quot;width=device-width, initial-scale=1&quot;&gt;
    &lt;title&gt;Error 404&lt;/title&gt;
&lt;/head&gt;

&lt;body
    style=&quot;
        min-height:100vh;
        display:flex;
        align-items:center;
        justify-content:center;
        font-family:system-ui, sans-serif;
        background:#0f172a;
        color:#e5e7eb;
    &quot;
&gt;
    &lt;div style=&quot;text-align:center; max-width:480px&quot;&gt;
        &lt;div style=&quot;font-size:14px; opacity:.7&quot;&gt;ERROR&lt;/div&gt;

        &lt;div style=&quot;font-size:56px; font-weight:700; margin:8px 0&quot;&gt;
            404
        &lt;/div&gt;
    &lt;/div&gt;
&lt;/body&gt;
&lt;/html&gt;
</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-logs" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-logs"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-logs"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-logs" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-logs">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-logs" data-method="GET"
      data-path="api/v1/logs"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-logs', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-logs"
                    onclick="tryItOut('GETapi-v1-logs');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-logs"
                    onclick="cancelTryOut('GETapi-v1-logs');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-logs"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/logs</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>X-API-Key</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="X-API-Key" class="auth-value"               data-endpoint="GETapi-v1-logs"
               value="{YOUR_API_KEY}"
               data-component="header">
    <br>
<p>Example: <code>{YOUR_API_KEY}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-logs"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-logs"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                <h1 id="logs">Logs</h1>

    

                                <h2 id="logs-POSTapi-v1-logs">Kirim log event ke sistem.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Contoh payload per log_type:</p>
<p>DATA_CREATE</p>
<pre><code class="language-json">{"log_type":"DATA_CREATE","payload":{"user_id":2,"data":{"resource":"product","id":10,"name":"Laptop"}}}</code></pre>
<p>DATA_UPDATE</p>
<pre><code class="language-json">{"log_type":"DATA_UPDATE","payload":{"user_id":2,"before":{"id":10,"price":1000},"after":{"id":10,"price":1200}}}</code></pre>
<p>DATA_DELETE</p>
<pre><code class="language-json">{"log_type":"DATA_DELETE","payload":{"user_id":2,"id":10,"reason":"Deleted by admin"}}</code></pre>
<p>STATUS_CHANGE</p>
<pre><code class="language-json">{"log_type":"STATUS_CHANGE","payload":{"user_id":2,"id":99,"from":"draft","to":"published"}}</code></pre>
<p>ACCESS_ENDPOINT</p>
<pre><code class="language-json">{"log_type":"ACCESS_ENDPOINT","payload":{"user_id":2,"endpoint":"/products","method":"GET","status":200}}</code></pre>
<p>DOWNLOAD_DOCUMENT</p>
<pre><code class="language-json">{"log_type":"DOWNLOAD_DOCUMENT","payload":{"user_id":2,"document_id":"DOC-99","document_name":"report.pdf"}}</code></pre>
<p>SEND_EXTERNAL</p>
<pre><code class="language-json">{"log_type":"SEND_EXTERNAL","payload":{"user_id":2,"channel":"EMAIL","to":"customer@gmail.com","message":"Invoice sent"}}</code></pre>
<p>AUTH_LOGIN</p>
<pre><code class="language-json">{"log_type":"AUTH_LOGIN","payload":{"user_id":2,"email":"admin@gmail.com","device":"Chrome Windows"}}</code></pre>
<p>AUTH_LOGOUT</p>
<pre><code class="language-json">{"log_type":"AUTH_LOGOUT","payload":{"user_id":2,"email":"admin@gmail.com"}}</code></pre>
<p>AUTH_LOGIN_FAILED</p>
<pre><code class="language-json">{"log_type":"AUTH_LOGIN_FAILED","payload":{"user_id":null,"email":"admin@gmail.com","device":"Firefox Linux"}}</code></pre>
<p>BULK_IMPORT</p>
<pre><code class="language-json">{"log_type":"BULK_IMPORT","payload":{"user_id":2,"total_rows":100,"success":95,"failed":5,"file_name":"import.xlsx"}}</code></pre>
<p>BULK_EXPORT</p>
<pre><code class="language-json">{"log_type":"BULK_EXPORT","payload":{"user_id":2,"total_rows":200,"success":200,"failed":0,"file_name":"export.xlsx"}}</code></pre>
<p>SYSTEM_ERROR</p>
<pre><code class="language-json">{"log_type":"SYSTEM_ERROR","payload":{"message":"Route not defined","code":"RouteNotFoundException","context":{"url":"/products","method":"GET"}}}</code></pre>
<p>SECURITY_VIOLATION</p>
<pre><code class="language-json">{"log_type":"SECURITY_VIOLATION","payload":{"user_id":null,"reason":"Brute force attempt","meta":{"email":"admin@gmail.com","attempt":5}}}</code></pre>
<p>PERMISSION_CHANGE</p>
<pre><code class="language-json">{"log_type":"PERMISSION_CHANGE","payload":{"user_id":1,"target_user_id":2,"before":{"role":"user"},"after":{"role":"admin"}}}</code></pre>

<span id="example-requests-POSTapi-v1-logs">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/v1/logs" \
    --header "X-API-Key: string required API Key aplikasi." \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"log_type\": \"architecto\",
    \"payload\": {
        \"user_id\": 16,
        \"email\": \"gbailey@example.net\",
        \"ip\": \"architecto\",
        \"device\": \"architecto\",
        \"endpoint\": \"architecto\",
        \"method\": \"architecto\",
        \"status\": 16,
        \"document_id\": \"architecto\",
        \"document_name\": \"architecto\",
        \"channel\": \"architecto\",
        \"to\": \"architecto\",
        \"message\": \"architecto\",
        \"meta\": [],
        \"data\": [],
        \"before\": [],
        \"after\": [],
        \"id\": \"architecto\",
        \"reason\": \"architecto\",
        \"from\": \"architecto\",
        \"total_rows\": 16,
        \"success\": 16,
        \"failed\": 16,
        \"file_name\": \"architecto\",
        \"code\": \"architecto\",
        \"trace_id\": \"architecto\",
        \"context\": [],
        \"target_user_id\": 16
    }
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/v1/logs"
);

const headers = {
    "X-API-Key": "string required API Key aplikasi.",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "log_type": "architecto",
    "payload": {
        "user_id": 16,
        "email": "gbailey@example.net",
        "ip": "architecto",
        "device": "architecto",
        "endpoint": "architecto",
        "method": "architecto",
        "status": 16,
        "document_id": "architecto",
        "document_name": "architecto",
        "channel": "architecto",
        "to": "architecto",
        "message": "architecto",
        "meta": [],
        "data": [],
        "before": [],
        "after": [],
        "id": "architecto",
        "reason": "architecto",
        "from": "architecto",
        "total_rows": 16,
        "success": 16,
        "failed": 16,
        "file_name": "architecto",
        "code": "architecto",
        "trace_id": "architecto",
        "context": [],
        "target_user_id": 16
    }
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-logs">
            <blockquote>
            <p>Example response (202):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Log received and queued for processing&quot;,
    &quot;queued_at&quot;: &quot;2024-01-15 10:30:45&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Invalid application context&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Validation failed&quot;,
    &quot;errors&quot;: {
        &quot;log_type&quot;: [
            &quot;The log_type field is required.&quot;
        ]
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-logs" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-logs"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-logs"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-logs" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-logs">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-logs" data-method="POST"
      data-path="api/v1/logs"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-logs', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-logs"
                    onclick="tryItOut('POSTapi-v1-logs');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-logs"
                    onclick="cancelTryOut('POSTapi-v1-logs');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-logs"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/logs</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>X-API-Key</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="X-API-Key" class="auth-value"               data-endpoint="POSTapi-v1-logs"
               value="string required API Key aplikasi."
               data-component="header">
    <br>
<p>Example: <code>string required API Key aplikasi.</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-logs"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-logs"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>log_type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="log_type"                data-endpoint="POSTapi-v1-logs"
               value="architecto"
               data-component="body">
    <br>
<p>Jenis log. Allowed: AUTH_LOGIN, AUTH_LOGOUT, AUTH_LOGIN_FAILED, ACCESS_ENDPOINT, DOWNLOAD_DOCUMENT, SEND_EXTERNAL, DATA_CREATE, DATA_UPDATE, DATA_DELETE, STATUS_CHANGE, BULK_IMPORT, BULK_EXPORT, SYSTEM_ERROR, SECURITY_VIOLATION, PERMISSION_CHANGE. Example: <code>architecto</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>payload</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Data log sesuai log_type.</p>
            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>user_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="payload.user_id"                data-endpoint="POSTapi-v1-logs"
               value="16"
               data-component="body">
    <br>
<p>ID user. Required untuk: ACCESS_ENDPOINT, DOWNLOAD_DOCUMENT, SEND_EXTERNAL, DATA_CREATE, DATA_UPDATE, DATA_DELETE, STATUS_CHANGE, BULK_IMPORT, BULK_EXPORT, PERMISSION<em>CHANGE. Nullable untuk AUTH</em>* dan SECURITY_VIOLATION. Example: <code>16</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="payload.email"                data-endpoint="POSTapi-v1-logs"
               value="gbailey@example.net"
               data-component="body">
    <br>
<p>Email user. Required untuk: AUTH_LOGIN, AUTH_LOGOUT, AUTH_LOGIN_FAILED. Example: <code>gbailey@example.net</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>ip</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="payload.ip"                data-endpoint="POSTapi-v1-logs"
               value="architecto"
               data-component="body">
    <br>
<p>IP address. Optional untuk AUTH_* dan SECURITY_VIOLATION. Example: <code>architecto</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>device</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="payload.device"                data-endpoint="POSTapi-v1-logs"
               value="architecto"
               data-component="body">
    <br>
<p>Informasi device. Optional untuk AUTH_*. Example: <code>architecto</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>endpoint</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="payload.endpoint"                data-endpoint="POSTapi-v1-logs"
               value="architecto"
               data-component="body">
    <br>
<p>Endpoint yang diakses. Required untuk: ACCESS_ENDPOINT. Example: <code>architecto</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>method</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="payload.method"                data-endpoint="POSTapi-v1-logs"
               value="architecto"
               data-component="body">
    <br>
<p>HTTP method. Required untuk: ACCESS_ENDPOINT. Allowed: GET, POST, PUT, PATCH, DELETE. Example: <code>architecto</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="payload.status"                data-endpoint="POSTapi-v1-logs"
               value="16"
               data-component="body">
    <br>
<p>HTTP status code. Required untuk: ACCESS_ENDPOINT. Example: <code>16</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>document_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="payload.document_id"                data-endpoint="POSTapi-v1-logs"
               value="architecto"
               data-component="body">
    <br>
<p>ID dokumen. Required untuk: DOWNLOAD_DOCUMENT. Example: <code>architecto</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>document_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="payload.document_name"                data-endpoint="POSTapi-v1-logs"
               value="architecto"
               data-component="body">
    <br>
<p>Nama dokumen. Optional untuk: DOWNLOAD_DOCUMENT. Example: <code>architecto</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>channel</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="payload.channel"                data-endpoint="POSTapi-v1-logs"
               value="architecto"
               data-component="body">
    <br>
<p>Channel. Required untuk: SEND_EXTERNAL. Allowed: WA, EMAIL, API. Example: <code>architecto</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>to</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="payload.to"                data-endpoint="POSTapi-v1-logs"
               value="architecto"
               data-component="body">
    <br>
<p>Tujuan pengiriman. Required untuk: SEND_EXTERNAL. Juga digunakan sebagai status akhir untuk STATUS_CHANGE. Example: <code>architecto</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>message</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="payload.message"                data-endpoint="POSTapi-v1-logs"
               value="architecto"
               data-component="body">
    <br>
<p>Pesan. Required untuk: SYSTEM_ERROR. Optional untuk: SEND_EXTERNAL. Example: <code>architecto</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>meta</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="payload.meta"                data-endpoint="POSTapi-v1-logs"
               value=""
               data-component="body">
    <br>
<p>Metadata tambahan. Optional untuk: SEND_EXTERNAL, SECURITY_VIOLATION.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>data</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="payload.data"                data-endpoint="POSTapi-v1-logs"
               value=""
               data-component="body">
    <br>
<p>Data yang dibuat. Required untuk: DATA_CREATE.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>before</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="payload.before"                data-endpoint="POSTapi-v1-logs"
               value=""
               data-component="body">
    <br>
<p>Data sebelum perubahan. Required untuk: DATA_UPDATE, PERMISSION_CHANGE.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>after</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="payload.after"                data-endpoint="POSTapi-v1-logs"
               value=""
               data-component="body">
    <br>
<p>Data sesudah perubahan. Required untuk: DATA_UPDATE, PERMISSION_CHANGE.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="payload.id"                data-endpoint="POSTapi-v1-logs"
               value="architecto"
               data-component="body">
    <br>
<p>ID record. Required untuk: DATA_DELETE, STATUS_CHANGE. Example: <code>architecto</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>reason</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="payload.reason"                data-endpoint="POSTapi-v1-logs"
               value="architecto"
               data-component="body">
    <br>
<p>Alasan. Required untuk: SECURITY_VIOLATION. Optional untuk: DATA_DELETE. Example: <code>architecto</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>from</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="payload.from"                data-endpoint="POSTapi-v1-logs"
               value="architecto"
               data-component="body">
    <br>
<p>Status awal. Required untuk: STATUS_CHANGE. Example: <code>architecto</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>total_rows</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="payload.total_rows"                data-endpoint="POSTapi-v1-logs"
               value="16"
               data-component="body">
    <br>
<p>Total baris. Required untuk: BULK_IMPORT, BULK_EXPORT. Example: <code>16</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>success</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="payload.success"                data-endpoint="POSTapi-v1-logs"
               value="16"
               data-component="body">
    <br>
<p>Total sukses. Required untuk: BULK_IMPORT, BULK_EXPORT. Example: <code>16</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>failed</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="payload.failed"                data-endpoint="POSTapi-v1-logs"
               value="16"
               data-component="body">
    <br>
<p>Total gagal. Required untuk: BULK_IMPORT, BULK_EXPORT. Example: <code>16</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>file_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="payload.file_name"                data-endpoint="POSTapi-v1-logs"
               value="architecto"
               data-component="body">
    <br>
<p>Nama file. Optional untuk: BULK_IMPORT, BULK_EXPORT. Example: <code>architecto</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>code</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="payload.code"                data-endpoint="POSTapi-v1-logs"
               value="architecto"
               data-component="body">
    <br>
<p>Kode error. Optional untuk: SYSTEM_ERROR. Example: <code>architecto</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>trace_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="payload.trace_id"                data-endpoint="POSTapi-v1-logs"
               value="architecto"
               data-component="body">
    <br>
<p>Trace ID. Optional untuk: SYSTEM_ERROR. Example: <code>architecto</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>context</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="payload.context"                data-endpoint="POSTapi-v1-logs"
               value=""
               data-component="body">
    <br>
<p>Konteks tambahan. Optional untuk: SYSTEM_ERROR.</p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>target_user_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="payload.target_user_id"                data-endpoint="POSTapi-v1-logs"
               value="16"
               data-component="body">
    <br>
<p>Target user ID. Required untuk: PERMISSION_CHANGE. Example: <code>16</code></p>
                    </div>
                                    </details>
        </div>
        </form>

            

        
    </div>
    <div class="dark-box">
                    <div class="lang-selector">
                                                        <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                                        <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                            </div>
            </div>
</div>
</body>
</html>
