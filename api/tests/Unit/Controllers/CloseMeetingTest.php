<?php

namespace MintHCM\Tests\Unit\Controllers;

use MintHCM\Modules\Meetings\api\controllers\CloseMeeting;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Psr7\Response;

final class CloseMeetingTest extends TestCase
{
    // NOTE: ACL-denial and happy-path (status set to 'Held', save() invoked, response structure)
    // are intentionally not covered by this unit test — they require BeanFactory::getBean(), which
    // triggers the real legacy bootstrap (chdir + DB) not available in tests/bootstrap.php. These
    // paths are covered by the E2E test: tests/modules/meetings/tests/close-meeting.spec.js.
    public function testThrowsBadRequestWhenIdIsMissing(): void
    {
        $this->expectException(HttpBadRequestException::class);
        (new CloseMeeting())($this->requestWithId(null), new Response());
    }

    public function testThrowsBadRequestWhenIdIsEmptyString(): void
    {
        $this->expectException(HttpBadRequestException::class);
        (new CloseMeeting())($this->requestWithId(''), new Response());
    }

    private function requestWithId(?string $id): ServerRequestInterface
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getAttribute')->with('id')->willReturn($id);
        return $request;
    }
}
