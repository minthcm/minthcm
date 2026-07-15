<?php

namespace MintMCP\Capabilities\Resources;

use Mcp\Capability\Attribute\McpResource;

final class SearchRecordsResource extends AbstractMCPAppResource
{
    public const URI = 'ui://mint-mcp/search-records';

    #[McpResource(
        uri: self::URI,
        name: 'search_records_app',
        description: 'Table view for search_records results with links to each record.',
        mimeType: 'text/html;profile=mcp-app',
        meta: [
            'ui' => [
                'prefersBorder' => true,
            ],
        ],
    )]
    public function getSearchRecordsApp(): string
    {
        $content = @file_get_contents(self::appDistPath('search-records'));

        if (!\is_string($content) || $content === '') {
            return '<!doctype html><html><body><p>search_records app is unavailable.</p></body></html>';
        }

        return $content;
    }
}
