<?php

namespace Juangcarmona\Courtly\Tests\Unit\Application\Controllers;

use Juangcarmona\Courtly\Application\Controllers\AdminCourtAvailabilityController;
use Juangcarmona\Courtly\Domain\Repositories\CourtRepositoryInterface;
use PHPUnit\Framework\TestCase;

class AdminCourtAvailabilityControllerTest extends TestCase
{
    protected function tearDown(): void
    {
        \Mockery::close();
    }

    public function testGetViewDataReturnsCourtsAndSelectedCourtId(): void
    {
        $courtMock = \Mockery::mock();
        $courtMock->shouldReceive('getId')->andReturn(1);

        $courtRepo = \Mockery::mock(CourtRepositoryInterface::class);
        $courtRepo->shouldReceive('findAll')->andReturn([$courtMock]);

        $_GET['court_id'] = null;

        $controller = new AdminCourtAvailabilityController($courtRepo);
        $data = $controller->getViewData();

        $this->assertArrayHasKey('courts', $data);
        $this->assertArrayHasKey('selectedCourtId', $data);
        $this->assertEquals(1, $data['selectedCourtId']);
        $this->assertEquals([$courtMock], $data['courts']);
    }
}
