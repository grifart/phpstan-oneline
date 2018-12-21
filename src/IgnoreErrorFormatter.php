<?php declare(strict_types = 1);

namespace Grifart\PhpstanOneLine;

use PHPStan\Command\AnalysisResult;
use PHPStan\Command\ErrorFormatter\ErrorFormatter;
use PHPStan\File\RelativePathHelper;
use Symfony\Component\Console\Style\OutputStyle;
use Symfony\Component\Yaml\Yaml;

class IgnoreErrorFormatter implements ErrorFormatter
{
    /** @var RelativePathHelper */
    private $relativePathHelper;

    public function __construct(RelativePathHelper $relativePathHelper)
    {
        $this->relativePathHelper = $relativePathHelper;
    }

    public function formatErrors(
        AnalysisResult $analysisResult,
        OutputStyle $style
    ): int
    {
        if (!$analysisResult->hasErrors()) {
            return 0;
        }

        $errors = [];

        foreach ($analysisResult->getFileSpecificErrors() as $fileSpecificError) {
            $errors[$fileSpecificError->getFile()][] = $fileSpecificError->getMessage();
        }

        foreach ($errors as $file => $fileErrors) {
            $fileErrors = array_unique($fileErrors);

            foreach ($fileErrors as $message) {
                $style->writeln(
                    sprintf(
                        "        -\n            message: %s\n            path: %s",
                        Yaml::dump('%currentWorkingDirectory%/src/' . $this->relativePathHelper->getRelativePath($file)),
                        Yaml::dump('~' . preg_quote(substr($message, 0, -1), '~') . '~')
                    )
                );
            }
        }

        return 1;
    }

}
