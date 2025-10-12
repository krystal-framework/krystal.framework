<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */
namespace Krystal\Templating;

final class TabTemplate
{
    /**
     * Parse HTML content with tab markers into structured data
     * 
     * Parses content that follows this format:
     * - Everything before the first "[TabName]" marker is considered main content
     * - Each "[TabName]" line starts a new tab section (even if wrapped in HTML tags)
     * - Content following a tab marker belongs to that tab until the next marker
     * 
     * @param string $html The input HTML string to parse, containing tab markers
     * @return array Returns structured array
     */
    public function parse($html)
    {
        $result = [
            'content' => '',
            'tabs' => []
        ];

        // Normalize line endings and split into lines
        $normalizedHtml = str_replace(["\r\n", "\r"], "\n", $html);
        $lines = explode("\n", $normalizedHtml);

        $currentSection = 'content';
        $currentContent = [];
        $currentTabName = '';

        foreach ($lines as $line) {
            $trimmedLine = trim($line);

            // Check if this line contains a tab separator [TabName], even if wrapped in HTML
            if (preg_match('/\[([^\]]+)\]/u', $trimmedLine, $matches)) {
                // Check if the entire line is just the tab marker (with optional HTML tags)
                $strippedLine = strip_tags($trimmedLine);
                if (trim($strippedLine) === '[' . $matches[1] . ']') {
                    // Save the current section before starting a new one
                    if ($currentSection === 'content' && !empty($currentContent)) {
                        $result['content'] = trim(implode("\n", $currentContent));
                        $currentContent = [];
                    } elseif ($currentSection === 'tab' && !empty($currentTabName)) {
                        $result['tabs'][] = [
                            'name' => $currentTabName,
                            'text' => trim(implode("\n", $currentContent))
                        ];
                        $currentContent = [];
                    }
                    
                    // Start new tab section
                    $currentSection = 'tab';
                    $currentTabName = trim($matches[1]);
                    continue; // Skip adding this line to content
                }
            }
            
            // Add line to current section
            $currentContent[] = $line;
        }

        // Handle the last section after processing all lines
        if ($currentSection === 'content' && !empty($currentContent)) {
            $result['content'] = trim(implode("\n", $currentContent));
        } elseif ($currentSection === 'tab' && !empty($currentTabName)) {
            $result['tabs'][] = [
                'name' => $currentTabName,
                'text' => trim(implode("\n", $currentContent))
            ];
        }

        return $result;
    }
}