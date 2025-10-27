@extends('admin.layouts.admin')
@section('title', 'Api')
@section('content')
    <!-- Settings -->
    <div class="settings">
        @include('admin.partials.settings')
        <!-- Settings Content -->
        <div class="settings-content w-100">
            <div class="box">
                <h5 class="mb-4">{{ __('Api Key') }}</h5>

                <form action="{{ route('admin.settings.api.update') }}" method="POST">
                    @csrf

                    <div class="row row-cols-1 g-3 mt-3">
                        <x-label name="Api Key" for="test_email" />
                        <div class="input-group">
                            <x-input readonly name='api_key'
                                value="{{ is_demo() ? 'Hidden in demo' : getSetting('api_key') }}" required
                                :show-errors="false" aria-label="Recipient's username" aria-describedby="button-addon2" />
                            <button id="gen_api" class="btn btn-outline-primary" type="button"
                                id="button-addon2">{{ __('Generate!') }}</button>
                        </div>
                        <x-error name="api_key" />

                        <div class="col">
                            <x-label name="Enable Api" for="enable_api" />
                            <select class="select-input" hidden name="enable_api" id="enable_api">
                                <option {{ getSetting('enable_api') == 1 ? 'selected' : '' }} value="1">
                                    {{ __('Enabled') }}
                                </option>
                                <option {{ getSetting('enable_api') == 0 ? 'selected' : '' }} value="0">
                                    {{ __('Disabled') }}
                                </option>
                            </select>
                            <x-error name="enable_api" />
                        </div>

                        <div class="col">
                            <x-button class="btn-md w-100" />
                        </div>
                    </div>
                </form>
            </div>

            <div class="box mt-3">
                <h5 class="mb-4">{{ __('API Documentation') }}</h5>
                <div class="alert alert-important alert-info alert-dismissible br-dash-2" role="alert">
                    <div class="d-flex">
                        <div>
                            {{ __('This documentation provides details on how to use the API endpoints.') }}
                        </div>
                    </div>
                    <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
                </div>

                <!-- API Documentation Sections -->
                <div class="accordion" id="apiDocsAccordion">
                    <!-- Get Domains -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                Get Domains
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne"
                            data-bs-parent="#apiDocsAccordion">
                            <div class="accordion-body">
                                <p><strong>Endpoint:</strong> <code>api/domains/{apiKey}/{type}</code></p>
                                <p><strong>Method:</strong> <code>GET</code></p>
                                <p><strong>Parameters:</strong></p>
                                <ul>
                                    <li><code>apiKey</code> - Your API key.</li>
                                    <li><code>type</code> - Type of domains to fetch (free, premium, all).</li>
                                </ul>
                                <p><strong>Example Request:</strong></p>
                                <pre><code>GET api/domains/your-api-key/all</code></pre>
                                <p><strong>Example Response:</strong></p>
                                <pre><code>{
    "status": true,
    "data": {
        "domains": [
            {
                "domain": "free.com",
                "type": "Free"
            },
            {
                "domain": "lobage.com",
                "type": "Free"
            },
            {
                "domain": "premium.com",
                "type": "Premium"
            }
        ]
    }
}</code></pre>
                            </div>
                        </div>
                    </div>

                    <!-- Create Email -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Create Email
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                            data-bs-parent="#apiDocsAccordion">
                            <div class="accordion-body">
                                <p><strong>Endpoint:</strong> <code>api/emails/{apiKey}</code></p>
                                <p><strong>Method:</strong> <code>POST</code></p>
                                <p><strong>Parameters:</strong></p>
                                <ul>
                                    <li><code>apiKey</code> - Your API key.</li>
                                </ul>
                                <p><strong>Example Request:</strong></p>
                                <pre><code>POST api/emails/your-api-key</code></pre>
                                <p><strong>Example Response:</strong></p>
                                <pre><code>{
    "status": true,
    "data": {
        "email": "random@example.com",
        "domain": "example.com",
        "ip": "127.0.0.1",
        "fingerprint": "eef780ab2ec7535e66c1405c0bff1c64",
        "expire_at": "2025-01-01T00:00:00.000000Z",
        "created_at": "2025-01-01T00:00:00.000000Z",
        "id": 1,
        "email_token": "email_token"
    }
}</code></pre>
                            </div>
                        </div>
                    </div>

                    <!-- Update Email -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Update Email
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
                            data-bs-parent="#apiDocsAccordion">
                            <div class="accordion-body">
                                <p><strong>Endpoint:</strong> <code>api/emails/{apiKey}/{email}/{username}/{domain}</code>
                                </p>
                                <p><strong>Method:</strong> <code>POST</code></p>
                                <p><strong>Parameters:</strong></p>
                                <ul>
                                    <li><code>apiKey</code> - Your API key.</li>
                                    <li><code>email</code> - The current email address.</li>
                                    <li><code>username</code> - The new username.</li>
                                    <li><code>domain</code> - The new domain.</li>
                                </ul>
                                <p><strong>Example Request:</strong></p>
                                <pre><code>POST api/emails/your-api-key/old@example.com/newuser/newexample.com</code></pre>
                                <p><strong>Example Response:</strong></p>
                                <pre><code>{
    "status": true,
    "data": {
        "email": "newuser@newexample.com",
        "domain": "newexample.com",
        "ip": "127.0.0.1",
        "fingerprint": "eef780ab2ec7535e66c1405c0bff1c64",
        "expire_at": "2025-01-01T00:00:00.000000Z",
        "created_at": "2025-01-01T00:00:00.000000Z",
        "id": 2,
        "email_token": "email_token"
    }
}</code></pre>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Email -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFour">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                Delete Email
                            </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour"
                            data-bs-parent="#apiDocsAccordion">
                            <div class="accordion-body">
                                <p><strong>Endpoint:</strong> <code>api/emails/{apiKey}/{email}</code></p>
                                <p><strong>Method:</strong> <code>POST</code></p>
                                <p><strong>Parameters:</strong></p>
                                <ul>
                                    <li><code>apiKey</code> - Your API key.</li>
                                    <li><code>email</code> - The email address to delete.</li>
                                </ul>
                                <p><strong>Example Request:</strong></p>
                                <pre><code>POST api/emails/your-api-key/old@example.com</code></pre>
                                <p><strong>Example Response:</strong></p>
                                <pre><code>{
    "status": true,
    "message": "Email has been successfully deleted."
}</code></pre>
                            </div>
                        </div>
                    </div>

                    <!-- Get Messages -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFive">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                Get Messages
                            </button>
                        </h2>
                        <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive"
                            data-bs-parent="#apiDocsAccordion">
                            <div class="accordion-body">
                                <p><strong>Endpoint:</strong> <code>api/messages/{apiKey}/{email}</code></p>
                                <p><strong>Method:</strong> <code>GET</code></p>
                                <p><strong>Parameters:</strong></p>
                                <ul>
                                    <li><code>apiKey</code> - Your API key.</li>
                                    <li><code>email</code> - The email address to fetch messages for.</li>
                                </ul>
                                <p><strong>Example Request:</strong></p>
                                <pre><code>GET api/messages/your-api-key/example@example.com</code></pre>
                                <p><strong>Example Response:</strong></p>
                                <pre><code>{
    "status": true,
    "mailbox": "example@example.com",
    "messages": [
        {
            "is_seen": true,
            "subject": "Test Subject",
            "from": "John Doe",
            "from_email": "john@example.com",
            "to": "example@example.com",
            "receivedAt": "2025-01-01 00:00:38",
            "id": "ap94AWDg123ELQz07vrVB9dLXlbqZM5NGwYxOJKko8n6m1",
            "html": true,
            "content": "Test content",
            "attachments": [
                {
                    "name": "file.txt",
                    "extension": "txt",
                    "size": 91,
                    "url": "http://yoursite.com/api/d/ap94AWDg123ELQz07vrVB9dLXlbqZM5NGwYxOJKko8n6m1/file.txt"
                }
            ]
        }
    ]
}</code></pre>
                            </div>
                        </div>
                    </div>

                    <!-- Get Message -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingSix">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                Get Message
                            </button>
                        </h2>
                        <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix"
                            data-bs-parent="#apiDocsAccordion">
                            <div class="accordion-body">
                                <p><strong>Endpoint:</strong> <code>api/messages/{apiKey}/message/{messageId}</code></p>
                                <p><strong>Method:</strong> <code>GET</code></p>
                                <p><strong>Parameters:</strong></p>
                                <ul>
                                    <li><code>apiKey</code> - Your API key.</li>
                                    <li><code>messageId</code> - The ID of the message to fetch.</li>
                                </ul>
                                <p><strong>Example Request:</strong></p>
                                <pre><code>GET api/messages/your-api-key/message/ap94AWDg123ELQz07vrVB9dLXlbqZM5NGwYxOJKko8n6m1</code></pre>
                                <p><strong>Example Response:</strong></p>
                                <pre><code>{
    "status": true,
    "data": {
        "is_seen": true,
        "subject": "Test Subject",
        "from": "John Doe",
        "from_email": "john@example.com",
        "to": "example@example.com",
        "receivedAt": "2024-12-28 00:00:38",
        "id": "ap94AWDg123ELQz07vrVB9dLXlbqZM5NGwYxOJKko8n6m1",
        "html": true,
        "content": "Test content",
        "attachments": [
            {
                "name": "file.txt",
                "extension": "txt",
                "size": 91,
                "url": "http://yoursite.come/api/d/ap94AWDg123ELQz07vrVB9dLXlbqZM5NGwYxOJKko8n6m1/file.txt"
            }
        ]
    }
}</code></pre>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Message -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingSeven">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                                Delete Message
                            </button>
                        </h2>
                        <div id="collapseSeven" class="accordion-collapse collapse" aria-labelledby="headingSeven"
                            data-bs-parent="#apiDocsAccordion">
                            <div class="accordion-body">
                                <p><strong>Endpoint:</strong> <code>api/messages/{apiKey}/message/{messageId}</code></p>
                                <p><strong>Method:</strong> <code>POST</code></p>
                                <p><strong>Parameters:</strong></p>
                                <ul>
                                    <li><code>apiKey</code> - Your API key.</li>
                                    <li><code>messageId</code> - The ID of the message to delete.</li>
                                </ul>
                                <p><strong>Example Request:</strong></p>
                                <pre><code>POST api/messages/your-api-key/message/ap94AWDg123ELQz07vrVB9dLXlbqZM5NGwYxOJKko8n6m1</code></pre>
                                <p><strong>Example Response:</strong></p>
                                <pre><code>{
    "status": true,
    "message": "Message was deleted successfully."
}</code></pre>
                            </div>
                        </div>
                    </div>

                    <!-- Download Attachment -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingEight">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                                Download Attachment
                            </button>
                        </h2>
                        <div id="collapseEight" class="accordion-collapse collapse" aria-labelledby="headingEight"
                            data-bs-parent="#apiDocsAccordion">
                            <div class="accordion-body">
                                <p><strong>Endpoint:</strong> <code>api/d/{hash_id}/{file?}</code></p>
                                <p><strong>Method:</strong> <code>GET</code></p>
                                <p><strong>Parameters:</strong></ <ul>
                                    <li><code>hash_id</code> - The hash ID of the message.</li>
                                    <li><code>file</code> - The name of the file to download (optional).</li>
                                    </ul>
                                <p><strong>Example Request:</strong></p>
                                <pre><code>GET api/d/abc123/filename.pdf</code></pre>
                                <p><strong>Example Response:</strong></p>
                                <p>The file will be downloaded directly.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Token to Email -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingNine">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
                                Token to Email
                            </button>
                        </h2>
                        <div id="collapseNine" class="accordion-collapse collapse" aria-labelledby="headingNine"
                            data-bs-parent="#apiDocsAccordion">
                            <div class="accordion-body">
                                <p><strong>Endpoint:</strong> <code>/token/{email_token}</code></p>
                                <p><strong>Method:</strong> <code>GET</code></p>
                                <p><strong>Description:</strong> Send your visitors to a site with a token to create the
                                    same email.</p>
                                <p><strong>Parameters:</strong></p>
                                <ul>
                                    <li><code>email_token</code> - Your email token </li>
                                </ul>
                                <p><strong>Example Response:</strong></p>
                                <p>The user will be redirected to a page where the email associated with the token is
                                    created or displayed.</p>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
        <!-- /Settings Content -->
    </div>
    <!-- /Settings -->
@endsection
