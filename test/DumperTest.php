<?php declare(strict_types=1);

use OAS\Document\Factory\Dumper;
use OAS\OpenApiDocument;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Variable;
use PhpParser\PrettyPrinter;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/TestHelper.php';

class DumperTest extends TestCase
{
    use TestHelper;

    /**
     * @test
     * @dataProvider openApiDocumentProvider
     */
    public function itBuildsCorrectAst(OpenApiDocument $spec): void
    {
        $rawSpec = $this->getRawSpec();
        $variableName = 'specFromGeneratedAST';
        $ast = (new Dumper)->getAstFromPrimitives($rawSpec);

        eval(
            (new PrettyPrinter\Standard)->prettyPrint(
                [
                    new Assign(
                        new Variable($variableName),
                        $ast
                    )
                ]
            )
            .
            ";"
        );

        $this->assertJsonStringEqualsJsonString(
            json_encode($$variableName), json_encode($spec)
        );
    }
}
