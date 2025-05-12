<?php

declare(strict_types=1);

use PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use PHPStan\PhpDocParser\Parser\TypeParser;
use PHPStan\PhpDocParser\ParserConfig;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflector\DefaultReflector;
use Roave\BetterReflection\SourceLocator\Type\SingleFileSourceLocator;
use phpDocumentor\Reflection\DocBlockFactory;

require_once __DIR__ . '/../vendor/autoload.php';

$config = new ParserConfig(usedAttributes: []);
$lexer = new Lexer($config);
$constExprParser = new ConstExprParser($config);
$typeParser = new TypeParser($config, $constExprParser);
$phpDocParser = new PhpDocParser($config, $typeParser, $constExprParser);

$factory = DocBlockFactory::createInstance();

$astLocator = (new BetterReflection())->astLocator();
$reflector = new DefaultReflector(new SingleFileSourceLocator(__DIR__ . '/../src/functions.php', $astLocator));

$functions = $reflector->reflectAllFunctions();

$formatTextBlock = function (string $text, int $leadingSpaces = 3, bool $wrap = true): string {
    $text = $wrap ? preg_replace('/\n(?!\n)/', ' ', $text) : $text;
    $text = explode("\n", (string) $text);

    if (array_filter($text) === []) {
        return '';
    }

    $textLines = [];
    $inCodeBlock = false;

    foreach ($text as $line) {
        if (str_starts_with($line, '```')) {
            $inCodeBlock = !$inCodeBlock;
            if ($inCodeBlock) {
                $textLines[] = str_repeat(' ', $leadingSpaces) . '.. code-block:: ' . (trim(substr($line, 3)) ?: 'php');
                $textLines[] = '';
                $leadingSpaces += 3;
            } else {
                $leadingSpaces -= 3;
            }

            continue;
        }

        array_push($textLines, ...array_map(
            fn (string $line) => $line !== '' ? str_repeat(' ', $leadingSpaces) . $line : '',
            explode("\n", $wrap ? wordwrap(trim($line), 120 - $leadingSpaces) : rtrim($line)),
        ));
    }

    return implode("\n", $textLines);
};

$formatParamDescription = function (string $description) use ($formatTextBlock): string {
    $description = $formatTextBlock($description, 0, false);

    return implode(' ', array_map(trim(...), explode("\n", $description)));
};

/**
 * @param array<ParamTagValueNode> $parameters
 */
$findParameter = function (array $parameters, string $name): ?ParamTagValueNode {
    /** @var ParamTagValueNode $parameter */
    foreach ($parameters as $parameter) {
        if ($parameter->parameterName === '$' . $name) {
            return $parameter;
        }
    }

    return null;
};

echo ".. _matchers:\n\n";
echo "Matchers\n";
echo "========\n\n";

echo '.. php:namespace:: RestCertain\Hamcrest';
echo "\n\n";

foreach ($functions as $function) {
    $tokens = new TokenIterator($lexer->tokenize((string) $function->getDocComment()));
    $phpDocNode = $phpDocParser->parse($tokens);
    $docBlock = $factory->create((string) $function->getDocComment());

    if ($docBlock->hasTag('internal')) {
        continue;
    }

    echo '.. php:function:: ';
    echo $function->getShortName() . '()';
    echo "\n\n";

    $summary = $formatTextBlock($docBlock->getSummary(), 3);
    if ($summary !== '') {
        echo $summary . "\n\n";
    }

    $description = $formatTextBlock($docBlock->getDescription()->render(), 3, false);
    if ($description !== '') {
        echo $description . "\n\n";
    }

    $paramTags = $phpDocNode->getParamTagValues();

    foreach ($function->getParameters() as $parameter) {
        $paramTag = $findParameter($paramTags, $parameter->getName());

        echo '   :param ';
        echo addslashes((string) $parameter->getType());
        echo $parameter->isVariadic() ? ' ...' : ' ';
        echo '$';
        echo $parameter->getName() . ': ';
        if ($paramTag !== null) {
            echo $formatParamDescription($paramTag->description);
        }
        echo "\n";
    }

    foreach ($phpDocNode->getReturnTagValues() as $returnTag) {
        echo '   :returns: ' . $formatParamDescription($returnTag->description) . "\n";
    }

    echo '   :returntype: ' . addslashes((string) $function->getReturnType()) . "\n";
    echo "\n";
}
