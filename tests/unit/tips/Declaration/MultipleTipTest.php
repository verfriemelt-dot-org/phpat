<?php

declare(strict_types=1);

namespace Tests\PHPat\unit\tips\Declaration;

use Attribute;
use PHPat\Configuration;
use PHPat\Rule\Assertion\Declaration\ShouldBeFinal\IsFinalRule;
use PHPat\Rule\Assertion\Declaration\ShouldBeFinal\ShouldBeFinal;
use PHPat\Rule\Assertion\Relation\CanOnlyDepend\CanOnlyDepend;
use PHPat\Rule\Assertion\Relation\CanOnlyDepend\ClassAttributeRule;
use PHPat\Selector\Classname;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\fixtures\FixtureClass;
use Tests\PHPat\fixtures\Simple\SimpleAttribute;
use Tests\PHPat\unit\FakeTestParser;

use function sprintf;

/**
 * @extends RuleTestCase<ClassAttributeRule>
 */
class MultipleTipTest extends RuleTestCase
{
    public const RULE_NAME = 'test_FixtureClassShouldBeFinal';
    public function testRule(): void
    {
        $this->analyse(['tests/fixtures/FixtureClass.php'], [
            [
                sprintf('%s should be final', FixtureClass::class),
                31,
                <<<TIPS
                • tip #1
                • tip #2
                TIPS,
            ],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
            ShouldBeFinal::class,
            [new Classname(FixtureClass::class, false)],
            [],
            ['tip #1', 'tip #2']
        );

        return new IsFinalRule(
            new StatementBuilderFactory($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
