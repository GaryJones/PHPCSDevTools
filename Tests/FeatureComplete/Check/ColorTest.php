<?php
/**
 * PHPCSDevTools, tools for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSDevTools
 * @copyright 2019 PHPCSDevTools Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSDevTools
 */

namespace PHPCSDevTools\Tests\FeatureComplete\Check;

use PHPCSDevTools\Tests\FeatureComplete\Check\CheckTestCase;

/**
 * Test colorized output.
 *
 * @covers \PHPCSDevTools\Scripts\FeatureComplete\Check::isComplete
 *
 * @phpcs:disable Squiz.Arrays.ArrayDeclaration.DoubleArrowNotAligned -- If needed, fix once replaced by better sniff.
 */
final class ColorTest extends CheckTestCase
{

    /**
     * Directory containing the fixtures for the tests in this class.
     *
     * @var string
     */
    const FIXTURE_DIR = './Tests/Fixtures/FeatureComplete/';

    /**
     * Verify the correct text snippets will be colorized when colors are enabled.
     *
     * @dataProvider dataColors
     *
     * @param string $fixtureDir     Relative path within the fixture directory to use for the test.
     * @param string $expectedOutput Colorized snippet of the expected output.
     * @param int    $exitCode       The expected exit code.
     * @param string $cliExtra       Optional. Additional CLI arguments to pass.
     *
     * @return void
     */
    public function testColors($fixtureDir, $expectedOutput, $exitCode, $cliExtra = '')
    {
        $command = 'phpcs-check-feature-completeness --colors ' . $cliExtra . ' ' . self::FIXTURE_DIR . $fixtureDir;
        $regex   = '`' .  \preg_quote($expectedOutput, '`') . '`';

        $this->runValidation($command, $regex, $exitCode);
    }

    /**
     * Data provider.
     *
     * @return array
     */
    public function dataColors()
    {
        return [
            'feature complete - warning message for missing file' => [
                'fixtureDir'     => 'MissingDocFiles/SingleSniff',
                'expectedOutput' => "\033[33mWARNING\033[0m:",
                'exitCode'       => 1,
            ],
            'feature complete - error message for missing file' => [
                'fixtureDir'     => 'MissingTestFiles/SingleSniff',
                'expectedOutput' => "\033[31mERROR\033[0m:",
                'exitCode'       => 1,
            ],
            'feature complete - success' => [
                'fixtureDir'     => 'ValidStandards/CompleteSingleSniff',
                'expectedOutput' => "\033[32mFound 1 sniff accompanied by unit tests and documentation.\033[0m",
                'exitCode'       => 0,
            ],
            'feature complete - summary: has errors and warnings' => [
                'fixtureDir'     => 'MissingTestsAndDocs/MultipleSniffs',
                'expectedOutput' => "Found \033[31m3 errors\033[0m and \033[33m2 warnings\033[0m.",
                'exitCode'       => 1,
            ],
            'feature complete - summary: has errors, no warnings' => [
                'fixtureDir'     => 'MissingTestFiles/MultipleSniffs',
                'expectedOutput' => "Found \033[31m3 errors\033[0m and 0 warnings.",
                'exitCode'       => 1,
            ],
            'feature complete - summary: no errors, has warnings' => [
                'fixtureDir'     => 'MissingDocFiles/MultipleSniffs',
                'expectedOutput' => "Found 0 errors and \033[33m2 warnings\033[0m.",
                'exitCode'       => 1,
            ],
            'feature complete - summary: quiet mode - has errors' => [
                'fixtureDir'     => 'MissingTestsAndDocs/MultipleSniffs',
                'expectedOutput' => "Found \033[31m3 errors\033[0m",
                'exitCode'       => 1,
                'cliExtra'       => '-q',
            ],
        ];
    }
}
