<?php
require_once __DIR__ . '/database.php';

class AddMenuController
{
    private database $db;

    public function __construct(database $db)
    {
        $this->db = $db;
    }

    /**
     * Validate and normalize incoming data.
     * Expected keys: name (string), description (string), pax (string), price (number), availability (0|1|"available"|"unavailable").
     * Returns [data => array, errors => array]
     */
    public function validate(array $input): array
    {
        $errors = [];

        $name = trim((string)($input['name'] ?? ''));
        $description = trim((string)($input['description'] ?? ''));
        $pax = trim((string)($input['pax'] ?? ''));
        $priceRaw = $input['price'] ?? '';
        $availabilityRaw = $input['availability'] ?? $input['avail'] ?? $input['foodAvailability'] ?? '';

        if ($name === '') { $errors['name'] = 'Name is required.'; }
        if ($description === '') { $errors['description'] = 'Description is required.'; }
        if ($pax === '') { $errors['pax'] = 'Pax is required.'; }

        if ($priceRaw === '' || !is_numeric($priceRaw)) {
            $errors['price'] = 'Valid price is required.';
        }
        $price = (float)$priceRaw;
        if ($price < 0) { $errors['price'] = 'Price must be non-negative.'; }

        // Normalize availability
        $availability = 0;
        if (is_string($availabilityRaw)) {
            $availability = strtolower($availabilityRaw) === 'available' || $availabilityRaw === '1' ? 1 : 0;
        } else {
            $availability = (int)$availabilityRaw === 1 ? 1 : 0;
        }

        return [
            'data' => [
                'name' => $name,
                'description' => $description,
                'pax' => $pax,
                'price' => $price,
                'availability' => $availability,
            ],
            'errors' => $errors,
        ];
    }

    /**
     * Execute add menu.
     */
    public function add(array $input): array
    {
        $validated = $this->validate($input);
        if (!empty($validated['errors'])) {
            return [ 'success' => false, 'errors' => $validated['errors'] ];
        }

        $d = $validated['data'];
        try {
            $ok = $this->db->addMenu($d['name'], $d['description'], $d['pax'], $d['price'], $d['availability']);
            if ($ok) {
                return [ 'success' => true, 'message' => 'Menu added successfully.' ];
            }
            return [ 'success' => false, 'errors' => [ 'general' => 'Failed to add menu.' ] ];
        } catch (Throwable $e) {
            return [ 'success' => false, 'errors' => [ 'exception' => $e->getMessage() ] ];
        }
    }
}
