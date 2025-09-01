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
}
