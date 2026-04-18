<?php
/**
 * CSRF Protection Helpers
 * 
 * Usage in forms:        echo csrf_field();
 * Usage in server-side: csrf_verify();  (dies with 403 if invalid)
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Generate (or retrieve) the session CSRF token.
 */
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Return an HTML hidden input field containing the CSRF token.
 */
function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token()) . '">';
}

/**
 * Return a JS-safe CSRF token string for fetch() calls.
 */
function csrf_token_js(): string {
    return csrf_token();
}

/**
 * Verify the CSRF token submitted with a POST request.
 * Sends a 403 JSON error and exits if the token is missing or invalid.
 *
 * @param bool $json  If true, returns a JSON error response (for AJAX endpoints).
 */
function csrf_verify(bool $json = true): void {
    $submitted = $_POST['csrf_token'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');
    $valid     = isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $submitted);

    if (!$valid) {
        http_response_code(403);
        if ($json) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Invalid security token. Please refresh the page.']);
        } else {
            echo 'Forbidden: Invalid CSRF token.';
        }
        exit;
    }
}
