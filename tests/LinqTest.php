<?php
declare(strict_types=1);

namespace Tests;

use Linq\Linq;
use PHPUnit\Framework\TestCase;

class LinqTest extends TestCase
{
    private array $sports;
    private array $developers;

    public function setUp(): void
    {
        $this->sports = [
            ['userName' => 'Milan', 'sport' => 'šachy', 'active' => true],
            ['userName' => 'Milan', 'sport' => 'karate', 'active' => false],
            ['userName' => 'Honza', 'sport' => 'box', 'active' => true],
            ['userName' => 'Honza', 'sport' => 'fotbal', 'active' => false],
            ['userName' => 'Milan', 'sport' => 'hokej', 'active' => true],
            ['userName' => 'Petr', 'sport' => 'tenis', 'active' => true],
        ];

        $object = new \stdClass();
        $object->name = 'Vinicius';

        $object1 = new \stdClass();
        $object1->name = 'Daniel';

        $object2 = new \stdClass();
        $object2->name = 'Guilherme';

        $this->developers = [
            $object, $object1, $object2
        ];
    }

    public function testLinqSimple(): void
    {
        $linq = new Linq();

        $linq->from($this->sports)
            ->where(static function (array $item) {
                if ($item['userName'] === 'Milan') {
                    return $item;
                }

                return null;
            });

        $select = $linq->select();

        self::assertIsArray($select);
        self::assertCount(3, $select);

        $selectJustUserName = $linq->select('sport');

        self::assertEquals('karate', $selectJustUserName[1]);

        $count = $linq->from($this->sports)
            ->count();

        self::assertEquals(6, $count);

        $reverse = $linq->from($this->sports)
            ->reverse()->take(0);

        self::assertEquals('Petr', $reverse[0]['userName']);

        $distinct = $linq->from([
            ['userName' => 'Milan', 'sport' => 'šachy', 'active' => true],
            ['userName' => 'Milan', 'sport' => 'šachy', 'active' => true],
        ])->distinct()->count();

        self::assertEquals(1, $distinct);

        $first = $linq->from($this->sports)->first();

        self::assertEquals(['userName' => 'Milan', 'sport' => 'šachy', 'active' => true], $first);

        $last = $linq->from($this->sports)->last();

        self::assertEquals(['userName' => 'Petr', 'sport' => 'tenis', 'active' => true], $last);

        $devs = $linq->from($this->developers)->select('name');

        self::assertEquals(['Vinicius', 'Daniel', 'Guilherme'], $devs);
    }
}
