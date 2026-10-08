<?php

declare(strict_types=1);

require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/flash.php';
require_once __DIR__ . '/includes/auth.php';

$pageTitle = 'Send an enquiry';
$oldInput = $_SESSION['old_input'] ?? [];
unset($_SESSION['old_input']);
$errors = $_SESSION['form_errors'] ?? [];
unset($_SESSION['form_errors']);
require __DIR__ . '/includes/header.php';
?>
<main>
    <section class="hero-band">
        <div class="container hero-content">
            <p class="eyebrow">Contact our team</p>
            <h1>Tell us how we can help.</h1>
            <p class="lede">Send your enquiry and our team will review it as soon as possible.</p>
        </div>
    </section>
    <section class="container content-grid">
        <div class="intro-panel">
            <h2>Make an enquiry</h2>
            <p>Share a few details so we can direct your message to the right person.</p>
            <dl class="contact-notes">
                <div><dt>Response</dt><dd>We aim to respond within two working days.</dd></div>
                <div><dt>Privacy</dt><dd>Your details are used only to manage this enquiry.</dd></div>
            </dl>
        </div>
        <form class="form-panel" action="<?= e(BASE_URL) ?>/actions/submit-enquiry.php" method="post" novalidate>
            <?= csrfField() ?>
            <div class="form-row two-columns">
                <div class="field">
                    <label for="name">Name</label>
                    <input id="name" name="name" type="text" maxlength="100" autocomplete="name" required value="<?= e($oldInput['name'] ?? '') ?>">
                    <small class="field-error" data-error-for="name"><?= e($errors['name'] ?? '') ?></small>
                </div>
                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" maxlength="255" autocomplete="email" required value="<?= e($oldInput['email'] ?? '') ?>">
                    <small class="field-error" data-error-for="email"><?= e($errors['email'] ?? '') ?></small>
                </div>
            </div>
            <div class="form-row two-columns">
                <div class="field">
                    <label for="phone">Phone</label>
                    <input id="phone" name="phone" type="tel" maxlength="30" autocomplete="tel" required value="<?= e($oldInput['phone'] ?? '') ?>">
                    <small class="field-error" data-error-for="phone"><?= e($errors['phone'] ?? '') ?></small>
                </div>
                <div class="field">
                    <label for="enquiry_type">Enquiry type</label>
                    <select id="enquiry_type" name="enquiry_type" required>
                        <option value="">Select a type</option>
                        <?php foreach (ENQUIRY_TYPES as $type): ?>
                            <option value="<?= e($type) ?>" <?= ($oldInput['enquiry_type'] ?? '') === $type ? 'selected' : '' ?>><?= e($type) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <small class="field-error" data-error-for="enquiry_type"><?= e($errors['enquiry_type'] ?? '') ?></small>
                </div>
            </div>
            <div class="field">
                <label for="subject">Subject</label>
                <input id="subject" name="subject" type="text" maxlength="200" required value="<?= e($oldInput['subject'] ?? '') ?>">
                <small class="field-error" data-error-for="subject"><?= e($errors['subject'] ?? '') ?></small>
            </div>
            <div class="field">
                <label for="message">Message</label>
                <textarea id="message" name="message" rows="7" maxlength="5000" required><?= e($oldInput['message'] ?? '') ?></textarea>
                <small class="field-error" data-error-for="message"><?= e($errors['message'] ?? '') ?></small>
            </div>
            <button class="button button-primary" type="submit">Send enquiry</button>
        </form>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
