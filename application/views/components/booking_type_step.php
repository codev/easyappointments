<?php
/**
 * Local variables.
 *
 * @var array $available_services
 */

// Group services by category. Card image/blurb come from the category description:
// a line starting with "image:" is the card image URL, the rest is the blurb.
$service_groups = [];

foreach ($available_services as $service) {
    $category_id = $service['service_category_id'] ?: 'other';

    if (!isset($service_groups[$category_id])) {
        $blurb = '';
        $image = '';

        foreach (preg_split('/\r?\n/', (string) ($service['service_category_description'] ?? '')) as $line) {
            if (str_starts_with(trim($line), 'image:')) {
                $image = trim(substr(trim($line), 6));
            } elseif (trim($line) !== '') {
                $blurb .= ($blurb ? ' ' : '') . trim($line);
            }
        }

        $service_groups[$category_id] = [
            'name' => $service['service_category_name'] ?? lang('other'),
            'blurb' => $blurb,
            'image' => $image,
            'services' => [],
        ];
    }

    $service_groups[$category_id]['services'][] = $service;
}
?>

<div id="wizard-frame-1" class="wizard-frame p-3 p-md-4" style="visibility: hidden;">
    <div class="frame-container py-3" style="min-height: 500px;">
        <h2 class="frame-title fw-light text-center mb-4 text-muted mt-md-5"><?= lang('service_and_provider') ?></h2>

        <div class="row frame-content">
            <div class="col col-lg-10 offset-lg-1">

                <div hidden>
                    <select id="select-service" class="form-select mb-4">
                        <option value="">
                            <?= lang('please_select') ?>
                        </option>
                        <?php foreach ($service_groups as $group): ?>
                            <optgroup label="<?= e($group['name']) ?>">
                                <?php foreach ($group['services'] as $service): ?>
                                    <option value="<?= $service['id'] ?>"><?= e($service['name']) ?></option>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div id="service-category-cards" class="row g-3 mb-4">
                    <?php foreach ($service_groups as $category_id => $group): ?>
                        <div class="col-6 col-md-4">
                            <div class="card category-card h-100" data-category-id="<?= e($category_id) ?>"
                                 role="button" tabindex="0">
                                <?php if ($group['image']): ?>
                                    <img src="<?= e($group['image']) ?>" class="card-img-top" alt=""
                                         loading="lazy">
                                <?php endif; ?>
                                <div class="card-body p-2 p-md-3">
                                    <h3 class="card-title fs-6 fw-bold mb-1"><?= e($group['name']) ?></h3>
                                    <?php if ($group['blurb']): ?>
                                        <p class="card-text small text-muted mb-0"><?= e($group['blurb']) ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div id="category-services" class="mb-3" hidden>
                    <label class="fs-5 mb-2">
                        <strong><?= lang('service') ?></strong>
                    </label>
                    <div class="list-group">
                        <?php foreach ($service_groups as $category_id => $group): ?>
                            <?php foreach ($group['services'] as $service): ?>
                                <button type="button"
                                        class="list-group-item list-group-item-action category-service d-flex justify-content-between align-items-center"
                                        data-service-id="<?= $service['id'] ?>"
                                        data-category-id="<?= e($category_id) ?>" hidden>
                                    <span><?= e($service['name']) ?></span>
                                    <span class="small text-muted text-nowrap ms-2"><?= $service[
                                        'duration'
                                    ] ?> <?= lang('minutes') ?></span>
                                </button>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="mb-3" hidden>
                    <label for="select-provider" class="fs-5 mb-2">
                        <strong><?= lang('provider') ?></strong>
                    </label>

                    <select id="select-provider" class="form-select mb-4">
                        <option value="">
                            <?= lang('please_select') ?>
                        </option>
                    </select>
                </div>

                <div id="service-description" class="small overflow-auto shadow-none" style="max-height: 153px;">
                    <!-- JS -->
                </div>

            </div>
        </div>
    </div>

    <div class="command-buttons text-center my-3 mx-auto d-md-flex justify-content-md-between">
        <span>&nbsp;</span>

        <button type="button" id="button-next-1" class="btn button-next btn-dark" style="min-width: 120px; margin-right: 10px;"
                data-step_index="1">
            <?= lang('next') ?>
            <i class="fas fa-chevron-right ms-2"></i>
        </button>
    </div>
</div>
