<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Seo;

class StructuredData
{
    /**
     * Generate Website Schema.org markup.
     *
     * @param array $params {
     *     @type string $siteUrl   The website URL.
     *     @type string $siteName  The website name.
     *     @type string $language  The language code (e.g., "en", "en-US").
     *     @type string $searchUrl Optional. The URL pattern for search, e.g. "https://example.com/search?q={search_term_string}".
     * }
     *
     * @return array JSON-LD Website schema
     */
    public function generateWebsiteSchema(array $params): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type'    => 'WebSite',
            '@id'      => $params['siteUrl'] . '/#website',
            'url'      => $params['siteUrl'] ?? null,
            'name'     => $params['siteName'] ?? null,
            'publisher'=> [
                '@id' => $params['siteUrl'] . '/#organization',
            ],
            'inLanguage' => $params['language'] ?? null,
        ];

        // Add search action only if a search URL is provided
        if (!empty($params['searchUrl'])) {
            $schema['potentialAction'] = [
                '@type'       => 'SearchAction',
                'target'      => $params['searchUrl'],
                'query-input' => 'required name=search_term_string'
            ];
        }

        return $this->removeEmpty($schema);
    }

    /**
     * Generates a Schema.org Organization structured data array.
     *
     * This method creates a valid JSON-LD representation of an Organization,
     * including its name, URL, logo, and associated social profiles.
     * It follows Schema.org's "Organization" specification and can be
     * embedded on the website using <script type="application/ld+json">.
     *
     * Expected keys in $params:
     * - siteName (string, required)          The name of the organization.
     * - siteUrl (string, required)           The canonical URL of the organization.
     * - logo (array, optional)               Organization logo metadata:
     *     - url (string, required)           Logo image URL.
     *     - width (int, optional)            Logo width in pixels (default 112).
     *     - height (int, optional)           Logo height in pixels (default 112).
     * - socialProfiles (array, optional)     List of social profile URLs (e.g., Facebook, Twitter).
     *
     * @param array $params Organization details and metadata.
     *
     * @return array Structured data representing the Organization schema.
     *
     * @see https://schema.org/Organization
     */
    public function generateOrganizationSchema(array $params = [])
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $params['siteName'] ?? null,
            'url' => $params['siteUrl'] ?? null,
            'logo' => [
                '@type' => 'ImageObject',
                '@id' => ($params['siteUrl'] ?? '') . '/#logo',
                'url' => $params['logo']['url'] ?? null,
                'width' => $params['logo']['width'] ?? 112,
                'height' => $params['logo']['height'] ?? 112,
                'caption' => $params['siteName'] ?? null,
            ],
            'sameAs' => $params['socialProfiles'] ?? [],
        ];

        return $this->removeEmpty($schema);
    }

    /**
     * Generate LocalBusiness structured data (Schema.org JSON-LD).
     *
     * This method creates a Schema.org representation of a LocalBusiness entity
     * using the provided business data. It automatically removes empty/null
     * values to ensure valid structured data output for search engines.
     *
     * @param array $businessData {
     *     Business details used to build the LocalBusiness schema.
     *
     *     @type string $name         Business name (required).
     *     @type string $url          Website URL of the business (required).
     *     @type string $image        URL to an image or logo of the business.
     *     @type string $phone        Business phone number.
     *     @type string $address      Street address.
     *     @type string $city         City or locality.
     *     @type string $region       State or region.
     *     @type string $postalCode   Postal code.
     *     @type string $country      Country.
     *     @type float  $latitude     Latitude coordinate.
     *     @type float  $longitude    Longitude coordinate.
     *     @type array  $openingHours Opening hours in Schema.org format
     *                                (e.g. ["Mo-Fr 09:00-17:00", "Sa 10:00-14:00"]).
     *     @type string $priceRange   General price range (e.g. "$$", "€€", "cheap").
     * }
     *
     * @return array JSON-LD LocalBusiness schema data.
     *
     * @see https://schema.org/LocalBusiness
     * @see https://developers.google.com/search/docs/appearance/structured-data/local-business
     */
    public function generateLocalBusinessSchema(array $businessData)
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            '@id' => !empty($businessData['url']) ? $businessData['url'] . '/#localbusiness' : null,
            'name' => $businessData['name'] ?? null,
            'url' => $businessData['url'] ?? null,
            'image' => $businessData['image'] ?? null,
            'telephone' => $businessData['phone'] ?? null,
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $businessData['address'] ?? null,
                'addressLocality' => $businessData['city'] ?? null,
                'addressRegion' => $businessData['region'] ?? null,
                'postalCode' => $businessData['postalCode'] ?? null,
                'addressCountry' => $businessData['country'] ?? null,
            ],
            'geo' => [
                '@type' => 'GeoCoordinates',
                'latitude' => $businessData['latitude'] ?? null,
                'longitude' => $businessData['longitude'] ?? null,
            ],
            'openingHours' => $businessData['openingHours'] ?? null,
            'priceRange' => $businessData['priceRange'] ?? null
        ];

        return $this->removeEmpty($schema);
    }

    /**
     * Generate FAQPage JSON-LD structured data.
     *
     * @param array<int, array{question: string, answer: string}> $questions
     * @return array<string, mixed>
     */
    public function generateFAQSchema(array $questions)
    {
        $mainEntity = [];

        foreach ($questions as $question) {
            if (empty($question['question']) || empty($question['answer'])) {
                continue;
            }

            $mainEntity[] = [
                '@type' => 'Question',
                'name' => strip_tags($question['question']),
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => strip_tags($question['answer'])
                ]
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $mainEntity,
        ];
    }

    /**
     * Generate a Schema.org BreadcrumbList JSON-LD structure.
     *
     * Each item must have at least a "name" (string).
     * "url" is optional — typically omitted for the last breadcrumb (current page).
     *
     * @param array<int, array{name: string, url?: string}> $items
     *     An array of breadcrumb items, each containing:
     *       - name (string, required): The label of the breadcrumb.
     *       - url  (string, optional): The URL of the breadcrumb.
     *
     * @return array<string, mixed> A structured array ready to be JSON-encoded for use
     *                              in a <script type="application/ld+json"> block.
     */
    public function generateBreadcrumbSchema(array $items)
    {
        $itemListElement = [];

        foreach ($items as $index => $item) {
            // Validate required "name"
            if (empty($item['name']) || !is_string($item['name'])) {
                continue; // skip invalid breadcrumb
            }

            // Base ListItem
            $listItem = [
                '@type'    => 'ListItem',
                'position' => $index + 1,
                'name'     => strip_tags($item['name']) // normalization
            ];

            // Add URL only if present
            if (!empty($item['url']) && is_string($item['url'])) {
                $listItem['item'] = $item['url'];
            }

            $itemListElement[] = $listItem;
        }

        return [
            '@context' => 'https://schema.org',
            '@type'    => 'BreadcrumbList',
            'itemListElement' => $itemListElement,
        ];
    }

    /**
     * Recursively remove empty values (null, empty string, empty array) from schema arrays.
     *
     * @param array $data
     * @return array
     */
    private function removeEmpty(array $data)
    {
        return array_filter(array_map(function ($value) {
            if (is_array($value)) {
                return $this->removeEmpty($value);
            }
            return $value;
        }, $data), function ($value) {
            return !($value === null || $value === '' || (is_array($value) && empty($value)));
        });
    }
}
